<?php
/**
 * Plugin Name: Product Media Carousel Pro
 * Plugin URI: https://everyideas.com/product-media-carousel
 * Description: Transform your WooCommerce product galleries with stunning carousels featuring images, YouTube videos, Vimeo, and self-hosted videos. Includes powerful bulk video import! Fully integrated with Elementor.
 * Version: 1.2.0
 * Author: Frankie@EveryIdeas
 * Author URI: https://everyideas.com
 * Text Domain: product-media-carousel
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.5
 * Elementor tested up to: 3.18
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('PMC_VERSION', '1.2.0');
define('PMC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PMC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PMC_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
class Product_Media_Carousel {
    
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
        // Check compatibility first
        add_action('admin_init', array($this, 'check_compatibility'));
        
        // Include required files
        $this->includes();
        
        // Initialize hooks
        $this->init_hooks();
    }
    
    /**
     * Check compatibility and display warnings
     */
    public function check_compatibility() {
        $errors = PMC_Compatibility::check_requirements();
        
        if (!empty($errors)) {
            add_action('admin_notices', function() use ($errors) {
                PMC_Compatibility::display_errors($errors);
            });
            
            // Deactivate plugin if critical errors
            if (!PMC_Compatibility::check_php_version() || !PMC_Compatibility::check_wp_version()) {
                deactivate_plugins(PMC_PLUGIN_BASENAME);
            }
        }
    }
    
    /**
     * Check if WooCommerce is active
     */
    private function is_woocommerce_active() {
        // Check if WooCommerce class exists
        if (class_exists('WooCommerce')) {
            error_log('PMC: WooCommerce class exists');
            return true;
        }
        
        // Check if WooCommerce is in active plugins
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));
        $is_active = in_array('woocommerce/woocommerce.php', $active_plugins);
        
        error_log('PMC: WooCommerce in active plugins: ' . ($is_active ? 'yes' : 'no'));
        
        return $is_active || class_exists('WooCommerce');
    }
    
    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('Product Media Carousel requires WooCommerce to be installed and activated.', 'product-media-carousel'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Load classes
        require_once PMC_PLUGIN_DIR . 'includes/class-database.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-video-handler.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-admin.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-frontend.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-restrictions.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-compatibility.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-bulk-import.php';
        require_once PMC_PLUGIN_DIR . 'includes/class-help-page.php';
        
        // Load Elementor widget - check if already loaded or wait for it
        if (did_action('elementor/loaded')) {
            // Elementor already loaded, load widget immediately
            $this->load_elementor_widget();
        } else {
            // Wait for Elementor to load
            add_action('elementor/loaded', array($this, 'load_elementor_widget'));
        }
    }
    
    /**
     * Load Elementor widget
     */
    public function load_elementor_widget() {
        // Include Elementor widget class
        require_once PMC_PLUGIN_DIR . 'includes/class-elementor-widget.php';
        
        // Initialize Elementor widget
        PMC_Elementor_Widget::get_instance();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Activation/Deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Initialize components IMMEDIATELY, not on plugins_loaded
        $this->init_components();
        
        // Load text domain
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    
    /**
     * Initialize components
     */
    public function init_components() {
        // Initialize admin
        if (is_admin()) {
            PMC_Admin::get_instance();
        }
        
        // Initialize frontend
        PMC_Frontend::get_instance();
        
        // Elementor widget is loaded via elementor/loaded hook
    }
    
    /**
     * Load text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('product-media-carousel', false, dirname(PMC_PLUGIN_BASENAME) . '/languages');
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Swiper CSS
        wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
        
        // Fancybox CSS
        wp_enqueue_style('fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css', array(), '5.0.0');
        
        // Plugin CSS
        wp_enqueue_style('pmc-frontend', PMC_PLUGIN_URL . 'assets/css/frontend.css', array('swiper'), PMC_VERSION);
        
        // Swiper JS
        wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
        
        // Fancybox JS
        wp_enqueue_script('fancybox', 'https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js', array(), '5.0.0', true);
        
        // Plugin JS
        wp_enqueue_script('pmc-frontend', PMC_PLUGIN_URL . 'assets/js/frontend.js', array('jquery', 'swiper', 'fancybox'), PMC_VERSION, true);
        
        // Localize script
        wp_localize_script('pmc-frontend', 'pmcData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pmc_nonce')
        ));
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on product edit page
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post;
        if (!$post || 'product' !== $post->post_type) {
            return;
        }
        
        // Sortable UI
        wp_enqueue_script('jquery-ui-sortable');
        
        // WordPress Media Uploader (for video upload)
        wp_enqueue_media();
        
        // Admin CSS
        wp_enqueue_style('pmc-admin', PMC_PLUGIN_URL . 'assets/css/admin.css', array(), PMC_VERSION);
        
        // Admin JS
        wp_enqueue_script('pmc-admin', PMC_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'jquery-ui-sortable'), PMC_VERSION, true);
        
        // Localize script
        wp_localize_script('pmc-admin', 'pmcAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pmc_admin_nonce'),
            'confirmDelete' => __('Are you sure you want to delete this item?', 'product-media-carousel')
        ));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Create database tables
        PMC_Database::create_tables();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
}

/**
 * Initialize the plugin
 */
function pmc_init() {
    return Product_Media_Carousel::get_instance();
}

// Start the plugin on plugins_loaded
add_action('plugins_loaded', 'pmc_init', 10);
