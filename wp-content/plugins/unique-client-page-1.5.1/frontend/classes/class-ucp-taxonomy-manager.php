<?php
/**
 * Taxonomy Manager Component
 * 
 * Handles all product taxonomy related functionality
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Taxonomy Manager Class
 */
class UCP_Taxonomy_Manager {
    /**
     * Component instance
     *
     * @var UCP_Taxonomy_Manager
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return UCP_Taxonomy_Manager Component instance
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
        // Add any taxonomy-related hooks here
        add_action('init', array($this, 'register_custom_taxonomies'), 10);
    }
    
    /**
     * Register any custom taxonomies
     * This is a placeholder for future custom taxonomy registration
     */
    public function register_custom_taxonomies() {
        // Register any custom taxonomies if needed
        // Currently using WooCommerce's built-in product_cat
    }
    
    /**
     * Get product categories
     *
     * @param array $args Optional. Arguments to modify the query
     * @return array List of product categories
     */
    public function get_product_categories($args = array()) {
        $default_args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true
        );
        
        // Merge default args with provided args
        $query_args = wp_parse_args($args, $default_args);
        
        // Get the terms
        return get_terms($query_args);
    }
    
    /**
     * Get product category by ID
     *
     * @param int $category_id Category ID
     * @return WP_Term|false Term object if exists, false otherwise
     */
    public function get_product_category($category_id) {
        return get_term($category_id, 'product_cat');
    }
    
    /**
     * Check if a product belongs to a specific category
     *
     * @param int $product_id Product ID
     * @param int|string $category Category ID or slug
     * @return bool Whether product belongs to category
     */
    public function product_in_category($product_id, $category) {
        if (is_numeric($category)) {
            // If category is numeric, treat as ID
            return has_term($category, 'product_cat', $product_id);
        } else {
            // If category is string, treat as slug
            return has_term($category, 'product_cat', $product_id);
        }
    }
    
    /**
     * Get all categories for a product
     *
     * @param int $product_id Product ID
     * @return array List of categories
     */
    public function get_product_categories_by_product($product_id) {
        return wp_get_post_terms($product_id, 'product_cat');
    }
}
