<?php
/**
 * Product Manager Component
 *
 * Handles product selection, searching, and management for client pages
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Product Manager Component
 */
class UCP_Product_Manager {
    /**
     * Class instance
     *
     * @var UCP_Product_Manager
     */
    private static $instance = null;
    
    /**
     * Debug manager reference
     * 
     * @var UCP_Debug_Manager
     */
    private $debug_manager = null;
    
    /**
     * Get the singleton instance
     *
     * @return UCP_Product_Manager instance
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
        // Get reference to debug manager
        if (class_exists('UCP_Debug_Manager')) {
            $this->debug_manager = UCP_Debug_Manager::get_instance();
        }
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Register AJAX handlers
        add_action('wp_ajax_ucp_load_selector_products', array($this, 'load_selector_products_ajax'));
        add_action('wp_ajax_ucp_add_products_to_page', array($this, 'add_products_to_page_ajax'));
        add_action('wp_ajax_ucp_get_products_info', array($this, 'get_products_info_ajax'));
        
        // Log hook registration
        if ($this->debug_manager) {
            $this->debug_manager->log('Product manager hooks registered', 'debug', 'product_manager');
        }
    }
    
    /**
     * Get product categories
     *
     * @return array Array of category objects
     */
    public function get_product_categories() {
        // Verify WooCommerce is active
        if (!class_exists('WooCommerce')) {
            if ($this->debug_manager) {
                $this->debug_manager->log('WooCommerce not active, cannot get product categories', 'error', 'product_manager');
            }
            return array();
        }
        
        // Get all product categories
        $args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false
        );
        
        $categories = get_terms($args);
        
        // Handle potential errors
        if (is_wp_error($categories)) {
            if ($this->debug_manager) {
                $this->debug_manager->log('Error getting product categories: ' . $categories->get_error_message(), 'error', 'product_manager');
            }
            return array();
        }
        
        return $categories;
    }
    
    /**
     * Render product selector modal
     *
     * @param int $page_id Page ID
     */
    public function render_product_selector_modal($page_id = 0) {
        // Get product categories
        $categories = $this->get_product_categories();
        
        ?>
        <div id="ucp-product-selector-modal" class="ucp-modal">
            <div class="ucp-modal-overlay"></div>
            <div class="ucp-modal-container">
                <!-- Modal window title area, including search, categories, and select all functionality -->
                <div class="ucp-modal-header">
                    <div class="ucp-header-left">
                        <h2 class="ucp-modal-title"><?php _e('Select Products', 'unique-client-page'); ?></h2>
                    </div>
                    <div class="ucp-header-filters">
                        <!-- Search box -->
                        <div class="ucp-search-wrap">
                            <span class="ucp-search-icon dashicons dashicons-search"></span>
                            <input type="text" id="ucp-product-search" class="ucp-search-input" placeholder="<?php _e('Search products (name or SKU)', 'unique-client-page'); ?>">
                        </div>
                        
                        <!-- Category selection -->
                        <div class="ucp-category-filter">
                            <select id="ucp-modal-category" class="ucp-select">
                                <option value=""><?php _e('All Categories', 'unique-client-page'); ?></option>
                                <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Select all functionality -->
                        <div class="ucp-select-all-wrap">
                            <input type="checkbox" id="ucp-select-all">
                            <label for="ucp-select-all" class="ucp-select-all-label"><?php _e('Select All', 'unique-client-page'); ?></label>
                        </div>
                    </div>
                </div>
                
                <!-- Content wrapper for proper scrolling and layout -->
                <div class="ucp-modal-content-wrapper">
                    <!-- Product list area, separate container -->
                    <div class="ucp-modal-content">
                        <!-- 添加隐藏的当前页码字段，用于自动懒加载 -->
                        <input type="hidden" id="ucp-current-page" value="1">
                        <div class="ucp-product-list">
                            <!-- Products will be loaded via AJAX -->
                        </div>
                    </div>
                </div>
                
                <!-- Bottom action area -->
                <div class="ucp-modal-footer">
                    <div class="ucp-selection-info">
                        <?php _e('Selected', 'unique-client-page'); ?> <span class="ucp-selected-count">0</span> <?php _e('products', 'unique-client-page'); ?>
                    </div>
                    
                    <!-- Set page ID to modal, to match with JS code -->
                    <input type="hidden" id="ucp-page-id" value="<?php echo esc_attr($page_id); ?>">
                    <button type="button" class="ucp-modal-close ucp-close-btn" aria-label="<?php _e('Close', 'unique-client-page'); ?>"><?php _e('Close', 'unique-client-page'); ?></button>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Search products by SKU
     *
     * Extends WP_Query to search products by SKU
     *
     * @param array $clauses SQL query clauses
     * @param WP_Query $query Query object
     * @return array Modified query clauses
     */
    public function search_products_by_sku($clauses, $query) {
        global $wpdb;
        
        // Only modify product searches
        if (!$query->is_search() || $query->get('post_type') !== 'product') {
            return $clauses;
        }
        
        // Get search term
        $search_term = $query->get('s');
        
        if (empty($search_term)) {
            return $clauses;
        }
        
        // Remove existing search clause
        $clauses['where'] = preg_replace(
            "/\(\s*{$wpdb->posts}.post_title\s+LIKE.+?\)\)/", 
            ")", 
            $clauses['where']
        );
        
        // Build new search including SKU
        $like = '%' . $wpdb->esc_like($search_term) . '%';
        $clauses['where'] .= $wpdb->prepare(
            " AND ({$wpdb->posts}.post_title LIKE %s
            OR {$wpdb->posts}.post_content LIKE %s
            OR EXISTS (SELECT 1 FROM {$wpdb->postmeta} WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID AND {$wpdb->postmeta}.meta_key = '_sku' AND {$wpdb->postmeta}.meta_value LIKE %s))",
            $like,
            $like,
            $like
        );
        
        return $clauses;
    }
    
    /**
     * AJAX load products for selector
     */
    public function load_selector_products_ajax() {
        try {
            // Verify nonce for security (always required in production)
            if (!defined('WP_DEBUG') || !WP_DEBUG) {
                $valid_nonce = check_ajax_referer('ucp-selector-nonce', 'nonce', false);
                if (!$valid_nonce) {
                    wp_send_json_error([
                        'message' => __('Security verification failed. Please refresh the page and try again.', 'unique-client-page'),
                        'code' => 'invalid_nonce'
                    ]);
                    return;
                }
            } else {
                // Only in debug mode, log but continue
                if ($this->debug_manager) {
                    $this->debug_manager->log('Debug mode - nonce validation would occur in production', 'debug', 'product_manager');
                }
            }
            
            // Check user permissions
            if (!is_user_logged_in()) {
                // In production, this should be enforced
                if (!defined('WP_DEBUG') || !WP_DEBUG) {
                    wp_send_json_error([
                        'message' => __('Please log in to access this feature.', 'unique-client-page'),
                        'code' => 'login_required'
                    ]);
                    return;
                } else {
                    if ($this->debug_manager) {
                        $this->debug_manager->log('User not logged in, continuing for debugging only', 'debug', 'product_manager');
                    }
                }
            }
            
            // Sanitize input parameters
            $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
            $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
            $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
            
            // Debug log with minimal information in production
            if ($this->debug_manager) {
                $this->debug_manager->log('Query parameters - page: ' . $page . ', category: ' . $category . ', search: ' . $search, 'debug', 'product_manager');
            }
            
            // Validate WooCommerce is active and product post type exists
            if (!class_exists('WooCommerce') || !post_type_exists('product')) {
                wp_send_json_error([
                    'message' => __('WooCommerce is not active or properly installed.', 'unique-client-page'),
                    'code' => 'woocommerce_missing'
                ]);
                return;
            }
            
            // Build query arguments with proper sanitization
            $args = [
                'post_type' => 'product',
                'posts_per_page' => 15, // 15 products per page for automatic loading functionality
                'paged' => $page,
                'post_status' => 'publish', // Only published products
                'orderby' => 'date',
                'order' => 'DESC'
            ];
            
            // Add search parameter if provided
            if (!empty($search)) {
                $args['s'] = $search;
                // Add SKU search support
                add_filter('posts_clauses', [$this, 'search_products_by_sku'], 10, 2);
            }
            
            // Add category filter if provided
            if (!empty($category)) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $category
                    ]
                ];
            }
            
            // Execute query with proper error handling
            $products = new WP_Query($args);
            
            // Remove SKU search filter if it was added
            if (!empty($search)) {
                remove_filter('posts_clauses', [$this, 'search_products_by_sku'], 10);
            }
            
            // Prepare response data structure
            $response = [
                'products' => [],
                'total_pages' => $products->max_num_pages,
                'current_page' => $page,
                'total_products' => $products->found_posts
            ];
            
            // Process products if any found
            if ($products->have_posts()) {
                $formatted_products = [];
                
                while ($products->have_posts()) {
                    $products->the_post();
                    $product_id = get_the_ID();
                    $product = wc_get_product($product_id);
                    
                    // Skip invalid products
                    if (!$product || !is_a($product, 'WC_Product')) {
                        continue;
                    }
                    
                    // Get product image
                    $image_url = get_the_post_thumbnail_url($product_id, 'woocommerce_thumbnail');
                    if (!$image_url) {
                        $image_url = wc_placeholder_img_src();
                    }
                    
                    // Format product data with proper escaping
                    $formatted_products[] = [
                        'id' => $product_id,
                        'title' => html_entity_decode(get_the_title()),
                        'price' => $product->get_price_html(),
                        'image' => $image_url,
                        'sku' => $product->get_sku(),
                        'categories' => wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']),
                        'stock' => $product->is_in_stock() ? __('In Stock', 'unique-client-page') : __('Out of Stock', 'unique-client-page')
                    ];
                }
                
                $response['products'] = $formatted_products;
            }
            
            wp_reset_postdata();
            wp_send_json_success($response);
            
        } catch (Exception $e) {
            // Log the error
            if ($this->debug_manager) {
                $this->debug_manager->log('Error in load_selector_products_ajax: ' . $e->getMessage(), 'error', 'product_manager');
            }
            
            // Send error response
            wp_send_json_error([
                'message' => __('An unexpected error occurred. Please try again.', 'unique-client-page'),
                'code' => 'unexpected_error'
            ]);
        }
    }
    
    /**
     * Add products to page via AJAX
     */
    public function add_products_to_page_ajax() {
        try {
            // Verify nonce for security
            if (!defined('WP_DEBUG') || !WP_DEBUG) {
                $valid_nonce = check_ajax_referer('ucp-selector-nonce', 'nonce', false);
                if (!$valid_nonce) {
                    wp_send_json_error([
                        'message' => __('安全验证失败。请刷新页面后重试。', 'unique-client-page'),
                        'code' => 'invalid_nonce'
                    ]);
                    return;
                }
            } else {
                // Only in debug mode, log but continue
                if ($this->debug_manager) {
                    $this->debug_manager->log('Debug mode - nonce validation would occur in production', 'debug', 'product_manager');
                }
            }
            
            // Check user permissions
            if (!current_user_can('edit_posts')) {
                wp_send_json_error([
                    'message' => __('您没有足够的权限执行此操作。', 'unique-client-page'),
                    'code' => 'insufficient_permissions'
                ]);
                return;
            }
            
            // Sanitize input parameters
            $page_id = isset($_POST['page_id']) ? absint($_POST['page_id']) : 0;
            $product_ids = isset($_POST['product_ids']) ? array_map('absint', $_POST['product_ids']) : [];
            
            // Validate parameters
            if (!$page_id) {
                wp_send_json_error([
                    'message' => __('缺少页面ID参数。', 'unique-client-page'),
                    'code' => 'missing_page_id'
                ]);
                return;
            }
            
            if (empty($product_ids)) {
                wp_send_json_error([
                    'message' => __('请选择至少一个产品。', 'unique-client-page'),
                    'code' => 'no_products_selected'
                ]);
                return;
            }
            
            // Get existing products for this page
            $existing_product_ids = get_post_meta($page_id, '_ucp_product_ids', true);
            if (!$existing_product_ids) {
                $existing_product_ids = [];
            } elseif (!is_array($existing_product_ids)) {
                $existing_product_ids = array_map('trim', explode(',', $existing_product_ids));
            }
            
            // Convert to integers
            $existing_product_ids = array_map('intval', $existing_product_ids);
            
            // Merge with new product IDs, avoiding duplicates
            $all_product_ids = array_unique(array_merge($existing_product_ids, $product_ids));
            
            // Update the page meta
            $result = update_post_meta($page_id, '_ucp_product_ids', $all_product_ids);
            
            if ($result === false) {
                wp_send_json_error([
                    'message' => __('更新页面产品列表失败。', 'unique-client-page'),
                    'code' => 'update_failed'
                ]);
                return;
            }
            
            // Get product information for added products
            $added_products = [];
            foreach ($product_ids as $product_id) {
                $product = wc_get_product($product_id);
                if ($product) {
                    $added_products[] = [
                        'id' => $product_id,
                        'title' => $product->get_name(),
                        'sku' => $product->get_sku()
                    ];
                }
            }
            
            // Log success
            if ($this->debug_manager) {
                $this->debug_manager->log('Added ' . count($product_ids) . ' products to page ID: ' . $page_id, 'info', 'product_manager');
            }
            
            wp_send_json_success([
                'products' => $added_products,
                'message' => sprintf(
                    __('成功添加 %d 个产品到页面。', 'unique-client-page'),
                    count($product_ids)
                )
            ]);
            
        } catch (Exception $e) {
            // Log the error
            if ($this->debug_manager) {
                $this->debug_manager->log('Error in add_products_to_page_ajax: ' . $e->getMessage(), 'error', 'product_manager');
            }
            
            // Send error response
            wp_send_json_error([
                'message' => __('处理请求时发生错误。请重试。', 'unique-client-page'),
                'code' => 'unexpected_error'
            ]);
        }
    }
    
    /**
     * Get products info via AJAX
     */
    public function get_products_info_ajax() {
        try {
            // Verify nonce for security
            $valid_nonce = check_ajax_referer('ucp-selector-nonce', 'nonce', false);
            if (!$valid_nonce && (!defined('WP_DEBUG') || !WP_DEBUG)) {
                wp_send_json_error([
                    'message' => __('安全验证失败。请刷新页面后重试。', 'unique-client-page'),
                    'code' => 'invalid_nonce'
                ]);
                return;
            }
            
            // Sanitize input parameters
            $product_ids = isset($_POST['product_ids']) ? array_map('absint', $_POST['product_ids']) : [];
            
            // Get product information
            $products_info = [];
            foreach ($product_ids as $id) {
                $product = wc_get_product($id);
                if ($product) {
                    $image_url = get_the_post_thumbnail_url($id, 'thumbnail');
                    if (!$image_url) {
                        $image_url = wc_placeholder_img_src('thumbnail');
                    }
                    
                    $products_info[] = [
                        'id' => $id,
                        'title' => $product->get_name(),
                        'sku' => $product->get_sku(),
                        'price' => $product->get_price_html(),
                        'image' => $image_url,
                        'link' => get_permalink($id)
                    ];
                }
            }
            
            wp_send_json_success(['products' => $products_info]);
            
        } catch (Exception $e) {
            // Log the error
            if ($this->debug_manager) {
                $this->debug_manager->log('Error in get_products_info_ajax: ' . $e->getMessage(), 'error', 'product_manager');
            }
            
            // Send error response
            wp_send_json_error([
                'message' => __('处理产品信息请求时发生错误。', 'unique-client-page'),
                'code' => 'unexpected_error'
            ]);
        }
    }
}
