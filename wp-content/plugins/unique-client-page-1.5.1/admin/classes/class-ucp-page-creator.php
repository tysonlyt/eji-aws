<?php
/**
 * Page Creator Class for UCP Plugin
 *
 * Handles all page creation, template management, and related functionality
 *
 * @package Unique_Client_Page
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Page Creator class for UCP plugin
 */
class UCP_Page_Creator extends UCP_Base {
    
    /**
     * Initialize hooks
     */
    public function init() {
        // Add page template filters
        add_filter('theme_page_templates', array($this, 'add_page_template'));
        add_filter('template_include', array($this, 'load_page_template'), 15);
        
        // Register block template
        add_action('init', array($this, 'register_block_template'));
        
        // Register block editor categories
        if (function_exists('register_block_type')) {
            add_filter('block_categories_all', array($this, 'register_block_category'), 10, 2);
        }
        
        // Add shortcode
        add_shortcode('unique_client_products', array($this, 'render_product_shortcode'));
    }
    
    /**
     * Add custom page template
     *
     * @param array $templates Existing templates
     * @return array
     */
    public function add_page_template($templates) {
        // Ensure template key uses full path to solve template recognition issues in new WordPress versions
        $template_path = 'unique-client-template.php';
        $templates[$template_path] = __('Unique Client Product Page', 'unique-client-page');
        return $templates;
    }
    
    /**
     * Load custom page template
     *
     * @param string $template Template path
     * @return string
     */
    public function load_page_template($template) {
        global $post;
        
        // 介入WordPress模板加载过程，加载正确路径的模板文件
        $template_name = 'unique-client-template.php';
        $expected_path = 'C:\xampp\htdocs\eji\wp-content\plugins/templates/unique-client-template.php';
        
        // 记录调试信息
        error_log('UCP Debug - 模板加载请求：' . $template);
        
        // 检查是否正在试图加载我们的模板
        if (strpos($template, $template_name) !== false || 
            strpos($template, $expected_path) !== false) {
            
            // 手动定位到真实的模板文件路径
            $real_template_path = dirname(plugin_dir_path(__FILE__)) . '/templates/unique-client-template.php';
            
            error_log('UCP Debug - 返回绝对路径的模板文件：' . $real_template_path);
            error_log('UCP Debug - 模板文件是否存在：' . (file_exists($real_template_path) ? '是' : '否'));
            
            if (file_exists($real_template_path)) {
                return $real_template_path;
            }
        }
        
        // 如果是通过页面元数据指定的模板
        if (isset($post) && is_object($post) && is_page($post->ID)) {
            $post_template = get_post_meta($post->ID, '_wp_page_template', true);
            error_log('UCP Debug - 页面ID ' . $post->ID . ' 的模板：' . $post_template);
            
            if ($template_name === $post_template) {
                $real_template_path = dirname(plugin_dir_path(__FILE__)) . '/templates/unique-client-template.php';
                
                error_log('UCP Debug - 返回页面指定的模板文件：' . $real_template_path);
                
                if (file_exists($real_template_path)) {
                    return $real_template_path;
                }
            }
        }
        
        // 使用is_page_template检查
        if (is_page_template($template_name)) {
            $real_template_path = dirname(plugin_dir_path(__FILE__)) . '/templates/unique-client-template.php';
            
            error_log('UCP Debug - 使用is_page_template找到模板：' . $real_template_path);
            
            if (file_exists($real_template_path)) {
                return $real_template_path;
            }
        }
        
        return $template;
    }
    
    /**
     * Copy template file to theme directory
     * This is important for themes that look for templates in the theme directory
     */
    public function copy_template_file() {
        // Skip copying template to theme directory
        // We now use only the plugin template to avoid conflicts
        return;
        
        $theme_dir = get_stylesheet_directory();
        $source = $this->get_plugin_file_path('templates/unique-client-template.php');
        $destination = $theme_dir . '/unique-client-template.php';
        
        // Check if file exists, create it if it doesn't
        if (!file_exists($destination)) {
            if (file_exists($source)) {
                copy($source, $destination);
            }
        }
    }
    
    /**
     * Register block template
     * Add template support for Gutenberg editor
     */
    public function register_block_template() {
        // Copy template file to theme directory (if it doesn't exist)
        $this->copy_template_file();
        
        // Support for FSE Themes, add appropriate template registration
        if (function_exists('register_block_pattern')) {
            register_block_pattern(
                'unique-client-page/product-selection-pattern',
                array(
                    'title'       => __('Product Selection Area', 'unique-client-page'),
                    'description' => __('Display product selection interface, supports filtering and loading more products', 'unique-client-page'),
                    'content'     => '<!-- wp:shortcode -->[unique_client_products]<!-- /wp:shortcode -->',
                    'categories'  => array('unique-client-page'),
                )
            );
        }
    }
    
    /**
     * Register block editor template category
     *
     * @param array $categories Existing categories
     * @param WP_Post $post Current post
     * @return array
     */
    public function register_block_category($categories, $post) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'unique-client-page',
                    'title' => __('Unique Client Page', 'unique-client-page'),
                    'icon'  => 'store',
                ),
            )
        );
    }
    
    /**
     * Create a new product page
     *
     * @param array $data Page data
     * @return int|WP_Error Page ID on success, WP_Error on failure
     */
    public function create_product_page($data) {
        // Validate required fields
        if (empty($data['page_title'])) {
            return new WP_Error('missing_title', __('Page title is required', 'unique-client-page'));
        }
        
        // Generate unique slug if not provided
        if (empty($data['page_slug'])) {
            $data['page_slug'] = $this->generate_page_slug($data['page_title']);
        }
        
        // Process product IDs - 从selected_products字段读取（产品选择器使用此字段）
        $product_ids = '';
        
        // 优先从selected_products字段读取（这是产品选择器保存选择的地方）
        if (!empty($data['selected_products'])) {
            $product_ids = $data['selected_products'];
            error_log('UCP Debug - 从selected_products字段读取产品ID: ' . $product_ids);
        }
        // 兼容性：如果没有selected_products但有product_ids字段，则使用它
        else if (isset($data['product_ids']) && !empty($data['product_ids'])) {
            $product_ids = $data['product_ids'];
            error_log('UCP Debug - 从product_ids字段读取产品ID: ' . $product_ids);
        }
        
        // 如果没有指定产品ID，自动获取最新的产品
        if (empty($product_ids)) {
            error_log('UCP Debug - 没有指定产品ID，自动获取最新产品');
            
            // 获取最新的10个产品
            $default_products = get_posts(array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'numberposts' => 10,
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            
            if (!empty($default_products)) {
                // 提取产品ID
                $auto_product_ids = array();
                foreach ($default_products as $product) {
                    $auto_product_ids[] = $product->ID;
                }
                
                if (!empty($auto_product_ids)) {
                    $product_ids = implode(',', $auto_product_ids);
                    error_log('UCP Debug - 自动添加的产品ID: ' . $product_ids);
                }
            }
        }
        
        // Create shortcode
        $shortcode = '[unique_client_products';
        
        // 优先使用产品ID（手动选择的或自动获取的）
        if (!empty($product_ids)) {
            $shortcode .= ' ids="' . esc_attr($product_ids) . '"';
        } 
        // 如果仍然没有产品ID，使用分类或其他设置
        else {
            // If no products selected, use category and other settings
            if (!empty($data['product_category'])) {
                $shortcode .= ' category="' . esc_attr($data['product_category']) . '"';
            }
        }
        
        // Add product limit if specified
        if (!empty($data['product_limit']) && $data['product_limit'] != 12) {
            $shortcode .= ' per_page="' . absint($data['product_limit']) . '"';
        }
        
        // Add product columns if specified
        if (!empty($data['product_columns']) && $data['product_columns'] != 4) {
            $shortcode .= ' columns="' . absint($data['product_columns']) . '"';
        }
        
        $shortcode .= ']';
        
        // Create page content
        $content = '';
        if (!empty($data['page_content'])) {
            $content .= $data['page_content'] . "\n\n";
        }
        $content .= $shortcode;
        
        // Ensure template file is copied to theme directory
        $this->copy_template_file();
        
        // Create page
        $page_args = array(
            'post_title'    => $data['page_title'],
            'post_name'     => $data['page_slug'],
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'meta_input'    => array(
                '_wp_page_template' => 'unique-client-template.php',
                '_ucp_sale_name'    => isset($data['sale_name']) ? $data['sale_name'] : '',
                '_ucp_sale_email'   => isset($data['sale_email']) ? $data['sale_email'] : ''
            )
        );
        
        // If updating an existing page
        if (!empty($data['page_id'])) {
            $page_args['ID'] = $data['page_id'];
            $page_id = wp_update_post($page_args);
        } else {
            // Create new page
            $page_id = wp_insert_post($page_args);
        }
        
        return $page_id;
    }
    
    /**
     * Update an existing product page
     *
     * @param array $data Page data
     * @return int|WP_Error Page ID on success, WP_Error on failure
     */
    public function update_product_page($data) {
        // Validate page ID
        if (empty($data['page_id'])) {
            return new WP_Error('missing_page_id', __('Page ID is required for updates', 'unique-client-page'));
        }
        
        // Check if page exists
        $page = get_post($data['page_id']);
        if (!$page || $page->post_type !== 'page') {
            return new WP_Error('invalid_page', __('Invalid page ID', 'unique-client-page'));
        }
        
        // Forward to create function which handles both create and update
        return $this->create_product_page($data);
    }
    
    /**
     * Delete a product page
     *
     * @param int $page_id Page ID
     * @return bool Whether the page was successfully deleted
     */
    public function delete_product_page($page_id) {
        // Validate page ID
        if (empty($page_id)) {
            return false;
        }
        
        // Check if page exists and uses our template
        $template = get_post_meta($page_id, '_wp_page_template', true);
        if ($template !== 'unique-client-template.php') {
            return false;
        }
        
        // Delete the page
        $result = wp_delete_post($page_id, true);
        
        return ($result !== false);
    }
    
    /**
     * Generate a unique page slug
     *
     * @param string $title Page title
     * @return string Unique slug
     */
    public function generate_page_slug($title) {
        $slug = sanitize_title($title);
        $original_slug = $slug;
        $count = 1;
        
        // Check if slug exists
        while (get_page_by_path($slug, OBJECT, 'page')) {
            $slug = $original_slug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
    
    /**
     * Render product shortcode
     * This is a temporary function that will be moved to a separate renderer class in the future
     *
     * @param array $atts Shortcode attributes
     * @return string Rendered HTML
     */
    /**
     * Renders the product shortcode with enhanced functionality
     * 
     * @param array $atts Shortcode attributes
     * @return string Rendered HTML output
     */
    public function render_product_shortcode($atts) {
        // Add default values including key parameters
        $atts = shortcode_atts(array(
            'ids' => '',           // Product IDs to display
            'category' => '',      // Product category
            'limit' => 12,         // Number of products to show
            'columns' => 4,        // Number of columns in grid
            'per_page' => 12,      // For pagination (template use)
            'orderby' => 'date',   // Order products by parameter
            'order' => 'desc',     // Sort order (asc/desc)
            'featured' => '',      // Show only featured products
            'sale' => '',          // Show only products on sale
        ), $atts, 'unique_client_products');
        
        // Record debug information
        error_log('UCP Debug - Shortcode attributes: ' . print_r($atts, true));
        
        // Get current page ID if on a single page
        $page_id = get_the_ID();
        error_log('UCP Debug - Current page ID: ' . ($page_id ? $page_id : 'Not a singular page'));
        
        // Check for product IDs from page metadata if none provided in shortcode
        $product_ids = array();
        $using_meta_ids = false;
        
        if (empty($atts['ids']) && $page_id) {
            // Try to get product IDs from page metadata
            $meta_ids = get_post_meta($page_id, '_client_products', true);
            if (!empty($meta_ids) && is_array($meta_ids)) {
                $product_ids = array_map('intval', $meta_ids);
                $using_meta_ids = true;
                error_log('UCP Debug - Using product IDs from page metadata: ' . implode(',', $product_ids));
            }
        } elseif (!empty($atts['ids'])) {
            // Convert IDs string to array of integers
            $product_ids = array_map('intval', array_filter(array_map('trim', explode(',', $atts['ids']))));
            error_log('UCP Debug - Using product IDs from shortcode: ' . implode(',', $product_ids));
        }
        
        // Prepare product query arguments
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
        );
        
        // Set order parameters
        $args['orderby'] = $atts['orderby'];
        $args['order'] = $atts['order'];
        
        // Use product IDs if available
        if (!empty($product_ids)) {
            $args['post__in'] = $product_ids;
            if ($atts['orderby'] === 'date') {
                // If using IDs and default ordering, maintain the ID order
                $args['orderby'] = 'post__in';
            }
            
            // When using specific IDs, ignore limit and show all products
            $args['posts_per_page'] = -1;
            
            // Save product IDs to page metadata for template access
            if ($page_id && !$using_meta_ids) {
                update_post_meta($page_id, '_client_products', $product_ids);
                update_post_meta($page_id, '_client_products_per_page', $atts['per_page']);
                update_post_meta($page_id, '_client_products_columns', $atts['columns']);
                error_log('UCP Debug - Updated page #' . $page_id . ' product metadata');
            }
        }
        // If no product IDs specified, use other filters
        else {
            // Limit number of products
            $args['posts_per_page'] = intval($atts['limit']);
            
            // Apply category filter if specified
            if (!empty($atts['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => explode(',', $atts['category']),
                        'operator' => 'IN',
                    )
                );
            }
            
            // Show only featured products if requested
            if (!empty($atts['featured']) && $atts['featured'] === 'yes') {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                    'operator' => 'IN',
                );
            }
            
            // Show only products on sale if requested
            if (!empty($atts['sale']) && $atts['sale'] === 'yes') {
                $args['meta_query'] = array(
                    'relation' => 'OR',
                    array( // Simple products type
                        'key' => '_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'NUMERIC'
                    ),
                    array( // Variable products type
                        'key' => '_min_variation_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'NUMERIC'
                    )
                );
            }
        }
        
        // Get products
        $products = new WP_Query($args);
        error_log('UCP Debug - Product query found ' . $products->post_count . ' products');
        
        // Start output buffer
        ob_start();
        
        // If products found, display them
        if ($products->have_posts()) {
            // Use specified column count
            $columns = intval($atts['columns']);
            echo '<div class="ucp-products-grid" data-columns="' . esc_attr($columns) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                $product_id = get_the_ID();
                
                echo '<div class="ucp-product-item" data-product-id="' . esc_attr($product_id) . '">';
                
                // Product image
                echo '<div class="ucp-product-image">';
                if (has_post_thumbnail()) {
                    the_post_thumbnail('medium');
                } else {
                    echo '<img src="' . wc_placeholder_img_src('medium') . '" alt="Placeholder" />';
                }
                echo '</div>';
                
                // Product title
                echo '<h3 class="ucp-product-title">' . get_the_title() . '</h3>';
                
                // Product price and add to cart button (if WooCommerce product)
                if (function_exists('wc_get_product')) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        // Show if product is on sale
                        if ($product->is_on_sale()) {
                            echo '<span class="ucp-onsale">Sale!</span>';
                        }
                        
                        // Show price
                        echo '<div class="ucp-product-price">' . $product->get_price_html() . '</div>';
                        
                        // Product actions (add to cart, etc.)
                        echo '<div class="ucp-product-actions">';
                        
                        // Add to cart button
                        echo '<a href="?add-to-cart=' . esc_attr($product_id) . '" data-quantity="1" class="ucp-add-to-cart button add_to_cart_button ajax_add_to_cart" data-product_id="' . esc_attr($product_id) . '" aria-label="' . esc_attr__('Add to cart', 'unique-client-page') . '">' . esc_html__('Add to Cart', 'unique-client-page') . '</a>';
                        
                        // View product link
                        echo '<a href="' . get_permalink() . '" class="ucp-view-product">' . esc_html__('View Details', 'unique-client-page') . '</a>';
                        
                        echo '</div>';
                    }
                }
                
                echo '</div>'; // End .ucp-product-item
            }
            
            echo '</div>'; // End .ucp-products-grid
            wp_reset_postdata();
            
        } else {
            // If no products found and no filtering was applied, show latest products instead
            if (empty($product_ids) && empty($atts['category']) && empty($atts['featured']) && empty($atts['sale'])) {
                // Reset query args to get latest products
                $default_args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    'posts_per_page' => intval($atts['limit']),
                    'orderby' => 'date',
                    'order' => 'desc',
                );
                
                $default_products = new WP_Query($default_args);
                error_log('UCP Debug - Showing default products, found: ' . $default_products->post_count);
                
                if ($default_products->have_posts()) {
                    echo '<h3 class="ucp-fallback-title">' . esc_html__('Latest Products', 'unique-client-page') . '</h3>';
                    echo '<div class="ucp-products-grid" data-columns="' . esc_attr($columns) . '">';
                    
                    while ($default_products->have_posts()) {
                        $default_products->the_post();
                        $product_id = get_the_ID();
                        
                        echo '<div class="ucp-product-item" data-product-id="' . esc_attr($product_id) . '">';
                        echo '<div class="ucp-product-image">';
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium');
                        } else {
                            echo '<img src="' . wc_placeholder_img_src('medium') . '" alt="Placeholder" />';
                        }
                        echo '</div>';
                        echo '<h3 class="ucp-product-title">' . get_the_title() . '</h3>';
                        
                        if (function_exists('wc_get_product')) {
                            $product = wc_get_product($product_id);
                            if ($product) {
                                echo '<div class="ucp-product-price">' . $product->get_price_html() . '</div>';
                                echo '<div class="ucp-product-actions">';
                                echo '<a href="?add-to-cart=' . esc_attr($product_id) . '" class="ucp-add-to-cart button add_to_cart_button">' . esc_html__('Add to Cart', 'unique-client-page') . '</a>';
                                echo '<a href="' . get_permalink() . '" class="ucp-view-product">' . esc_html__('View Details', 'unique-client-page') . '</a>';
                                echo '</div>';
                            }
                        }
                        
                        echo '</div>';
                    }
                    
                    echo '</div>';
                    wp_reset_postdata();
                } else {
                    echo '<p class="ucp-no-products">' . esc_html__('No products found. Please add some products to your store.', 'unique-client-page') . '</p>';
                }
            } else {
                echo '<p class="ucp-no-products">' . esc_html__('No products found matching your selection.', 'unique-client-page') . '</p>';
            }
        }
        
        return ob_get_clean();
    }
}
