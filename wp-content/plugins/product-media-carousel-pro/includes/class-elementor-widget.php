<?php
/**
 * Elementor Widget
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Elementor_Widget {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
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
        // Register widget
        add_action('elementor/widgets/register', array($this, 'register_widget'));
        
        // Register widget category
        add_action('elementor/elements/categories_registered', array($this, 'add_widget_category'));
    }
    
    /**
     * Add widget category
     */
    public function add_widget_category($elements_manager) {
        $elements_manager->add_category(
            'product-media-carousel',
            array(
                'title' => __('Product Media', 'product-media-carousel'),
                'icon' => 'fa fa-plug',
            )
        );
    }
    
    /**
     * Register widget
     */
    public function register_widget($widgets_manager) {
        require_once PMC_PLUGIN_DIR . 'includes/elementor-widgets/product-media-carousel-widget.php';
        $widgets_manager->register(new \PMC_Elementor_Product_Media_Carousel_Widget());
    }
}
