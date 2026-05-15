<?php
/**
 * Product Selector Class for UCP Plugin
 *
 * Handles all product selection, modal display, and AJAX operations
 *
 * @package Unique_Client_Page
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Selector class for UCP plugin
 */
class UCP_Product_Selector extends UCP_Base {
    
    /**
     * Initialize hooks
     */
    public function init() {
        // Add AJAX handlers
        add_action('wp_ajax_ucp_load_selector_products', array($this, 'load_selector_products_ajax'));
        add_action('wp_ajax_nopriv_ucp_load_selector_products', array($this, 'load_selector_products_ajax'));
        
        add_action('wp_ajax_ucp_add_products_to_page', array($this, 'add_products_to_page_ajax'));
        add_action('wp_ajax_ucp_get_products_info', array($this, 'get_products_info_ajax'));
        add_action('wp_ajax_ucp_get_products_by_ids', array($this, 'get_products_by_ids_ajax'));
        
        // Add SKU search support
        add_filter('posts_clauses', array($this, 'search_products_by_sku'), 10, 2);
    }
    
    /**
     * Load product selector resources
     * Enqueues all necessary scripts and styles for the product selector
     */
    public function load_selector_resources() {
        // Directly use plugin constant to load resources, avoiding path issues
        if (!defined('UCP_PLUGIN_URL')) {
            // error_log('UCP Error: UCP_PLUGIN_URL not defined');
            return;
        }
        
        // Add debug information
        // error_log('UCP: Loading product selector resources from: ' . UCP_PLUGIN_URL);
        
        // Register CSS
        wp_enqueue_style('dashicons'); // WordPress built-in icons
        
        // Load main stylesheet with all modal styles
        wp_enqueue_style('ucp-styles', UCP_PLUGIN_URL . 'assets/css/ucp-styles.css', array(), $this->version);
        
        // Load core styles from shared directory
        wp_enqueue_style('ucp-variables', UCP_PLUGIN_URL . 'assets/shared/css/_variables.css', array(), $this->version);
        wp_enqueue_style('ucp-base', UCP_PLUGIN_URL . 'assets/shared/css/_base.css', array('ucp-variables'), $this->version);
        
        // Load component styles from module directory (as fallback)
        wp_enqueue_style('ucp-modals', UCP_PLUGIN_URL . 'modules/product-selector/assets/css/_modals.css', array('ucp-variables', 'ucp-styles'), $this->version);
        wp_enqueue_style('ucp-product-selector', UCP_PLUGIN_URL . 'modules/product-selector/assets/css/_product-selector.css', 
            array('ucp-variables', 'ucp-modals', 'ucp-styles'), $this->version);
        
        // Register JavaScript
        wp_enqueue_script('jquery'); // Ensure jQuery is loaded
        
        // Add random number to version to prevent caching issues
        $random_version = $this->version . '.' . time() . '.' . rand(1000, 9999);
        // Load product selector script from module directory
        wp_enqueue_script('ucp-product-selector-script', 
            UCP_PLUGIN_URL . 'modules/product-selector/assets/js/ucp-shared-product-selector.js', 
            array('jquery'), 
            $random_version, 
            true
        );
        
        // 移除调试输出，避免在控制台显示错误
        // error_log('UCP Debug - Loading product selector script from: ' . 
        //     UCP_PLUGIN_URL . 'modules/product-selector/assets/js/ucp-shared-product-selector.js');
        // error_log('UCP Debug - With version: ' . $random_version);
        
        // Only in admin area, load admin-specific modal fix script
        if (is_admin()) {
            // Load the admin-specific modal fix script
            wp_enqueue_script(
                'ucp-admin-modal-fix', 
                UCP_PLUGIN_URL . 'modules/product-selector/assets/js/ucp-shared-modal.js', 
                array('jquery', 'ucp-product-selector-script'), 
                $random_version, 
                true
            );
            
            // 移除调试输出，避免在控制台显示错误
            // error_log('UCP Debug - Loading admin-specific modal fix script for CMS environment');
            
            // 注释掉调试输出代码
            // add_action('admin_footer', function() {
            //     echo "<script>console.log('UCP: Admin modal fix script loaded - ' + (typeof UCPAdminModalFix !== 'undefined' ? 'SUCCESS' : 'FAILED'));</script>";
            // });
        } else {
            // In frontend, we use the regular direct-modal-fix.js if needed
            // Uncomment the following line if you want to use it in frontend too
            // wp_enqueue_script('ucp-direct-modal-fix', UCP_PLUGIN_URL . 'assets/js/direct-modal-fix.js', array('jquery', 'ucp-product-selector-script'), $random_version, true);
        }
        
        // Use wp_localize_script to pass parameters to JavaScript
        wp_localize_script('ucp-product-selector-script', 'ucp_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ucp-selector-nonce'),
            'loading_text' => __('Loading...', 'unique-client-page'),
            'add_products_text' => __('Add Selected Products', 'unique-client-page'),
            'no_products_text' => __('No Products', 'unique-client-page'),
            'error_message' => __('Error loading products', 'unique-client-page'),
            'debug_info' => true,
            'plugin_url' => $this->get_plugin_file_url('')
        ));
        
        // Add inline script to define ucp_params variable directly, ensuring variable availability
        $plugin_url = $this->get_plugin_file_url('');
        add_action('admin_head', function() use ($plugin_url) {
            ?>
            <script type="text/javascript">
            // Define ucp_params variable directly to ensure availability
            if (typeof ucp_params === 'undefined') {
                console.log('UCP: Defining ucp_params directly');
                var ucp_params = {
                    ajax_url: '<?php echo esc_js(admin_url('admin-ajax.php')); ?>',
                    nonce: '<?php echo esc_js(wp_create_nonce('ucp-selector-nonce')); ?>',
                    loading_text: '<?php echo esc_js(__('Loading...', 'unique-client-page')); ?>',
                    add_products_text: '<?php echo esc_js(__('Add Selected Products', 'unique-client-page')); ?>',
                    no_products_text: '<?php echo esc_js(__('No Products', 'unique-client-page')); ?>',
                    error_message: '<?php echo esc_js(__('Error loading products', 'unique-client-page')); ?>',
                    debug_info: true,
                    plugin_url: '<?php echo esc_js($plugin_url); ?>'
                };
            }
            </script>
            <?php
        }, 5);
        
        // Add debug output to page
        add_action('admin_footer', function() {
            echo "<script>console.log('UCP Debug: Resources loading check', {\n";
            echo "  'jQuery version': jQuery.fn.jquery,\n";
            echo "  'ucp_params': typeof ucp_params,\n";
            echo "  'CSS added': Boolean(document.querySelector('link[href*=\"product-selector.css\"]')),\n";
            echo "  'JS added': Boolean(document.querySelector('script[src*=\"product-selector.js\"]')),\n";
            echo "});</script>";
        });
    }
    
    /**
     * AJAX handler for loading products for selector
     * Optimized for better security and performance
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
                // error_log('UCP: Debug mode - nonce validation would occur in production');
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
                    // error_log('UCP: User not logged in, continuing for debugging only');
                }
            }
            
            // Sanitize input parameters
            $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
            $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
            $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
            
            // Get exclude_products parameter (selected products to exclude)
            $exclude_products = isset($_POST['exclude_products']) ? $_POST['exclude_products'] : array();
            if (is_string($exclude_products)) {
                $exclude_products = array_filter(array_map('trim', explode(',', $exclude_products)));
            }
            $exclude_products = array_map('absint', (array) $exclude_products);
            $exclude_products = array_filter($exclude_products); // Remove zeros
            
            // Debug log with minimal information in production
            if (defined('WP_DEBUG') && WP_DEBUG) {
                // error_log('UCP: Query parameters - page: ' . $page . ', category: ' . $category . ', search: ' . $search . ', exclude: ' . count($exclude_products));
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
            
            // Exclude selected products
            if (!empty($exclude_products)) {
                $args['post__not_in'] = $exclude_products;
            }
            
            // Prepare a short transient cache key based on inputs (page/category/search)
            $cache_key = 'ucp_sel_products_' . md5(json_encode([
                'p' => $page,
                'c' => $category,
                's' => $search,
                'ppp' => $args['posts_per_page'],
                'ob' => $args['orderby'],
                'od' => $args['order'],
            ]));
            $cached = get_transient($cache_key);
            if (false !== $cached) {
                // Return cached response immediately
                wp_send_json_success($cached);
            }

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
                        'name' => $product->get_name(),
                        'image' => esc_url($image_url),
                        'price' => $product->get_price_html(),
                        'sku' => $product->get_sku(),
                        'status' => get_post_status($product_id)
                    ];
                }
                
                $response['products'] = $formatted_products;
            }
            
            // Cache the response briefly to reduce DB load for repeated queries
            // TTL: 90 seconds (tweakable 30–120s)
            set_transient($cache_key, $response, 90);

            // Return success response
            wp_send_json_success($response);
            
        } catch (Exception $e) {
            // Log error and send user-friendly message
            // error_log('UCP Error in load_selector_products_ajax: ' . $e->getMessage());
            wp_send_json_error([
                'message' => __('An error occurred while loading products. Please try again.', 'unique-client-page'),
                'code' => 'server_error'
            ]);
        }
    }
    
    /**
     * AJAX handler for adding selected products to page
     * Optimized for better security and error handling
     */
    public function add_products_to_page_ajax() {
        try {
            // Verify nonce for security (always required)
            $valid_nonce = check_ajax_referer('ucp-selector-nonce', 'nonce', false);
            if (!$valid_nonce) {
                wp_send_json_error([
                    'message' => __('Security verification failed. Please refresh the page and try again.', 'unique-client-page'),
                    'code' => 'invalid_nonce'
                ]);
                return;
            }
            
            // Verify user permissions
            if (!current_user_can('edit_pages')) {
                wp_send_json_error([
                    'message' => __('You do not have sufficient permissions to edit this page.', 'unique-client-page'),
                    'code' => 'insufficient_permissions'
                ]);
                return;
            }
            
            // Get and sanitize parameters (support both comma-separated string and array)
            $raw_product_ids = isset($_POST['product_ids']) ? $_POST['product_ids'] : [];
            if (is_string($raw_product_ids)) {
                $raw_product_ids = array_filter(array_map('trim', explode(',', $raw_product_ids)));
            }
            $product_ids = array_map('absint', (array) $raw_product_ids);
            // Remove zeros and duplicates
            $product_ids = array_values(array_unique(array_filter($product_ids)));
            $page_id = isset($_POST['page_id']) ? absint($_POST['page_id']) : 0;
            
            // Validate required parameters
            if (empty($product_ids)) {
                wp_send_json_error([
                    'message' => __('No products were selected. Please select at least one product.', 'unique-client-page'),
                    'code' => 'no_products'
                ]);
                return;
            }
            
            if (empty($page_id)) {
                wp_send_json_error([
                    'message' => __('Invalid page ID. Please try again or contact support.', 'unique-client-page'),
                    'code' => 'invalid_page_id'
                ]);
                return;
            }
            
            // Check if page exists and is of the correct type
            $page = get_post($page_id);
            if (!$page || $page->post_type !== 'page') {
                wp_send_json_error([
                    'message' => __('The specified page does not exist.', 'unique-client-page'),
                    'code' => 'page_not_found'
                ]);
                return;
            }
            
            // Verify product IDs exist and are valid products
            foreach ($product_ids as $key => $product_id) {
                $product = wc_get_product($product_id);
                if (!$product) {
                    // Remove invalid product IDs
                    unset($product_ids[$key]);
                }
            }
            
            // Check if we still have valid products after filtering
            if (empty($product_ids)) {
                wp_send_json_error([
                    'message' => __('No valid products were found. Please select valid products.', 'unique-client-page'),
                    'code' => 'invalid_products'
                ]);
                return;
            }
            
            // Get existing products from post_meta (Single Source of Truth)
            $existing_products = get_post_meta($page_id, '_client_products', true);
            if (!is_array($existing_products)) {
                $existing_products = array();
            }
            
            // Merge with new products and remove duplicates
            $all_product_ids = array_unique(array_merge($existing_products, $product_ids));
            $all_product_ids = array_values($all_product_ids); // Re-index array
            
            // Create new shortcode with properly formatted product IDs (merged list)
            $shortcode = '[unique_client_products ids="' . implode(',', $all_product_ids) . '"]';
            
            // Get current page content
            $content = $page->post_content;
            
            // Check if content already has the shortcode - update existing or append if not present
            if (strpos($content, '[unique_client_products') === false) {
                // Append shortcode if not present
                $content .= "\n\n" . $shortcode;
            } else {
                // Update existing shortcode's ids attribute if present, otherwise inject ids attribute
                $updated = false;
                // Replace ids value if ids attribute exists
                $pattern_with_ids = '/(\[unique_client_products[^\]]*ids=")([^"]*)("[^\]]*\])/i';
                $new_content = preg_replace($pattern_with_ids, '$1' . implode(',', $product_ids) . '$3', $content, 1, $count);
                if ($count > 0) {
                    $content = $new_content;
                    $updated = true;
                }

                if (!$updated) {
                    // No ids attribute found, inject ids just before closing bracket of the first shortcode occurrence
                    $pattern_no_ids = '/\[(unique_client_products)([^\]]*)\]/i';
                    $content = preg_replace_callback($pattern_no_ids, function($matches) use ($product_ids) {
                        $before = $matches[0];
                        // Avoid adding duplicate ids if already present by any chance
                        if (stripos($before, 'ids=') !== false) {
                            return $before;
                        }
                        $attrs = trim($matches[2]);
                        $space = $attrs === '' ? '' : ' ';
                        return '[' . $matches[1] . $space . $attrs . ($space === '' ? '' : ' ') . 'ids="' . implode(',', $product_ids) . '"]';
                    }, $content, 1);
                }
            }
            
            // Update the page with new content
            $update_args = [
                'ID' => $page_id,
                'post_content' => $content
            ];
            
            $result = wp_update_post($update_args);
            
            // Check for errors during page update
            if (is_wp_error($result)) {
                wp_send_json_error([
                    'message' => $result->get_error_message(),
                    'code' => 'update_failed'
                ]);
                return;
            }
            
            // Also sync to post meta for reliable retrieval (use merged list)
            update_post_meta($page_id, '_client_products', $all_product_ids);

            // Success response with appropriate message (return complete list)
            wp_send_json_success([
                'message' => sprintf(__('Successfully saved %d products to the page.', 'unique-client-page'), count($all_product_ids)),
                'count' => count($all_product_ids),
                'product_ids' => $all_product_ids,
                'reload' => false
            ]);
            
        } catch (Exception $e) {
            // Log error and send user-friendly message
            // error_log('UCP Error in add_products_to_page_ajax: ' . $e->getMessage());
            wp_send_json_error([
                'message' => __('An error occurred while adding products to the page. Please try again.', 'unique-client-page'),
                'code' => 'server_error'
            ]);
        }
    }
    
    /**
     * Get product info AJAX handler
     */
    public function get_products_info_ajax() {
        // Validate nonce
        check_ajax_referer('ucp-selector-nonce', 'nonce');
        
        // Get products ID
        $product_ids = isset($_POST['product_ids']) ? array_map('absint', (array) $_POST['product_ids']) : array();
        
        // Validate parameters
        if (empty($product_ids)) {
            wp_send_json_error(array('message' => __('No products selected', 'unique-client-page')));
        }
        
        $products = array();
        
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            
            if ($product) {
                $products[] = array(
                    'id' => $product_id,
                    'title' => $product->get_name(),
                    'price' => $product->get_price_html(),
                    // Use a larger size for modal clarity
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_single'),
                    'sku' => $product->get_sku()
                );
            }
        }
        
        wp_send_json_success(array('products' => $products));
    }
    
    /**
     * Render product selector modal
     * 
     * @param int $page_id Page ID, used for adding products to the page
     */
    public function render_product_selector_modal($page_id = 0) {
        // Get product categories
        $categories = $this->get_product_categories();
        
        ?>
        <div id="ucp-product-selector-modal" class="ucp-modal" style="display: none;">
            <div class="ucp-modal-content">
                <div class="ucp-modal-header">
                    <h3><?php _e('Select Products', 'unique-client-page'); ?></h3>
                    <button class="ucp-modal-close">&times;</button>
                </div>
                
                <!-- Tabs -->
                <div class="ucp-modal-tabs">
                    <button class="ucp-tab-btn active" data-tab="select"><?php _e('Select Products', 'unique-client-page'); ?></button>
                    <button class="ucp-tab-btn" data-tab="selected"><?php _e('Selected Products', 'unique-client-page'); ?> (<span class="ucp-selected-count">0</span>)</button>
                </div>
                
                <div class="ucp-modal-body">
                    <!-- Tab 1: Select Products -->
                    <div class="ucp-tab-content active" data-tab-content="select">
                        <div class="ucp-modal-toolbar">
                            <div class="ucp-search-container">
                                <input type="text" id="ucp-product-search" placeholder="<?php _e('Search products', 'unique-client-page'); ?>">
                                <button id="ucp-search-btn" class="ucp-btn ucp-btn-sm"><?php _e('Search', 'unique-client-page'); ?></button>
                            </div>
                            <div class="ucp-category-filter">
                                <select id="ucp-category-filter">
                                    <option value=""><?php _e('All categories', 'unique-client-page'); ?></option>
                                    <?php 
                                    function display_categories_hierarchically($categories, $level = 0) {
                                        $output = '';
                                        $indent = str_repeat('&nbsp;&nbsp;', $level);
                                        
                                        foreach ($categories as $category) {
                                            $category_class = $level === 0 ? 'ucp-category-level-0' : 'ucp-category-level-' . $level;
                                            
                                            $output .= sprintf(
                                                '<option value="%s" data-level="%d" class="%s">%s%s</option>',
                                                esc_attr($category->slug),
                                                $level,
                                                $category_class,
                                                $indent,
                                                esc_html($category->name)
                                            );
                                            
                                            if (!empty($category->children)) {
                                                $output .= display_categories_hierarchically($category->children, $level + 1);
                                            }
                                        }
                                        
                                        return $output;
                                    }
                                    
                                    echo display_categories_hierarchically($categories);
                                    ?>
                                </select>
                            </div>
                            <div class="ucp-select-all-wrap">
                                <input type="checkbox" id="ucp-select-all">
                                <label for="ucp-select-all" class="ucp-select-all-label"><?php _e('Select All', 'unique-client-page'); ?></label>
                            </div>
                        </div>
                        <div class="ucp-products-container">
                            <div class="ucp-products-grid"></div>
                            <div class="ucp-loader" style="display: none;">
                                <div class="ucp-spinner"></div>
                                <p><?php _e('Loading...', 'unique-client-page'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab 2: Selected Products -->
                    <div class="ucp-tab-content" data-tab-content="selected">
                        <div class="ucp-selected-products-list"></div>
                    </div>
                </div>
                
                <div class="ucp-modal-footer">
                    <div class="ucp-selected-info">
                        <?php _e('Selected', 'unique-client-page'); ?> <span class="ucp-selected-count">0</span> <?php _e('products', 'unique-client-page'); ?>
                    </div>
                    <div class="ucp-modal-actions">
                        <input type="hidden" id="ucp-page-id" value="<?php echo esc_attr($page_id); ?>">
                        <button class="ucp-btn ucp-btn-secondary ucp-cancel-selection"><?php _e('Cancel', 'unique-client-page'); ?></button>
                        <button class="ucp-btn ucp-btn-primary ucp-add-selected-products">
                            <?php _e('Add selected products', 'unique-client-page'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get hierarchical product categories
     * 
     * @return array
     */
    public function get_product_categories() {
        $cache_key = 'ucp_hierarchical_categories';
        $cached = get_transient($cache_key);
        
        if (false !== $cached) {
            return $cached;
        }
        
        // Get all top-level categories
        $categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => 0,
            'hierarchical' => true,
        ));
        
        if (is_wp_error($categories)) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('UCP: Error loading product categories - ' . $categories->get_error_message());
            }
            return array();
        }
        
        // Recursively get child categories
        $hierarchical_categories = array();
        foreach ($categories as $category) {
            $hierarchical_categories[] = $this->get_category_with_children($category);
        }
        
        // Cache for 12 hours
        set_transient($cache_key, $hierarchical_categories, 12 * HOUR_IN_SECONDS);
        
        return $hierarchical_categories;
    }
    
    /**
     * Recursively get category with its children
     */
    private function get_category_with_children($category) {
        $category->children = array();
        
        // Get child categories
        $child_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $category->term_id,
            'hierarchical' => true,
        ));
        
        if (!is_wp_error($child_categories) && !empty($child_categories)) {
            foreach ($child_categories as $child) {
                $category->children[] = $this->get_category_with_children($child);
            }
        }
        
        return $category;
    }
    
    /**
     * Get category and all its children IDs
     */
    private function get_category_children_ids($category_slug) {
        $category = get_term_by('slug', $category_slug, 'product_cat');
        if (!$category) {
            return array();
        }
        
        $category_ids = array($category->term_id);
        $children = get_terms(array(
            'taxonomy' => 'product_cat',
            'child_of' => $category->term_id,
            'fields' => 'ids',
            'hide_empty' => false,
        ));
        
        if (!is_wp_error($children) && !empty($children)) {
            $category_ids = array_merge($category_ids, $children);
        }
        
        return $category_ids;
    }
    
    /**
     * Allow product search by SKU
     */
    public function search_products_by_sku($clauses, $query) {
        global $wpdb;
        
        // Only modify product searches
        if ($query->query_vars['post_type'] !== 'product' || empty($query->query_vars['s'])) {
            return $clauses;
        }
        
        // Get the search term
        $search_term = $query->query_vars['s'];
        
        // Modify WHERE clause to include SKU matches
        $clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} pm_sku ON ({$wpdb->posts}.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku')";
        
        // Add SKU search
        $clauses['where'] = preg_replace(
            "/\(\s*{$wpdb->posts}.post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "({$wpdb->posts}.post_title LIKE $1) OR (pm_sku.meta_value LIKE $1)",
            $clauses['where']
        );
        
        return $clauses;
    }
    
    /**
     * AJAX handler to get product details by IDs
     * Used for displaying selected products tab
     */
    public function get_products_by_ids_ajax() {
        try {
            // Verify nonce
            if (!check_ajax_referer('ucp-selector-nonce', 'nonce', false)) {
                wp_send_json_error([
                    'message' => __('Security check failed', 'unique-client-page')
                ]);
                return;
            }
            
            // Get product IDs
            $product_ids = isset($_POST['product_ids']) ? $_POST['product_ids'] : array();
            
            if (empty($product_ids) || !is_array($product_ids)) {
                wp_send_json_success([]);
                return;
            }
            
            // Sanitize IDs
            $product_ids = array_map('intval', $product_ids);
            $product_ids = array_filter($product_ids, function($id) {
                return $id > 0;
            });
            
            if (empty($product_ids)) {
                wp_send_json_success([]);
                return;
            }
            
            // Query products
            $args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'post__in' => $product_ids,
                'orderby' => 'post__in'
            );
            
            $query = new WP_Query($args);
            $products = array();
            
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $product_id = get_the_ID();
                    $product = wc_get_product($product_id);
                    
                    if (!$product) {
                        continue;
                    }
                    
                    // Get product image
                    $image_id = $product->get_image_id();
                    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : wc_placeholder_img_src();
                    
                    // Get SKU
                    $sku = $product->get_sku();
                    
                    $products[] = array(
                        'id' => $product_id,
                        'name' => get_the_title(),
                        'sku' => $sku ? $sku : '-',
                        'image' => $image_url
                    );
                }
                wp_reset_postdata();
            }
            
            wp_send_json_success($products);
            
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => __('Failed to load product details', 'unique-client-page')
            ]);
        }
    }
}
