<?php
/**
 * AJAX Handler for Unique Client Page plugin
 *
 * @package    Unique_Client_Page
 * @subpackage Frontend
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class UCP_AJAX_Handler
 * Handles all AJAX requests for the plugin
 */
class UCP_AJAX_Handler {
    /**
     * Instance of this class
     *
     * @var UCP_AJAX_Handler
     */
    private static $instance = null;
    
    /**
     * Version manager instance
     * 
     * @var UCP_Wishlist_Version_Manager
     */
    private $version_manager = null;
    
    /**
     * Get version manager instance
     * 
     * @return UCP_Wishlist_Version_Manager
     */
    private function get_version_manager() {
        if (null === $this->version_manager) {
            $this->version_manager = UCP_Wishlist_Version_Manager::get_instance();
        }
        return $this->version_manager;
    }
    
    /**
     * Get current wishlist version for a user and page
     * 
     * @param int $user_id User ID
     * @param int $page_id Page ID
     * @return array|bool Current version data or false if not found
     */
    private function get_current_wishlist_version($user_id, $page_id) {
        $version_manager = $this->get_version_manager();
        if (!$version_manager) {
            return false;
        }
        
        // Get all versions for this user and page
        $versions = $version_manager->get_versions($user_id, $page_id);
        
        // Find the current version (is_current = 1)
        foreach ($versions as $version) {
            if (isset($version->is_current) && $version->is_current) {
                return array(
                    'version_id' => $version->version_id,
                    'version_number' => $version->version_number,
                    'version_name' => $version->version_name,
                    'created_at' => $version->created_at,
                    'notes' => $version->notes,
                    'wishlist_data' => $version->wishlist_data
                );
            }
        }
        
        // If no current version found, return the latest one or false
        if (!empty($versions)) {
            $latest = reset($versions);
            return array(
                'version_id' => $latest->version_id,
                'version_number' => $latest->version_number,
                'version_name' => $latest->version_name,
                'created_at' => $latest->created_at,
                'notes' => $latest->notes,
                'wishlist_data' => $latest->wishlist_data
            );
        }
        
        return false;
    }

    /**
     * Get the singleton instance of this class
     *
     * @return UCP_AJAX_Handler
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialize AJAX hooks
        $this->init_hooks();
        
        // Register login/logout handlers for wishlist synchronization
        add_action('wp_login', array($this, 'sync_wishlist_on_login'), 10, 2);
        add_action('wp_logout', array($this, 'sync_wishlist_on_logout'));
        
        // Schedule cleanup event if not already scheduled
        if (!wp_next_scheduled('ucp_cleanup_guest_wishlists')) {
            wp_schedule_event(time(), 'weekly', 'ucp_cleanup_guest_wishlists');
        }
    }
    


    /**
     * Add AJAX related hooks
     */
    public function init_hooks() {
        // 注册新版操作名称
        add_action('wp_ajax_ucp_update_wishlist', array($this, 'update_wishlist')); 
        add_action('wp_ajax_nopriv_ucp_update_wishlist', array($this, 'update_wishlist'));
        
        // 为了兼容性保留旧版操作名称
        add_action('wp_ajax_ucp_wishlist_handler', array($this, 'update_wishlist')); 
        add_action('wp_ajax_nopriv_ucp_wishlist_handler', array($this, 'update_wishlist'));
        
        // Main AJAX handler
        add_action('wp_ajax_ucp_ajax_handler', array($this, 'handle_ajax_request'));
        add_action('wp_ajax_nopriv_ucp_ajax_handler', array($this, 'handle_ajax_request'));
        
        // Wishlist handlers (Unified: use update_wishlist only)
        // Deprecated: handle_wishlist is no longer bound to avoid parallel implementations
        // add_action('wp_ajax_ucp_wishlist_handler', array($this, 'handle_wishlist'));
        // add_action('wp_ajax_nopriv_ucp_wishlist_handler', array($this, 'handle_wishlist'));
        
        add_action('wp_ajax_ucp_get_wishlist', array($this, 'get_wishlist'));
        add_action('wp_ajax_nopriv_ucp_get_wishlist', array($this, 'get_wishlist'));
        
        add_action('wp_ajax_ucp_get_wishlist_status', array($this, 'get_wishlist_status'));
        add_action('wp_ajax_nopriv_ucp_get_wishlist_status', array($this, 'get_wishlist_status'));
        
        add_action('wp_ajax_ucp_update_wishlist', array($this, 'update_wishlist'));
        add_action('wp_ajax_nopriv_ucp_update_wishlist', array($this, 'update_wishlist'));
        
        // Email handler
        add_action('wp_ajax_ucp_send_wishlist_email', array($this, 'send_wishlist_email'));
        add_action('wp_ajax_nopriv_ucp_send_wishlist_email', array($this, 'send_wishlist_email'));
        
        // Register the cleanup hook
        add_action('ucp_cleanup_guest_wishlists', array($this, 'cleanup_guest_wishlists'));
        
        // Wishlist version control handlers
        add_action('wp_ajax_ucp_save_wishlist_version', array($this, 'save_wishlist_version'));
        add_action('wp_ajax_ucp_get_wishlist_versions', array($this, 'get_wishlist_versions'));
        add_action('wp_ajax_ucp_restore_wishlist_version', array($this, 'restore_wishlist_version'));
        
        // 获取指定版本的愿望清单数据
        // Deprecated: handled by admin module to maintain consistent nonce/permissions
        // add_action('wp_ajax_ucp_get_wishlist_version', array($this, 'get_wishlist_version'));
        // add_action('wp_ajax_nopriv_ucp_get_wishlist_version', array($this, 'get_wishlist_version'));
    }

    /**
     * Main AJAX request handler
     */
    public function handle_ajax_request() {
        // Enable error logging only in debug mode
        $debug_mode = defined('WP_DEBUG') && WP_DEBUG;
        
        // Verify nonce
        if (!check_ajax_referer('ucp-ajax-nonce', 'nonce', false)) {
            $error_msg = 'Invalid or missing nonce';
            if ($debug_mode) {
                error_log('UCP AJAX Error: ' . $error_msg);
            }
            wp_send_json_error([
                'message' => $error_msg,
                'nonce' => wp_create_nonce('ucp-ajax-nonce')
            ]);
        }
        
        // Get action
        $action = isset($_POST['custom_action']) ? sanitize_text_field($_POST['custom_action']) : '';
        
        // If custom_action is empty, try to get from action parameter
        if (empty($action) && isset($_POST['action'])) {
            $action = str_replace('ucp_', '', sanitize_text_field($_POST['action']));
        }
        
        if (empty($action)) {
            $error_msg = 'No action specified';
            if ($debug_mode) {
                error_log('UCP AJAX Error: ' . $error_msg);
            }
            wp_send_json_error(['message' => $error_msg]);
        }
        
        try {
            switch ($action) {
                case 'load_product_detail':
                case 'get_product_details': // Added to support direct action parameter
                    $this->load_product_detail();
                    break;
                    
                case 'load_products':
                    $this->load_products();
                    break;
                    
                default:
                    throw new Exception(__('Invalid action', 'unique-client-page'));
            }
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
                'trace' => WP_DEBUG ? $e->getTraceAsString() : ''
            ]);
        }
        
        wp_die();
    }

    /**
     * Load product details
     */
    private function load_product_detail() {
        $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
        
        if (!$product_id) {
            throw new Exception('Invalid product ID');
        }
        
        $product = wc_get_product($product_id);
        if (!$product) {
            throw new Exception('Product not found');
        }
        
        // Check if this is a modal view request
        $is_modal = isset($_POST['modal_view']) && $_POST['modal_view'] === 'true';
        
        $data = [
            'name' => $product->get_name(),
            'price_html' => $product->get_price_html(),
            'description' => $product->get_description() ?: $product->get_short_description(),
            'permalink' => get_permalink($product_id),
            'sku' => $product->get_sku(),
            'stock_status' => $product->get_stock_status()
        ];
        
        ob_start();
        ?>
        <div class="ucp-product-detail-container">
            <div class="ucp-product-summary">
                <?php if ($data['price_html']) : ?>
                    <div class="ucp-product-price"><?php echo $data['price_html']; ?></div>
                <?php endif; ?>
                
                <!-- Product description -->
                <div class="ucp-product-description">
                    <?php 
                    // Use formatting function to process product description
                    echo ucp_format_product_description($data['description']); 
                    ?>
                </div>
                
                <!-- Product action buttons removed -->
                
                <!-- Product metadata -->
                <div class="ucp-product-meta">
                    <?php
                    // Display SKU
                    if (!empty($data['sku'])) {
                        echo '<div class="ucp-product-sku">';
                        echo '<span class="ucp-meta-label">' . esc_html__('SKU:', 'unique-client-page') . '</span> ';
                        echo '<span class="ucp-meta-value">' . esc_html($data['sku']) . '</span>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        
        $html = ob_get_clean();
        wp_send_json_success(['html' => $html]);
    }

    /**
     * Load products
     */
    private function load_products() {
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $product_ids = [];
        
        if (!empty($_POST['product_ids'])) {
            $raw_ids = sanitize_text_field($_POST['product_ids']);
            $product_ids = array_filter(array_map('absint', explode(',', $raw_ids)));
            
            if (empty($product_ids)) {
                throw new Exception(__('No valid product IDs provided', 'unique-client-page'));
            }
        } else {
            throw new Exception(__('Missing product IDs parameter', 'unique-client-page'));
        }
        
        // Create cache key
        $cache_key = 'ucp_products_' . md5($category . implode(',', $product_ids) . $page);
        $cached_result = get_transient($cache_key);
        
        if (false !== $cached_result) {
            wp_send_json_success($cached_result);
        }
        
        // Build query arguments
        $args = [
            'post_type'      => 'product',
            'post__in'       => $product_ids,
            'posts_per_page' => 12,
            'paged'          => $page,
            'orderby'        => 'menu_order title',
            'order'          => 'ASC',
            'fields'         => 'ids',
            'post_status'    => 'publish'
        ];
        
        // Add category filter if provided
        if (!empty($category)) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $category,
                ],
            ];
        }
        
        $products_query = new WP_Query($args);
        
        if (is_wp_error($products_query)) {
            throw new Exception('Database query failed: ' . $products_query->get_error_message());
        }
        
        $products = [];
        
        foreach ($products_query->posts as $product_id) {
            $product = wc_get_product($product_id);
            if (!$product) continue;
            
            $products[] = [
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'price_html' => $product->get_price_html(),
                'permalink' => get_permalink($product_id),
                'add_to_cart_url' => $product->add_to_cart_url()
            ];
        }
        
        $result = [
            'products' => $products,
            'total' => $products_query->found_posts,
            'page' => $page,
            'per_page' => $args['posts_per_page'],
            'has_more' => $page < $products_query->max_num_pages
        ];
        
        // Cache the results for 1 hour
        set_transient($cache_key, $result, HOUR_IN_SECONDS);
        
        wp_send_json_success($result);
    }

    /**
     * Get or create guest ID for non-logged in users
     * 
     * @return string Guest ID
     */
    private function get_guest_id() {
        $cookie_name = 'ucp_guest_id';
        
        // Check if guest ID exists in cookie
        if (isset($_COOKIE[$cookie_name])) {
            return sanitize_text_field($_COOKIE[$cookie_name]);
        }
        
        // Create new guest ID
        $guest_id = 'guest_' . uniqid();
        
        // Set cookie for 30 days
        setcookie($cookie_name, $guest_id, time() + (86400 * 30), '/');
        
        return $guest_id;
    }
    
    /**
     * Handle wishlist operations
     */
    public function handle_wishlist() {
        // Log request information to file
        $debug_info = array(
            'request' => $_REQUEST,
            'time' => current_time('mysql'),
            'user' => get_current_user_id(),
            'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown'
        );
        
        // Write debug information to file
        $log_file = WP_CONTENT_DIR . '/debug-wishlist.log';
        file_put_contents($log_file, print_r($debug_info, true) . "\n\n", FILE_APPEND);
        
        // Use unified ucp-ajax-nonce for verification
        $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : 'no_nonce';
        
        // Verify the nonce
        $nonce_check = wp_verify_nonce($nonce, 'ucp-ajax-nonce');
        
        // Record verification result
        $nonce_debug = "Nonce received: {$nonce}, Verification result: {$nonce_check}\n";
        file_put_contents($log_file, $nonce_debug, FILE_APPEND);
        
        // Check if nonce is valid
        if (!$nonce_check) {
            wp_send_json_error(array('message' => 'Security check failed', 'nonce_received' => $nonce));
            exit;
        }
        
        // Get parameters
        $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $wishlist_action = isset($_POST['wishlist_action']) ? sanitize_text_field($_POST['wishlist_action']) : '';
        
        if (!$product_id || !$page_id || empty($wishlist_action)) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        // Process wishlist operations for logged-in users
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            // Get page-specific wishlist
            $wishlist_key = '_ucp_wishlist_' . $page_id;
            $wishlist = get_user_meta($user_id, $wishlist_key, true);
            
            if (!is_array($wishlist)) {
                $wishlist = array();
            }
            
            // Process based on the action
            if ($wishlist_action === 'add') {
                if (!in_array($product_id, $wishlist)) {
                    $wishlist[] = $product_id;
                }
            } else if ($wishlist_action === 'remove') {
                $wishlist = array_diff($wishlist, array($product_id));
            }
            
            // Update user meta data
            update_user_meta($user_id, $wishlist_key, $wishlist);
            
            // Check if product is in the wishlist after the operation
            $is_in_wishlist = in_array($product_id, $wishlist);
            
            // Return success response
            wp_send_json_success(array(
                'message' => ($wishlist_action === 'add') ? 'Product added to wishlist' : 'Product removed from wishlist',
                'in_wishlist' => $is_in_wishlist,  // Based on actual state, not operation type
                'count' => count($wishlist),
                'wishlist' => $wishlist,
                'action_taken' => $wishlist_action  // Track what operation was performed
            ));
        } else {
            // For non-logged in users, use admin user's wishlist
            // Get admin user ID (typically 1) or use a fixed default user ID
            $default_user_id = 1; // 使用ID为1的用户（通常是管理员）
            
            // Get page-specific wishlist using the same structure as logged-in users
            $wishlist_key = '_ucp_wishlist_' . $page_id;
            $wishlist = get_user_meta($default_user_id, $wishlist_key, true);
            
            if (!is_array($wishlist)) {
                $wishlist = array();
            }
            
            // Process based on the action (same logic as logged-in users)
            if ($wishlist_action === 'add') {
                if (!in_array($product_id, $wishlist)) {
                    $wishlist[] = $product_id;
                }
            } else if ($wishlist_action === 'remove') {
                $wishlist = array_diff($wishlist, array($product_id));
            }
            
            // Update user meta data - using the same method as logged-in users
            update_user_meta($default_user_id, $wishlist_key, $wishlist);
            
            // Log successful operation
            error_log("Updated shared wishlist for non-logged user, using user ID {$default_user_id}, page {$page_id}, action {$wishlist_action}");
            
            // Check if product is in the wishlist after the operation
            $is_in_wishlist = in_array($product_id, $wishlist);
            
            // Return success response
            wp_send_json_success(array(
                'message' => ($wishlist_action === 'add') ? 'Product added to wishlist' : 'Product removed from wishlist',
                'in_wishlist' => $is_in_wishlist,  // Based on actual state, not operation type
                'count' => count($wishlist),
                'wishlist' => $wishlist,
                'action_taken' => $wishlist_action  // Track what operation was performed
            ));
        }
    }
    
    /**
     * Get wishlist items
     */
    public function get_wishlist() {
        // Enable debugging
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('UCP_AJAX_Handler::get_wishlist called');
        }
        
        try {
            
            // Verify nonce (skip if no nonce provided for testing)
            if (isset($_REQUEST['nonce']) && !empty($_REQUEST['nonce'])) {
                check_ajax_referer('ucp-ajax-nonce', 'nonce');
            } else {
                // Log warning but continue for testing
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('UCP_AJAX_Handler::get_wishlist - No nonce provided, continuing for testing');
                }
            }
            
            // Get current user ID
            $user_id = get_current_user_id();
            
            // Get page ID if provided
            $page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : 0;
            
            // If no page ID provided, try to get from current page
            if ($page_id <= 0) {
                global $post;
                $page_id = $post ? $post->ID : 0;
                // If still no page ID, use default product page
                if ($page_id <= 0) {
                    $page_id = get_option('ucp_default_product_page', 0);
                    if ($page_id <= 0) {
                        wp_send_json_error(array('message' => 'No page ID provided and no default product page set'));
                        return;
                    }
                }
            }
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Getting wishlist for page ID: ' . $page_id);
        }
        
        $wishlist = array();
        $items = array();
        
        // Enhanced user status detection with fallback mechanisms
        $user_id = get_current_user_id();
        $is_logged_in = is_user_logged_in();
        $page_id = intval($_REQUEST['page_id']);
        
        // Additional user status checks for edge cases
        $wp_user = wp_get_current_user();
        $actual_user_id = $wp_user->ID;
        
        // If there's a mismatch, use the actual user ID
        if ($user_id === 0 && $actual_user_id > 0) {
            $user_id = $actual_user_id;
            $is_logged_in = true;
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("UCP: Fixed user status mismatch - using actual user ID: {$actual_user_id}");
            }
        }
        
        // Initialize storage type first
        $storage_type = 'unknown';
        
        // Debug user status detection
        $debug_info = array(
            'user_id' => $user_id,
            'actual_user_id' => $actual_user_id,
            'is_logged_in' => $is_logged_in,
            'wp_get_current_user_id' => get_current_user_id(),
            'wp_user_exists' => $wp_user->exists(),
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'cookies_present' => !empty($_COOKIE),
            'page_id' => $page_id,
            'storage_type' => $storage_type
        );
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('UCP Debug Info: ' . json_encode($debug_info));
        }
        
        // Get wishlist data with improved logic
        $wishlist_key = '_ucp_wishlist_' . $page_id;
        
        if ($is_logged_in && $user_id > 0) {
            // Logged in user - get from user meta
            $wishlist = get_user_meta($user_id, $wishlist_key, true);
            $storage_type = 'user_meta';
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("UCP: Getting wishlist for logged-in user {$user_id}, key: {$wishlist_key}");
                error_log("UCP: User meta wishlist: " . json_encode($wishlist));
            }
            
            // If no user meta found, check if there's guest data to migrate
            if (empty($wishlist) || !is_array($wishlist)) {
                $guest_wishlist_cookie_name = 'ucp_guest_wishlist_' . $page_id;
                $guest_wishlist = isset($_COOKIE[$guest_wishlist_cookie_name]) ? json_decode(stripslashes($_COOKIE[$guest_wishlist_cookie_name]), true) : array();
                
                if (!empty($guest_wishlist) && is_array($guest_wishlist)) {
                    // Migrate guest wishlist to user meta
                    update_user_meta($user_id, $wishlist_key, $guest_wishlist);
                    $wishlist = $guest_wishlist;
                    $storage_type = 'migrated_from_cookie';
                    
                    // Clear the guest cookie
                    setcookie($guest_wishlist_cookie_name, '', time() - 3600, '/');
                    
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log("UCP: Migrated guest wishlist to user {$user_id}: " . json_encode($guest_wishlist));
                    }
                } else {
                    $wishlist = array();
                }
            }
        } else {
            // Guest user - get from cookie
            $guest_wishlist_cookie_name = 'ucp_guest_wishlist_' . $page_id;
            $wishlist = isset($_COOKIE[$guest_wishlist_cookie_name]) ? json_decode(stripslashes($_COOKIE[$guest_wishlist_cookie_name]), true) : array();
            $storage_type = 'cookie';
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("UCP: Getting wishlist for guest, cookie: {$guest_wishlist_cookie_name}");
                error_log("UCP: Guest cookie wishlist: " . json_encode($wishlist));
            }
        }
        
        // If not an array, initialize as empty array
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        // Debug information for wishlist data
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Wishlist data for user ' . $user_id . ', page ' . $page_id . ': ' . print_r($wishlist, true));
        }
        
        // Prepare wishlist items data
        foreach ($wishlist as $product_id) {
                $product_id = intval($product_id);
                $product = get_post($product_id);
                
                if ($product) {
                    // Get product image - 优先使用WooCommerce方法获取产品图片
                    $image_url = '';
                    if (function_exists('wc_get_product')) {
                        $wc_product = wc_get_product($product_id);
                        if ($wc_product) {
                            // 获取产品图片ID
                            $image_id = $wc_product->get_image_id();
                            if ($image_id) {
                                // 获取完整尺寸的图片URL
                                $image_data = wp_get_attachment_image_src($image_id, 'thumbnail');
                                if ($image_data && isset($image_data[0])) {
                                    $image_url = $image_data[0];
                                }
                            }
                        }
                    }
                    
                    // 如果WooCommerce方法未获取到图片，尝试使用WordPress函数
                    if (empty($image_url)) {
                        $image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
                    }
                    
                    // 如果仍未获取到图片，使用WooCommerce默认占位符图片
                    if (empty($image_url) && function_exists('wc_placeholder_img_src')) {
                        $image_url = wc_placeholder_img_src('thumbnail');
                    } else if (empty($image_url)) {
                        // 最后尝试使用插件自带的占位符图片
                        $image_url = plugins_url('/assets/images/placeholder.jpg', dirname(__FILE__, 2));
                    }
                    
                    // Get product price (if using WooCommerce)
                    $price = '';
                    if (function_exists('wc_get_product')) {
                        $wc_product = wc_get_product($product_id);
                        if ($wc_product) {
                            $price = $wc_product->get_price_html();
                        }
                    }
                    
                    // Add product to array
                    $sku = '';
                    if (function_exists('wc_get_product')) {
                        $wc_product = wc_get_product($product_id);
                        if ($wc_product) {
                            $sku = $wc_product->get_sku();
                            if (empty($sku)) {
                                $sku = 'N/A';
                            }
                        }
                    }
                    
                    $items[] = array(
                        'id' => $product_id,
                        'product_id' => $product_id,
                        'page_id' => $page_id,
                        'name' => $product->post_title,
                        'image' => $image_url,
                        'price' => $price,
                        'sku' => $sku
                    );
                }
            }
            
            // Get wishlist status
            $wishlist_sent = false;
            $wishlist_last_sent = '';
            
            if ($user_id > 0) {
                // For logged-in users, get from post meta
                $wishlist_sent = get_post_meta($page_id, '_wishlist_sent_' . $user_id, true) === 'yes';
                $wishlist_last_sent = get_post_meta($page_id, '_wishlist_last_sent_' . $user_id, true);
            } else {
                // For guests, get from options
                $guest_id = $this->get_guest_id();
                $wishlist_sent = get_option('_ucp_wishlist_sent_guest_' . $guest_id . '_' . $page_id) === 'yes';
                $wishlist_last_sent = get_option('_ucp_wishlist_last_sent_guest_' . $guest_id . '_' . $page_id, '');
            }
            
            // Return wishlist data with sent status and debug info
            $response = array(
                'count' => count($wishlist),
                'items' => $items,
                'wishlist_sent' => $wishlist_sent,
                'wishlist_last_sent' => $wishlist_last_sent,
                'storage_type' => $storage_type,
                'debug_info' => $debug_info
            );
            
            // Add guest_id for debugging if user is not logged in
            if ($user_id === 0) {
                $response['guest_id'] = $this->get_guest_id();
            }
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Sending wishlist response with ' . count($items) . ' items');
            }
            
            wp_send_json_success($response);
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::get_wishlist error: ' . $e->getMessage());
                error_log('Stack trace: ' . $e->getTraceAsString());
            }
            wp_send_json_error(array('message' => 'Error retrieving wishlist: ' . $e->getMessage()));
        }
    }

    /**
     * Get wishlist status for a product
     */
    public function get_wishlist_status() {
        try {
            // Enable debugging
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::get_wishlist_status called');
            }
            
            // Verify nonce
            check_ajax_referer('ucp-ajax-nonce', 'nonce');
            
            // Get parameters
            $page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : 0;
            $user_id = get_current_user_id();
            
            if ($user_id <= 0) {
                // For guests
                $guest_id = $this->get_guest_id();
                $wishlist_sent = get_option('_ucp_wishlist_sent_guest_' . $guest_id . '_' . $page_id) === 'yes';
                $wishlist_last_sent = (string) get_option('_ucp_wishlist_last_sent_guest_' . $guest_id . '_' . $page_id, '');
            } else {
                // For logged-in users, get from user meta
                $wishlist_key = '_ucp_wishlist_' . $page_id;
                $wishlist = get_user_meta($user_id, $wishlist_key, true);
                
                if (!is_array($wishlist)) {
                    $wishlist = array();
                }
                
                // Get sent status from post meta
                $wishlist_sent = get_post_meta($page_id, '_wishlist_sent_' . $user_id, true) === 'yes';
                
                // Get last sent time if available
                $wishlist_last_sent = get_post_meta($page_id, '_wishlist_last_sent_' . $user_id, true);
            }
            
            // Prepare response
            $response = array(
                'wishlist_sent' => $wishlist_sent,
                'wishlist_last_sent' => $wishlist_last_sent,
                'count' => count($wishlist),
                'is_guest' => ($user_id <= 0)
            );
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Response: ' . print_r($response, true));
            }
            
            wp_send_json_success($response);
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::get_wishlist_status error: ' . $e->getMessage());
            }
            wp_send_json_error(array('message' => 'Error: ' . $e->getMessage()));
        }
    }

    /**
     * Remove item from wishlist
     */
    public function remove_from_wishlist() {
        try {
            // Verify nonce (skip if no nonce provided for testing)
            if (isset($_REQUEST['nonce']) && !empty($_REQUEST['nonce'])) {
                check_ajax_referer('ucp-ajax-nonce', 'nonce');
            } else {
                // Log warning but continue for testing
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('UCP_AJAX_Handler::remove_from_wishlist - No nonce provided, continuing for testing');
                }
            }
            
            // Get parameters
            $product_id = isset($_REQUEST['product_id']) ? intval($_REQUEST['product_id']) : 0;
            $page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : 0;
            
            if (!$product_id || !$page_id) {
                wp_send_json_error(array('message' => 'Invalid product ID or page ID'));
                return;
            }
            
            // Get current user ID
            $user_id = get_current_user_id();
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::remove_from_wishlist - Attempting to remove:', print_r(array(
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'page_id' => $page_id
                ), true));
            }
            
            // Remove from wishlist using the same method as get_wishlist
            if ($user_id > 0) {
                // For logged-in users - use user meta
                $wishlist_key = '_ucp_wishlist_' . $page_id;
                $wishlist = get_user_meta($user_id, $wishlist_key, true);
                
                if (!is_array($wishlist)) {
                    $wishlist = array();
                }
                
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('Current wishlist before removal: ' . print_r($wishlist, true));
                }
                
                // Find and remove the product
                $found = false;
                $new_wishlist = array();
                
                foreach ($wishlist as $item_product_id) {
                    $item_product_id = intval($item_product_id);
                    
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log('Checking logged-in user item ID: ' . $item_product_id . ' - target ID: ' . $product_id);
                    }
                    
                    if ($item_product_id == $product_id) {
                        $found = true;
                        if (defined('WP_DEBUG') && WP_DEBUG) {
                            error_log('Found matching item to remove: ' . $item_product_id);
                        }
                        // Skip this item (remove it)
                        continue;
                    }
                    $new_wishlist[] = $item_product_id;
                }
                
                if (!$found) {
                    wp_send_json_error(array('message' => 'Item not found in wishlist'));
                    return;
                }
                
                // Update user meta with new wishlist
                update_user_meta($user_id, $wishlist_key, $new_wishlist);
                
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('Updated wishlist after removal: ' . print_r($new_wishlist, true));
                }
                
                wp_send_json_success(array(
                    'message' => 'Item removed from wishlist',
                    'count' => count($new_wishlist),
                    'product_id' => $product_id
                ));
                
            } else {
                // For guests - use cookies
                $cookie_name = 'ucp_guest_wishlist_' . $page_id;
                $wishlist_json = isset($_COOKIE[$cookie_name]) ? stripslashes($_COOKIE[$cookie_name]) : '';
                $wishlist = $wishlist_json ? json_decode($wishlist_json, true) : array();
                
                if (!is_array($wishlist)) {
                    $wishlist = array();
                }
                
                // Find and remove the product
                $found = false;
                $new_wishlist = array();
                
                foreach ($wishlist as $item) {
                    if (isset($item['product_id']) && intval($item['product_id']) == $product_id) {
                        $found = true;
                        // Skip this item (remove it)
                        continue;
                    }
                    $new_wishlist[] = $item;
                }
                
                if (!$found) {
                    wp_send_json_error(array('message' => 'Item not found in wishlist'));
                    return;
                }
                
                // Update cookie with new wishlist
                $new_wishlist_json = json_encode($new_wishlist);
                setcookie($cookie_name, $new_wishlist_json, time() + (86400 * 30), '/'); // 30 days
                
                wp_send_json_success(array(
                    'message' => 'Item removed from wishlist',
                    'count' => count($new_wishlist),
                    'product_id' => $product_id
                ));
            }
            
        } catch (Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::remove_from_wishlist error: ' . $e->getMessage());
            }
            wp_send_json_error(array('message' => 'Error removing item: ' . $e->getMessage()));
        }
    }

    /**
     * Update wishlist - Improved version with batch processing support
     */
    public function update_wishlist() {
        // Log start time for performance tracking
        $start_time = microtime(true);
        
        // Log request information to file
        $debug_info = array(
            'request' => $_REQUEST,
            'time' => current_time('mysql'),
            'user' => get_current_user_id(),
            'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown'
        );
        
        // Write debug information to file
        $log_file = WP_CONTENT_DIR . '/debug-wishlist.log';
        file_put_contents($log_file, print_r($debug_info, true) . "\n\n", FILE_APPEND);
        
        // 验证nonce (skip if no nonce provided for testing)
        if (isset($_REQUEST['nonce']) && !empty($_REQUEST['nonce'])) {
            check_ajax_referer('ucp-ajax-nonce', 'nonce');
        } else {
            // Log warning but continue for testing
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::update_wishlist - No nonce provided, continuing for testing');
            }
        }
        
        // Get parameters (support both single product and batch operations)
        $product_ids = isset($_POST['product_ids']) ? $_POST['product_ids'] : 
                     (isset($_POST['product_id']) ? intval($_POST['product_id']) : 0);
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $wishlist_action = isset($_POST['wishlist_action']) ? sanitize_text_field($_POST['wishlist_action']) : '';
        
        // Convert product_ids to array if needed
        if (!is_array($product_ids)) {
            // Handle JSON string format
            if (is_string($product_ids) && strpos($product_ids, '[') === 0) {
                $product_ids = json_decode($product_ids, true);
            }
            // Handle single product ID
            elseif (is_numeric($product_ids)) {
                $product_ids = array(intval($product_ids));
            }
            // Handle comma-separated string
            elseif (is_string($product_ids) && strpos($product_ids, ',') !== false) {
                $product_ids = array_map('intval', explode(',', $product_ids));
            } else {
                $product_ids = array();
            }
        }
        
        // Ensure all product IDs are integers
        $product_ids = array_map('intval', $product_ids);
        
        // Basic validation
        if (empty($product_ids) || !$page_id || empty($wishlist_action)) {
            wp_send_json_error(array(
                'message' => 'Missing required parameters', 
                'product_ids' => $product_ids,
                'page_id' => $page_id,
                'action' => $wishlist_action
            ));
            return;
        }
        
        // 判断用户状态，并设置合适的存储方式
        $is_logged_in = is_user_logged_in();
        $user_id = $is_logged_in ? get_current_user_id() : 0;
        
        // 非登录用户使用cookie存储
        $wishlist_key = '_ucp_wishlist_' . $page_id;
        $guest_wishlist_cookie_name = 'ucp_guest_wishlist_' . $page_id;
        
        // 记录调试信息
        $debug_info['is_logged_in'] = $is_logged_in;
        $debug_info['user_id'] = $user_id;
        $debug_info['wishlist_key'] = $wishlist_key;
        $debug_info['cookie_name'] = $guest_wishlist_cookie_name;
        
        // Get existing wishlist with locking mechanism
        $wishlist_lock_key = '_ucp_wishlist_lock_' . $page_id . '_' . $user_id;
        $is_locked = get_transient($wishlist_lock_key);
        
        // Enhanced locking mechanism with queue system
        $retry_count = 0;
        $max_retries = 10;
        $base_wait = 50000; // 50ms base wait time
        
        while ($is_locked && $retry_count < $max_retries) {
            // Progressive wait time with jitter to prevent thundering herd
            $jitter = rand(0, 25000); // 0-25ms random jitter
            $wait_time = $base_wait + ($retry_count * 25000) + $jitter;
            usleep($wait_time);
            
            $is_locked = get_transient($wishlist_lock_key);
            $retry_count++;
            
            // Log retry attempts for debugging
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log("UCP: Retry attempt {$retry_count} for user {$user_id}, page {$page_id}, waited {$wait_time}μs");
            }
        }
        
        // If still locked after all retries, implement queue system
        if ($is_locked) {
            // Add to processing queue
            $queue_key = '_ucp_wishlist_queue_' . $page_id . '_' . $user_id;
            $queue_data = get_transient($queue_key) ?: array();
            
            $request_id = uniqid('req_', true);
            $queue_data[] = array(
                'request_id' => $request_id,
                'product_ids' => $product_ids,
                'action' => $wishlist_action,
                'timestamp' => microtime(true)
            );
            
            set_transient($queue_key, $queue_data, 30); // Queue expires in 30 seconds
            
            error_log("UCP: Added request {$request_id} to queue for user {$user_id}, page {$page_id}");
            
            // Return queued response
            wp_send_json_success(array(
                'message' => 'Request queued for processing',
                'queued' => true,
                'request_id' => $request_id,
                'queue_position' => count($queue_data)
            ));
            return;
        }
        
        // Set lock with unique identifier and shorter duration
        $lock_identifier = uniqid('ucp_lock_', true) . '_' . getmypid();
        set_transient($wishlist_lock_key, $lock_identifier, 5); // Reduced to 5 seconds
        
        // 获取当前愿望清单 - 根据用户登录状态选择存储位置
        if ($is_logged_in) {
            // 登录用户使用用户元数据
            $wishlist = get_user_meta($user_id, $wishlist_key, true);
            $debug_info['storage_type'] = 'user_meta';
        } else {
            // 游客用户使用cookie
            $wishlist = isset($_COOKIE[$guest_wishlist_cookie_name]) ? json_decode(stripslashes($_COOKIE[$guest_wishlist_cookie_name]), true) : array();
            $debug_info['storage_type'] = 'cookie';
            $debug_info['cookie_value_raw'] = isset($_COOKIE[$guest_wishlist_cookie_name]) ? $_COOKIE[$guest_wishlist_cookie_name] : 'not_set';
        }
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        $debug_info['original_wishlist'] = $wishlist;
        
        // Track changes for logging
        $old_count = count($wishlist);
        $processed_ids = array();
        
        // Process based on the action
        if ($wishlist_action === 'add') {
            foreach ($product_ids as $product_id) {
                if ($product_id > 0 && !in_array($product_id, $wishlist)) {
                    $wishlist[] = $product_id;
                    $processed_ids[] = $product_id;
                }
            }
        } else if ($wishlist_action === 'remove') {
            $wishlist = array_diff($wishlist, $product_ids);
            $processed_ids = $product_ids;
        }
        
        // Update wishlist data - always get fresh data for concurrent safety
        if ($is_logged_in) {
            // For logged-in users, use atomic update with retry mechanism
            $update_success = false;
            $update_attempts = 0;
            $max_attempts = 5;
            
            while (!$update_success && $update_attempts < $max_attempts) {
                // Always get the most current data before making changes
                $current_wishlist = get_user_meta($user_id, $wishlist_key, true);
                if (!is_array($current_wishlist)) {
                    $current_wishlist = array();
                }
                
                $debug_info['attempt_' . ($update_attempts + 1)] = array(
                    'current_wishlist_before' => $current_wishlist,
                    'product_ids_to_process' => $product_ids,
                    'action' => $wishlist_action
                );
                
                // Apply the operation on the most current data
                $new_wishlist = $current_wishlist;
                if ($wishlist_action === 'add') {
                    foreach ($product_ids as $product_id) {
                        if ($product_id > 0 && !in_array($product_id, $new_wishlist)) {
                            $new_wishlist[] = $product_id;
                            $processed_ids[] = $product_id;
                        }
                    }
                } else if ($wishlist_action === 'remove') {
                    $new_wishlist = array_diff($new_wishlist, $product_ids);
                    $processed_ids = array_merge($processed_ids, $product_ids);
                }
                
                // Clean and reindex
                $new_wishlist = array_values(array_filter(array_map('intval', $new_wishlist)));
                
                $debug_info['attempt_' . ($update_attempts + 1)]['new_wishlist_after'] = $new_wishlist;
                
                // Check if there's actually a change and if the operation should succeed
                $should_update = false;
                $operation_valid = false;
                
                if ($wishlist_action === 'add') {
                    // For ADD: operation is valid if we're actually adding new products
                    $operation_valid = !empty(array_diff($product_ids, $current_wishlist));
                    $should_update = ($new_wishlist !== $current_wishlist);
                } else if ($wishlist_action === 'remove') {
                    // For REMOVE: operation is valid if products exist to remove
                    $operation_valid = !empty(array_intersect($product_ids, $current_wishlist));
                    $should_update = ($new_wishlist !== $current_wishlist);
                }
                
                $debug_info['attempt_' . ($update_attempts + 1)]['operation_valid'] = $operation_valid;
                $debug_info['attempt_' . ($update_attempts + 1)]['should_update'] = $should_update;
                
                if ($should_update) {
                    $update_success = update_user_meta($user_id, $wishlist_key, $new_wishlist);
                    
                    if ($update_success) {
                        $wishlist = $new_wishlist; // Update local copy
                        $debug_info['final_update_success'] = true;
                    } else {
                        // Wait with exponential backoff before retry
                        $wait_time = 25000 * pow(2, $update_attempts); // 25ms, 50ms, 100ms, 200ms, 400ms
                        usleep($wait_time);
                        $debug_info['attempt_' . ($update_attempts + 1)]['retry_wait_ms'] = $wait_time / 1000;
                    }
                } else if ($operation_valid) {
                    // No database change needed but operation was valid
                    $update_success = true;
                    $wishlist = $new_wishlist;
                    $debug_info['no_change_needed_but_valid'] = true;
                } else {
                    // Operation is not valid (e.g., trying to add existing product or remove non-existing product)
                    // Still consider it successful but note the issue
                    $update_success = true;
                    $wishlist = $current_wishlist; // Keep current state
                    $debug_info['operation_redundant'] = true;
                }
                
                $update_attempts++;
            }
            
            $debug_info['update_type'] = 'user_meta';
            $debug_info['update_attempts'] = $update_attempts;
            $debug_info['update_success'] = $update_success;
            $debug_info['user_id'] = $user_id;
            $debug_info['wishlist_meta_key'] = $wishlist_key;
        } else {
            // For guest users, update cookie
            // 在写入Cookie前重新检查是否有其他并发请求已经更新了Cookie
            $latest_cookie = isset($_COOKIE[$guest_wishlist_cookie_name]) ? 
                json_decode(stripslashes($_COOKIE[$guest_wishlist_cookie_name]), true) : array();
                
            // 如果发现Cookie有新数据，则合并因为可能有并行请求
            if (!empty($latest_cookie) && is_array($latest_cookie) && $latest_cookie != $wishlist) {
                $debug_info['concurrent_detected'] = true;
                $debug_info['original_wishlist'] = $wishlist;
                $debug_info['latest_cookie'] = $latest_cookie;
                
                // 合并数据，确保所有更改都得到保留
                if ($wishlist_action === 'add') {
                    // 合并新添加的产品
                    $wishlist = array_unique(array_merge($latest_cookie, $wishlist));
                } else if ($wishlist_action === 'remove') {
                    // 不管最新状态，都删除请求中的产品
                    $wishlist = array_diff($latest_cookie, $product_ids);
                }
                
                // 重新索引和过滤
                $wishlist = array_values(array_filter(array_map('intval', $wishlist)));
                $debug_info['merged_wishlist'] = $wishlist;
            }
            
            // Cookie expires in 30 days
            $expire = time() + (30 * DAY_IN_SECONDS);
            $secure = is_ssl();
            $domain = COOKIE_DOMAIN ? COOKIE_DOMAIN : '';
            
            // Encode wishlist as JSON for cookie storage
            $wishlist_json = json_encode($wishlist);
            setcookie($guest_wishlist_cookie_name, $wishlist_json, $expire, COOKIEPATH, $domain, $secure, true);
            
            // Also set it immediately for the current request
            $_COOKIE[$guest_wishlist_cookie_name] = $wishlist_json;
            
            $debug_info['update_type'] = 'cookie';
            $debug_info['cookie_set'] = true;
            $debug_info['cookie_value'] = $wishlist_json;
            $debug_info['final_wishlist_count'] = count($wishlist);
        }
        
        // Process any queued requests before releasing lock
        $this->process_wishlist_queue($page_id, $user_id);
        
        // Release lock
        delete_transient($wishlist_lock_key);
        
        // Log performance
        $execution_time = microtime(true) - $start_time;
        $performance_log = "Wishlist update completed in {$execution_time} seconds. " .
                          "Action: {$wishlist_action}, Products: " . implode(',', $processed_ids) . 
                          ", Old count: {$old_count}, New count: " . count($wishlist) . "\n";
        file_put_contents($log_file, $performance_log, FILE_APPEND);
        
        // 记录所有调试信息到日志文件
        $debug_info['final_wishlist'] = $wishlist;
        $debug_info['execution_time'] = $execution_time;
        $debug_info['products_processed'] = $processed_ids;
        file_put_contents(WP_CONTENT_DIR . '/debug-wishlist.log', date('Y-m-d H:i:s') . ' - ' . print_r($debug_info, true) . "\n\n", FILE_APPEND);
        
        // 准备响应数据，包含每个产品的愿望清单状态
        $in_wishlist = array();
        foreach ($product_ids as $product_id) {
            $in_wishlist[$product_id] = in_array($product_id, $wishlist);
        }
        
        // 结合所有信息返回前端
        $response = array(
            'message' => ($wishlist_action === 'add') ? 'Products added to wishlist' : 'Products removed from wishlist',
            'in_wishlist' => $in_wishlist,  // 产品ID及其状态映射
            'count' => count($wishlist),
            'wishlist' => $wishlist,
            'action_taken' => $wishlist_action,  // 执行的操作
            'processed_count' => count($processed_ids),
            'execution_time' => $execution_time,
            'storage_type' => $is_logged_in ? 'user_meta' : 'cookie',
            'is_guest' => !$is_logged_in
        );
        
        wp_send_json_success($response);
    }

    /**
     * Sync wishlist when a user logs in - merge guest cookie data into user account
     * 
     * @param string $user_login Username
     * @param WP_User $user User object
     */
    public function sync_wishlist_on_login($user_login, $user) {
        // 获取用户 ID
        $user_id = $user->ID;
        
        if ($user_id <= 0) {
            return;
        }
        
        // 获取所有页面ID (如果有多个客户页面)
        global $wpdb;
        $page_ids = $wpdb->get_col("SELECT DISTINCT meta_key FROM {$wpdb->postmeta} WHERE meta_key LIKE '_ucp_page_for_user_%'");
        
        // 找出从名称中提取页面ID
        $page_ids_extracted = array();
        foreach ($page_ids as $key) {
            if (preg_match('/_ucp_page_for_user_(\\d+)/', $key, $matches)) {
                $page_ids_extracted[] = intval($matches[1]);
            }
        }
        
        // 如果没有找到页面ID，则尝试默认自动发现方式
        if (empty($page_ids_extracted)) {
            // 尝试发现现有的cookie
            foreach ($_COOKIE as $cookie_name => $cookie_value) {
                if (strpos($cookie_name, 'ucp_guest_wishlist_') === 0) {
                    // 提取页面ID
                    $page_id = str_replace('ucp_guest_wishlist_', '', $cookie_name);
                    if (is_numeric($page_id)) {
                        $page_ids_extracted[] = intval($page_id);
                    }
                }
            }
        }
        
        // 为每个页面处理同步
        foreach ($page_ids_extracted as $page_id) {
            // 定义cookie名称和用户元数据键
            $guest_wishlist_cookie_name = 'ucp_guest_wishlist_' . $page_id;
            $wishlist_key = '_ucp_wishlist_' . $page_id;
            
            // 获取cookie数据
            $guest_wishlist = isset($_COOKIE[$guest_wishlist_cookie_name]) ? 
                json_decode(stripslashes($_COOKIE[$guest_wishlist_cookie_name]), true) : array();
            
            // 获取用户数据
            $user_wishlist = get_user_meta($user_id, $wishlist_key, true);
            if (!is_array($user_wishlist)) {
                $user_wishlist = array();
            }
            
            // 如果Cookie中有数据，合并到用户元数据
            if (!empty($guest_wishlist)) {
                // 合并数组，删除重复
                $merged_wishlist = array_unique(array_merge($user_wishlist, $guest_wishlist));
                
                // 确保所有值都是整数
                $merged_wishlist = array_map('intval', $merged_wishlist);
                
                // 删除空值
                $merged_wishlist = array_filter($merged_wishlist);
                
                // 重新索引
                $merged_wishlist = array_values($merged_wishlist);
                
                // 更新用户元数据
                update_user_meta($user_id, $wishlist_key, $merged_wishlist);
                
                // 清除cookie
                setcookie($guest_wishlist_cookie_name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
                
                // 记录到日志
                $log_message = "Wishlist synced on login for user {$user_id} on page {$page_id}. " .
                               "Added " . count(array_diff($merged_wishlist, $user_wishlist)) . " items from cookie.\n";
                file_put_contents(WP_CONTENT_DIR . '/wishlist-sync.log', date('Y-m-d H:i:s') . ' - ' . $log_message, FILE_APPEND);
            }
        }
    }
    
    /**
     * Sync wishlist when a user logs out - copy user data to cookie
     */
    public function sync_wishlist_on_logout() {
        // 确保用户已登录
        if (!is_user_logged_in()) {
            return;
        }
        
        $user_id = get_current_user_id();
        
        // 获取所有用户元数据键值对
        $all_user_meta = get_user_meta($user_id);
        
        // 遍历元数据，查找愿望清单项
        foreach ($all_user_meta as $meta_key => $meta_values) {
            // 检查是否为愿望清单元数据
            if (strpos($meta_key, '_ucp_wishlist_') === 0) {
                // 从键名提取页面ID
                $page_id = str_replace('_ucp_wishlist_', '', $meta_key);
                
                if (is_numeric($page_id)) {
                    $page_id = intval($page_id);
                    $wishlist_data = maybe_unserialize($meta_values[0]);
                    
                    if (is_array($wishlist_data) && !empty($wishlist_data)) {
                        // 设置Cookie，有时30天
                        $expire = time() + (30 * DAY_IN_SECONDS);
                        $secure = is_ssl();
                        $guest_wishlist_cookie_name = 'ucp_guest_wishlist_' . $page_id;
                        
                        // 将愿望清单数据转为JSON存储到Cookie
                        setcookie(
                            $guest_wishlist_cookie_name,
                            json_encode($wishlist_data),
                            $expire,
                            COOKIEPATH,
                            COOKIE_DOMAIN,
                            $secure,
                            true
                        );
                        
                        // 记录到日志
                        $log_message = "Wishlist saved to cookie on logout for user {$user_id} on page {$page_id}. " .
                                       count($wishlist_data) . " items saved.\n";
                        file_put_contents(WP_CONTENT_DIR . '/wishlist-sync.log', date('Y-m-d H:i:s') . ' - ' . $log_message, FILE_APPEND);
                    }
                }
            }
        }
    }

    /**
     * Process queued wishlist requests
     * 
     * @param int $page_id Page ID
     * @param int $user_id User ID
     */
    private function process_wishlist_queue($page_id, $user_id) {
        $queue_key = '_ucp_wishlist_queue_' . $page_id . '_' . $user_id;
        $queue_data = get_transient($queue_key);
        
        if (empty($queue_data) || !is_array($queue_data)) {
            return;
        }
        
        // Sort by timestamp to process in order
        usort($queue_data, function($a, $b) {
            return $a['timestamp'] <=> $b['timestamp'];
        });
        
        $processed_requests = array();
        
        foreach ($queue_data as $request) {
            try {
                // Process each queued request
                $this->process_single_wishlist_request(
                    $request['product_ids'],
                    $request['action'],
                    $page_id,
                    $user_id
                );
                
                $processed_requests[] = $request['request_id'];
                
                error_log("UCP: Processed queued request {$request['request_id']} for user {$user_id}");
                
            } catch (Exception $e) {
                error_log("UCP: Failed to process queued request {$request['request_id']}: " . $e->getMessage());
            }
        }
        
        // Clear the queue after processing
        delete_transient($queue_key);
        
        if (!empty($processed_requests)) {
            error_log("UCP: Processed " . count($processed_requests) . " queued requests for user {$user_id}, page {$page_id}");
        }
    }
    
    /**
     * Process a single wishlist request with atomic updates
     * 
     * @param array $product_ids Product IDs to process
     * @param string $action Action to perform (add/remove)
     * @param int $page_id Page ID
     * @param int $user_id User ID
     */
    private function process_single_wishlist_request($product_ids, $action, $page_id, $user_id) {
        $is_logged_in = is_user_logged_in() && $user_id > 0;
        $wishlist_key = '_ucp_wishlist_' . $page_id;
        $guest_wishlist_cookie_name = 'ucp_guest_wishlist_' . $page_id;
        
        if ($is_logged_in) {
            // For logged-in users, use atomic update with retry mechanism
            $update_success = false;
            $update_attempts = 0;
            $max_attempts = 3;
            
            while (!$update_success && $update_attempts < $max_attempts) {
                // Always get the most current data before making changes
                $current_wishlist = get_user_meta($user_id, $wishlist_key, true);
                if (!is_array($current_wishlist)) {
                    $current_wishlist = array();
                }
                
                // Apply the operation on the most current data
                $new_wishlist = $current_wishlist;
                if ($action === 'add') {
                    foreach ($product_ids as $product_id) {
                        if ($product_id > 0 && !in_array($product_id, $new_wishlist)) {
                            $new_wishlist[] = $product_id;
                        }
                    }
                } else if ($action === 'remove') {
                    $new_wishlist = array_diff($new_wishlist, $product_ids);
                }
                
                // Clean and reindex
                $new_wishlist = array_values(array_filter(array_map('intval', $new_wishlist)));
                
                // Only update if there's actually a change
                if ($new_wishlist !== $current_wishlist) {
                    $update_success = update_user_meta($user_id, $wishlist_key, $new_wishlist);
                    
                    if (!$update_success) {
                        // Wait before retry
                        usleep(25000 * pow(2, $update_attempts)); // 25ms, 50ms, 100ms
                    }
                } else {
                    // No change needed, consider it successful
                    $update_success = true;
                }
                
                $update_attempts++;
            }
            
            error_log("UCP: Queue processing - User {$user_id}, Action: {$action}, Products: " . implode(',', $product_ids) . ", Attempts: {$update_attempts}, Success: " . ($update_success ? 'Yes' : 'No'));
            
        } else {
            // For guest users, use cookie with atomic-like behavior
            $latest_cookie = isset($_COOKIE[$guest_wishlist_cookie_name]) ? 
                json_decode(stripslashes($_COOKIE[$guest_wishlist_cookie_name]), true) : array();
            
            if (!is_array($latest_cookie)) {
                $latest_cookie = array();
            }
            
            // Apply operation on latest cookie data
            if ($action === 'add') {
                foreach ($product_ids as $product_id) {
                    if ($product_id > 0 && !in_array($product_id, $latest_cookie)) {
                        $latest_cookie[] = $product_id;
                    }
                }
            } else if ($action === 'remove') {
                $latest_cookie = array_diff($latest_cookie, $product_ids);
            }
            
            // Clean and reindex
            $latest_cookie = array_values(array_filter(array_map('intval', $latest_cookie)));
            
            // Save updated cookie
            $expire = time() + (30 * DAY_IN_SECONDS);
            $secure = is_ssl();
            $domain = COOKIE_DOMAIN ? COOKIE_DOMAIN : '';
            $wishlist_json = json_encode($latest_cookie);
            setcookie($guest_wishlist_cookie_name, $wishlist_json, $expire, COOKIEPATH, $domain, $secure, true);
            $_COOKIE[$guest_wishlist_cookie_name] = $wishlist_json;
            
            error_log("UCP: Queue processing - Guest user, Action: {$action}, Products: " . implode(',', $product_ids) . ", Final count: " . count($latest_cookie));
        }
    }

    /**
     * Clean up old guest wishlists
     */
    public function cleanup_guest_wishlists() {
        global $wpdb;
        
        // Get all guest wishlist options
        $guest_options = $wpdb->get_results(
            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_ucp_wishlist_guest_%'"
        );
        
        // Define expiration period (e.g., 60 days)
        $expiration = 60 * 86400;
        $current_time = time();
        
        foreach ($guest_options as $option) {
            $option_name = $option->option_name;
            
            // Get last updated time
            $updated_time = get_option("{$option_name}_updated", 0);
            
            // If older than expiration period, delete
            if (($current_time - $updated_time) > $expiration) {
                delete_option($option_name);
                delete_option("{$option_name}_updated");
                error_log("Cleaned up expired wishlist: {$option_name}");
            }
        }
        
        error_log("Wishlist cleanup completed at " . current_time('mysql'));
    }
    
    /**
     * Save a new version of the wishlist
     */
    public function save_wishlist_version() {
        // Verify nonce
        check_ajax_referer('ucp-ajax-nonce', 'nonce');
        
        // Only allow logged-in users to save versions
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to save wishlist versions'));
            return;
        }
        
        // Get parameters
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $version_name = isset($_POST['version_name']) ? sanitize_text_field($_POST['version_name']) : '';
        $version_notes = isset($_POST['version_notes']) ? sanitize_textarea_field($_POST['version_notes']) : '';
        
        if (!$page_id) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        $user_id = get_current_user_id();
        $wishlist_key = '_ucp_wishlist_' . $page_id;
        $wishlist = get_user_meta($user_id, $wishlist_key, true);
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        // Get version manager
        $version_manager = $this->get_version_manager();
        if (!$version_manager) {
            wp_send_json_error(array('message' => 'Version manager not available'));
            return;
        }
        
        // Generate a default version name if not provided
        if (empty($version_name)) {
            $version_name = sprintf(__('Saved on %s', 'unique-client-page'), current_time('mysql'));
        }
        
        // Save the version
        $version_id = $version_manager->save_version(
            $user_id,
            $page_id,
            $wishlist,
            $version_name,
            $version_notes
        );
        
        if ($version_id) {
            wp_send_json_success(array(
                'message' => 'Wishlist version saved successfully',
                'version_id' => $version_id
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to save wishlist version'));
        }
    }
    
    /**
     * Get all versions of a wishlist
     */
    public function get_wishlist_versions() {
        // Verify nonce
        check_ajax_referer('ucp-ajax-nonce', 'nonce');
        
        // Only allow logged-in users to get versions
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to view wishlist versions'));
            return;
        }
        
        // Get parameters
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (!$page_id) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        $user_id = get_current_user_id();
        
        // Get version manager
        $version_manager = $this->get_version_manager();
        if (!$version_manager) {
            wp_send_json_error(array('message' => 'Version manager not available'));
            return;
        }
        
        // Get all versions
        $versions = $version_manager->get_versions($user_id, $page_id);
        
        wp_send_json_success(array(
            'versions' => $versions
        ));
    }
    
    /**
     * Restore a wishlist to a previous version
     */
    public function restore_wishlist_version() {
        // Verify nonce
        check_ajax_referer('ucp-ajax-nonce', 'nonce');
        
        // Only allow logged-in users to restore versions
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to restore wishlist versions'));
            return;
        }
        
        // Get parameters
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (!$version_id || !$page_id) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        $user_id = get_current_user_id();
        
        // Get version manager
        $version_manager = $this->get_version_manager();
        if (!$version_manager) {
            wp_send_json_error(array('message' => 'Version manager not available'));
            return;
        }
        
        // Get the version data
        $version = $version_manager->get_version($version_id);
        
        if (!$version) {
            wp_send_json_error(array('message' => 'Version not found or access denied'));
            return;
        }
        
        // Update the wishlist with the restored data
        $wishlist_key = '_ucp_wishlist_' . $page_id;
        update_user_meta($user_id, $wishlist_key, $version['wishlist_data']);
        
        // Save this as a new version with a note about the restore
        $new_version_id = $version_manager->save_version(
            $user_id,
            $page_id,
            $version['wishlist_data'],
            sprintf(__('Restored version from %s', 'unique-client-page'), $version['version_name']),
            sprintf(__('Restored from version ID: %d, originally created on %s', 'unique-client-page'), 
                  $version_id, $version['created_at'])
        );
        
        if ($new_version_id) {
            wp_send_json_success(array(
                'message' => 'Wishlist restored successfully',
                'wishlist' => $version['wishlist_data'],
                'version_id' => $new_version_id
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to restore wishlist version'));
        }
    }
    
    /**
     * Send wishlist email
     */
    public function send_wishlist_email() {
        // Verify nonce (skip if no nonce provided for testing)
        if (isset($_REQUEST['nonce']) && !empty($_REQUEST['nonce'])) {
            check_ajax_referer('ucp-ajax-nonce', 'nonce');
        } else {
            // Log warning but continue for testing
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP_AJAX_Handler::send_wishlist_email - No nonce provided, continuing for testing');
            }
        }
        
        // Log request information to file
        $debug_info = array(
            'request' => $_REQUEST,
            'time' => current_time('mysql'),
            'user' => get_current_user_id(),
            'referrer' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'unknown'
        );
        
        $log_file = WP_CONTENT_DIR . '/debug-wishlist-email.log';
        file_put_contents($log_file, print_r($debug_info, true) . "\n\n", FILE_APPEND);
        
        error_log('Wishlist email AJAX request received: ' . print_r($_POST, true));
        
        // Get page ID
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        // Log page ID
        error_log("Wishlist Email - Page ID: " . $page_id);
        
        // Process product IDs from different possible formats
        $product_ids = array();
        
        // Case 1: product_ids is already an array
        if (isset($_POST['product_ids']) && is_array($_POST['product_ids'])) {
            foreach ($_POST['product_ids'] as $id) {
                $product_ids[] = intval($id);
            }
            error_log('Product IDs from array: ' . implode(',', $product_ids));
        } 
        // Case 2: product_ids is a JSON string
        else if (isset($_POST['product_ids']) && is_string($_POST['product_ids']) && substr($_POST['product_ids'], 0, 1) === '[') {
            $decoded = json_decode($_POST['product_ids'], true);
            if (is_array($decoded)) {
                foreach ($decoded as $id) {
                    $product_ids[] = intval($id);
                }
                error_log('Product IDs from JSON: ' . implode(',', $product_ids));
            }
        }
        // Case 3: product_ids is a comma-separated string
        else if (isset($_POST['product_ids']) && is_string($_POST['product_ids'])) {
            $ids = explode(',', $_POST['product_ids']);
            foreach ($ids as $id) {
                if (!empty(trim($id))) {
                    $product_ids[] = intval(trim($id));
                }
            }
            error_log('Product IDs from string: ' . implode(',', $product_ids));
        }
        
        if (empty($product_ids)) {
            error_log('No product IDs found in request');
            wp_send_json_error(array('message' => 'No products to send'));
            return;
        }
        
        // Get sales email and name
        $sale_email = get_post_meta($page_id, '_ucp_sale_email', true);
        $sale_name = get_post_meta($page_id, '_ucp_sale_name', true);
        
        // Detailed logging of page ID and sales email information
        error_log('==== WISHLIST EMAIL DIAGNOSTICS ====');
        error_log('Wishlist Email - Page ID: ' . $page_id);
        error_log('Wishlist Email - Raw sale_email from post_meta: "' . ($sale_email ?: 'empty') . '"');
        error_log('Wishlist Email - Raw sale_name from post_meta: "' . ($sale_name ?: 'empty') . '"');
        
        // Check page metadata
        $all_meta = get_post_meta($page_id);
        error_log('Wishlist Email - All meta for page ID ' . $page_id . ': ' . print_r($all_meta, true));
        
        // Try to get all possible email settings
        $admin_email = get_bloginfo('admin_email');
        error_log('Wishlist Email - Admin email from bloginfo: "' . $admin_email . '"');
        
        // Default sale name if not set
        if (empty($sale_name)) {
            $sale_name = 'Sales';
        }
        
        // If sales email not found, try using admin email
        if (empty($sale_email)) {
            $sale_email = get_bloginfo('admin_email');
            error_log('Wishlist Email - Using admin email as fallback: "' . $sale_email . '"');
        }
        
        // Final validation of email address
        if (empty($sale_email) || !is_email($sale_email)) {
            error_log('Wishlist Email - CRITICAL: No valid sale email found. sale_email="' . $sale_email . '"');
            wp_send_json_error(array('message' => 'No valid sales email configured. Please contact administrator.'));
            return;
        }
        
        // Get customer information if provided in the request
        $customer_email = isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '';
        $customer_name = isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '';
        $customer_phone = isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '';
        $customer_message = isset($_POST['customer_message']) ? sanitize_textarea_field($_POST['customer_message']) : '';
        
        error_log('Wishlist Email - Customer form data: email="' . $customer_email . '", name="' . $customer_name . '"');
        
        // We're removing the email validation to allow wishlist sending without customer email
        // Previously this code would block sending if customer_email was empty
        // if (empty($customer_email) || !is_email($customer_email)) {
        //     error_log('Wishlist Email - Invalid email address: "' . $customer_email . '"');
        //     wp_send_json_error(array('message' => 'Please enter a valid email address'));
        //     return;
        // }
        
        // Get product details
        $products = array();
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $products[] = array(
                    'name' => $product->get_name(),
                    'url' => get_permalink($product_id),
                    'price' => $product->get_price_html(),
                    'id' => $product_id
                );
            }
        }
        
        if (empty($products)) {
            error_log('No valid products found for IDs: ' . implode(',', $product_ids));
            wp_send_json_error(array('message' => 'No valid products found'));
            return;
        }
        
        error_log('Wishlist Email - Found ' . count($products) . ' valid products for email');
        
        // Email headers
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $customer_name . ' <' . $customer_email . '>',
            'Reply-To: ' . $customer_name . ' <' . $customer_email . '>'
        );
        
        error_log('Wishlist Email - Headers: ' . print_r($headers, true));
        
        // Email subject
        $subject = sprintf(__('New Wishlist from %s', 'unique-client-page'), $customer_name);
        error_log('Wishlist Email - Subject: "' . $subject . '"');
        
        // Get page information
        $page = get_post($page_id);
        $page_title = $page ? $page->post_title : 'Unknown Page';
        
        // Get current user information
        $current_user = wp_get_current_user();
        $user_name = $current_user->ID ? $current_user->display_name : 'Guest';
        $user_email = $current_user->ID ? $current_user->user_email : 'Unknown Email';
        
        error_log('Wishlist Email - Building HTML email content for ' . count($products) . ' products');
        
        // Prepare email content directly in the function (not using template file)
        $message = "<html><body>";
        $message .= "<h2>Customer Wishlist</h2>";
        $message .= "<p><strong>Client:</strong> {$page_title}</p>";
        $message .= "<p><strong>Sent Time:</strong> " . current_time('mysql') . "</p>";
        $message .= "<h3>Product List:</h3>";
        $message .= "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>";
        $message .= "<tr><th>NO.</th><th>SKU</th><th>Product Name</th></tr>";
        
        foreach ($products as $index => $product_data) {
            $product_id = $product_data['id'];
            $product_name = $product_data['name'];
            
            // Get SKU if possible
            $product_sku = '';
            $product_obj = wc_get_product($product_id);
            if ($product_obj) {
                $product_sku = $product_obj->get_sku();
            }
            
            $message .= "<tr>";
            $message .= "<td>" . ($index + 1) . "</td>";
            $message .= "<td>{$product_sku}</td>";
            $message .= "<td>{$product_name}</td>";
            $message .= "</tr>";
        }
        
        $message .= "</table>";
        $message .= "<p>This email was sent automatically by the system.</p>";
        $message .= "</body></html>";
        
        // Log email content length and recipient before sending
        error_log('Wishlist Email - Sending to: "' . $sale_email . '" with message length: ' . strlen($message) . ' bytes');
        
        // Send email
        $sent = wp_mail($sale_email, $subject, $message, $headers);
        
        // Log detailed result
        error_log('Wishlist Email - Sending result: ' . ($sent ? 'SUCCESS' : 'FAILED'));
        
        // If email failed, try to get more information about the error
        if (!$sent) {
            global $wp_mail_error;
            if (!empty($wp_mail_error)) {
                error_log('Wishlist Email - Error details: ' . print_r($wp_mail_error, true));
            }
            // Check if sale_email is valid
            if (!is_email($sale_email)) {
                error_log('Wishlist Email - CRITICAL: Sale email "' . $sale_email . '" is not a valid email format');
            }
        }
        
        // Return response to client
        if ($sent) {
            // Record wishlist sent status in database
            $user_id = get_current_user_id();
            
            if ($user_id > 0) {
                // For logged-in users, use user-specific meta keys
                update_post_meta($page_id, '_wishlist_sent_' . $user_id, 'yes');
                update_post_meta($page_id, '_wishlist_last_sent_' . $user_id, current_time('mysql'));
                
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('Saved wishlist sent status for user ' . $user_id);
                }
            } else {
                // For guests, use guest-specific option keys
                $guest_id = $this->get_guest_id();
                update_option('_ucp_wishlist_sent_guest_' . $guest_id . '_' . $page_id, 'yes');
                update_option('_ucp_wishlist_last_sent_guest_' . $guest_id . '_' . $page_id, current_time('mysql'));
                
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('Saved wishlist sent status for guest ' . $guest_id);
                }
            }
            
            // Also keep the old way for backward compatibility
            update_post_meta($page_id, '_wishlist_sent', 'yes');
            update_post_meta($page_id, '_wishlist_last_sent', current_time('mysql'));
            
            // Create a new version record in the versions table
            global $wpdb;
            $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
            $current_user_id = get_current_user_id() ?: 1;
            $now = current_time('mysql');
            
            // Get the highest version number for this page
            $latest_version = $wpdb->get_var($wpdb->prepare(
                "SELECT MAX(version_number) FROM $version_table WHERE page_id = %d",
                $page_id
            ));
            
            // Set new version number
            $new_version = (int)$latest_version + 1;
            if($new_version < 1) {
                $new_version = 1; // Start from 1 if no previous versions
            }
            
            // Create version name (e.g., version01, version02)
            $version_name = sprintf('version%02d', $new_version);
            
            // Serialize product IDs for storage
            $products_json = json_encode($product_ids);
            
            // 已移除调试代码
            
            // First try with the actual table structure we observed
            $result = $wpdb->query($wpdb->prepare(
                "INSERT INTO $version_table 
                (page_id, user_id, version_number, version_name, wishlist_data, created_by, created_at, is_current, notes) 
                VALUES (%d, %d, %d, %s, %s, %d, %s, %d, %s)",
                $page_id,
                $current_user_id,
                $new_version,
                $version_name,
                $products_json,
                $current_user_id,
                $now,
                1, // is_current
                'Sent by email on ' . $now // notes
            ));
            
            // 如果第一次尝试失败，使用原始列集作为备选
            if(!$result) {
                // 尝试使用原始列集
                $result = $wpdb->insert(
                    $version_table,
                    array(
                        'page_id' => $page_id,
                        'user_id' => $current_user_id,
                        'version_number' => $new_version,
                        'wishlist_data' => $products_json, // 使用正确的字段名
                        'created_at' => $now
                    ),
                    array('%d', '%d', '%d', '%s', '%s')
                );
            }
            
            wp_send_json_success(array(
                'message' => 'Wishlist sent successfully',
                'wishlist_sent' => true
            ));
        } else {
            error_log('Wishlist Email - FAILED: Could not send email to "' . $sale_email . '"');
            
            // 提供更詳細的錯誤信息
            $error_message = 'Failed to send wishlist email';
            if (empty($sale_email)) {
                $error_message .= ' - No sales email configured';
            } else if (!is_email($sale_email)) {
                $error_message .= ' - Invalid sales email format: ' . $sale_email;
            } else {
                $error_message .= ' - Email sending failed (check server mail configuration)';
            }
            
            wp_send_json_error(array('message' => $error_message));
        }
        
        exit;
    }
    
    // 已移除重复的get_current_wishlist_version函数，保留了第一个定义（第44-88行）
    
    /**
     * 创建新的愿望清单版本
     * 
     * @param int $user_id 用户ID
     * @param int $page_id 页面ID
     * @param array $wishlist_data 愿望清单数据
     * @param string $version_name 可选的版本名称
     * @param string $notes 可选的备注
     * @return int|当创建成功时返回版本ID，失败时返回0
     */
    public function create_wishlist_version($user_id, $page_id, $wishlist_data, $version_name = '', $notes = '') {
        global $wpdb;
        
        // 确保数据是以数组格式存储
        if (!is_array($wishlist_data)) {
            $wishlist_data = array($wishlist_data);
        }
        
        // 获取当前用户已有的版本数量，以确定下一个版本号
        $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
        
        // 首先确保表存在
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$version_table}'")
            === $version_table;
            
        if (!$table_exists) {
            // 当表不存在时创建表
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE $version_table (
                version_id bigint(20) NOT NULL AUTO_INCREMENT,
                page_id bigint(20) NOT NULL,
                user_id bigint(20) NOT NULL,
                version_number int(11) NOT NULL,
                version_name varchar(255),
                wishlist_data longtext,
                created_by bigint(20),
                created_at datetime NOT NULL,
                is_current tinyint(1) DEFAULT 0,
                notes text,
                PRIMARY KEY (version_id),
                KEY page_id (page_id),
                KEY user_id (user_id)
            ) $charset_collate;";
            
            dbDelta($sql);
            
            // 再次检查表是否创建成功
            $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$version_table}'")
                === $version_table;
                
            if (!$table_exists) {
                // 表创建失败
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('Failed to create wishlist versions table');
                }
                return 0;
            }
        }
        
        // 获取当前最大版本号
        $max_version = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(version_number) FROM $version_table WHERE user_id = %d AND page_id = %d",
            $user_id, $page_id
        ));
        
        $next_version = (int)$max_version + 1;
        
        // 首先将所有现有版本设置为非当前版本
        $wpdb->update(
            $version_table,
            array('is_current' => 0),
            array('user_id' => $user_id, 'page_id' => $page_id)
        );
        
        // 插入新版本记录
        $result = $wpdb->insert(
            $version_table,
            array(
                'user_id' => $user_id,
                'page_id' => $page_id,
                'version_number' => $next_version,
                'version_name' => $version_name,
                'wishlist_data' => maybe_serialize($wishlist_data),
                'created_by' => get_current_user_id(),
                'created_at' => current_time('mysql'),
                'is_current' => 1,
                'notes' => $notes
            ),
            array('%d', '%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s')
        );
        
        if (!$result) {
            // 插入失败
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Failed to insert wishlist version: ' . $wpdb->last_error);
            }
            return 0;
        }
        
        return $wpdb->insert_id;
    }

    /**
     * 获取愿望清单版本数据
     * 处理AJAX请求，根据版本ID获取对应的愿望清单数据
     */
    public function get_wishlist_version() {
        // 验证安全nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ucp_get_wishlist_version')) {
            wp_send_json_error(array('message' => '安全验证失败'));
            return;
        }
        
        // 检查必要参数
        if (!isset($_POST['version_id']) || !isset($_POST['page_id'])) {
            wp_send_json_error(array('message' => '缺少必要参数'));
            return;
        }
        
        $version_id = intval($_POST['version_id']);
        $page_id = intval($_POST['page_id']);
        
        // 从数据库获取版本数据
        global $wpdb;
        $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
        
        $version_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $version_table WHERE version_number = %d AND page_id = %d",
            $version_id, $page_id
        ));
        
        if (empty($version_data)) {
            wp_send_json_error(array('message' => '未找到指定版本数据'));
            return;
        }
        
        // 解析愿望清单数据
        $wishlist_data = '';
        if (isset($version_data->wishlist_data)) {
            $wishlist_data = $version_data->wishlist_data;
        } else if (isset($version_data->products)) {
            // 兼容旧版字段名
            $wishlist_data = $version_data->products;
        }
        
        // 解析JSON数据
        $products = json_decode($wishlist_data, true);
        if (empty($products)) {
            $products = array();
        }
        
        // 生成HTML输出
        ob_start();
        
        if (!empty($products)) {
            echo '<div class="version-products-list">';
            echo '<p>' . sprintf(_n('%d product in this version', '%d products in this version', count($products), 'unique-client-page'), count($products)) . '</p>';
            
            foreach ($products as $product_id) {
                $product = wc_get_product($product_id);
                if ($product) {
                    echo '<div class="version-product-item">';
                    echo '<h4>' . $product->get_name() . '</h4>';
                    echo '<div class="product-meta">';
                    echo '<p><strong>SKU:</strong> ' . $product->get_sku() . '</p>';
                    echo '<p><strong>Price:</strong> ' . wc_price($product->get_price()) . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            
            echo '</div>';
        } else {
            echo '<p>此版本没有产品数据</p>';
        }
        
        // 添加版本详细信息
        echo '<div class="version-details">';
        echo '<p><strong>版本名称:</strong> ' . (isset($version_data->version_name) ? $version_data->version_name : 'version' . sprintf('%02d', $version_data->version_number)) . '</p>';
        echo '<p><strong>创建时间:</strong> ' . date_i18n('Y-m-d H:i:s', strtotime($version_data->created_at)) . '</p>';
        if (isset($version_data->notes) && !empty($version_data->notes)) {
            echo '<p><strong>备注:</strong> ' . esc_html($version_data->notes) . '</p>';
        }
        echo '</div>';
        
        $html = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $html,
            'version_number' => $version_data->version_number,
            'version_name' => isset($version_data->version_name) ? $version_data->version_name : null,
            'created_at' => $version_data->created_at
        ));
    }
}

// Initialize the AJAX handler
UCP_AJAX_Handler::get_instance();
