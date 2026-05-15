<?php
/**
 * AJAX Controller Component
 * 
 * Handles all AJAX requests
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Controller Class
 */
class UCP_Ajax_Controller {
    /**
     * Component instance
     *
     * @var UCP_Ajax_Controller
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return UCP_Ajax_Controller Component instance
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
        // Initialization code
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Register AJAX actions for both logged in and non-logged in users
        
        // AJAX hooks for logged in users
        add_action('wp_ajax_ucp_load_products', array($this, 'load_products_ajax'));
        add_action('wp_ajax_ucp_filter_products', array($this, 'filter_products_ajax'));
        add_action('wp_ajax_ucp_load_page', array($this, 'load_page_ajax'));
        add_action('wp_ajax_ucp_load_selector_products', array($this, 'load_selector_products_ajax'));
        
        // AJAX hooks for non-logged in users
        add_action('wp_ajax_nopriv_ucp_load_products', array($this, 'load_products_ajax'));
        add_action('wp_ajax_nopriv_ucp_filter_products', array($this, 'filter_products_ajax'));
        add_action('wp_ajax_nopriv_ucp_load_page', array($this, 'load_page_ajax'));
    }
    
    /**
     * Load products via AJAX
     */
    public function load_products_ajax() {
        // Verify nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Get parameters
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        
        // Prepare query args
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 12,
            'paged' => $page
        );
        
        // Add category filter if specified
        if (!empty($category) && $category !== 'all') {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $category
                )
            );
        }
        
        // Add search filter if specified
        if (!empty($search)) {
            $args['s'] = $search;
        }
        
        // Get product display component
        $product_display = UCP_Product_Display::get_instance();
        
        // Execute query
        $query = $product_display->get_products($args);
        
        // Prepare response
        $response = array(
            'success' => true,
            'products' => array(),
            'max_pages' => $query->max_num_pages,
            'count' => $query->found_posts
        );
        
        // Process products
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;
                
                if (!$product) {
                    continue;
                }
                
                // Get product data
                $response['products'][] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'price' => $product->get_price_html(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'in_wishlist' => $product_display->is_product_in_wishlist(get_the_ID())
                );
            }
            wp_reset_postdata();
        }
        
        // Send response
        wp_send_json($response);
    }
    
    /**
     * Filter products via AJAX
     */
    public function filter_products_ajax() {
        // This functionality is similar to load_products_ajax
        // but might include additional filtering logic
        $this->load_products_ajax();
    }
    
    /**
     * Load page via AJAX
     */
    public function load_page_ajax() {
        // Verify nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Get page ID
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (!$page_id) {
            wp_send_json_error(array(
                'message' => __('Invalid page ID', 'unique-client-page')
            ));
        }
        
        // Get page content
        $page = get_post($page_id);
        
        if (!$page) {
            wp_send_json_error(array(
                'message' => __('Page not found', 'unique-client-page')
            ));
        }
        
        // Prepare response
        $response = array(
            'success' => true,
            'title' => get_the_title($page_id),
            'content' => apply_filters('the_content', $page->post_content)
        );
        
        // Send response
        wp_send_json($response);
    }
    
    /**
     * Load selector products via AJAX
     */
    public function load_selector_products_ajax() {
        // Verify nonce
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'ucp_ajax_nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Get parameters
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $selected = isset($_POST['selected']) ? array_map('intval', $_POST['selected']) : array();
        
        // Prepare query args
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 20,
            'paged' => 1
        );
        
        // Add search filter if specified
        if (!empty($search)) {
            $args['s'] = $search;
        }
        
        // Exclude already selected products
        if (!empty($selected)) {
            $args['post__not_in'] = $selected;
        }
        
        // Execute query
        $query = new WP_Query($args);
        
        // Prepare response
        $response = array(
            'success' => true,
            'products' => array()
        );
        
        // Process products
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $product;
                
                if (!$product) {
                    continue;
                }
                
                // Get product data
                $response['products'][] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'price' => $product->get_price_html(),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
                );
            }
            wp_reset_postdata();
        }
        
        // Send response
        wp_send_json($response);
    }
}
