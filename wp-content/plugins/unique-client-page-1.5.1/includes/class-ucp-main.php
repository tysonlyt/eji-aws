<?php
/**
 * Main Class for UCP Plugin
 *
 * @package Unique_Client_Page
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main class to coordinate all UCP components
 */
class UCP_Main {
    
    /**
     * The single instance of this class
     *
     * @var UCP_Main
     */
    protected static $instance = null;
    
    /**
     * Plugin name
     *
     * @var string
     */
    protected $plugin_name;
    
    /**
     * Plugin version
     *
     * @var string
     */
    protected $version;
    
    /**
     * Form component instance
     *
     * @var UCP_Product_Form
     */
    protected $form;
    
    /**
     * Product selector component
     *
     * @var UCP_Product_Selector
     */
    protected $selector;
    
    /**
     * Page creator component
     *
     * @var UCP_Page_Creator
     */
    protected $creator;
    
    /**
     * Settings component
     *
     * @var UCP_Settings
     */
    protected $settings;
    
    /**
     * Get class instance
     *
     * @return UCP_Main
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->plugin_name = 'unique-client-page';
        $this->version = '1.3.1';
        
        $this->load_dependencies();
        $this->init_components();
        $this->init_hooks();
    }
    
    /**
     * Load required dependencies
     *
     * @return void
     */
    private function load_dependencies() {
        // Load base class
        require_once plugin_dir_path(__FILE__) . 'class-ucp-base.php';
        
        // Load component classes
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/class-ucp-product-form.php';
        // Load product selector module
        require_once plugin_dir_path(dirname(__FILE__)) . 'modules/product-selector/class-ucp-product-selector.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/class-ucp-page-creator.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/classes/class-ucp-settings.php';
        
        // Load original class for class definition, but don't initialize instance
        require_once plugin_dir_path(dirname(__FILE__)) . 'frontend/classes/class-ucp-product-page.php';
        
        // 加载愿望清单版本管理和AJAX处理类
        require_once plugin_dir_path(__FILE__) . 'class-ucp-wishlist-version-manager.php';
        require_once plugin_dir_path(__FILE__) . 'class-ucp-wishlist-version-ajax.php';
    }
    
    /**
     * Initialize components
     *
     * @return void
     */
    private function init_components() {
        // Initialize all components
        $this->settings = new UCP_Settings($this->plugin_name, $this->version);
        $this->form = new UCP_Product_Form($this->plugin_name, $this->version);
        $this->selector = new UCP_Product_Selector($this->plugin_name, $this->version);
        $this->creator = new UCP_Page_Creator($this->plugin_name, $this->version);
        
        // Initialize components
        $this->settings->init();
        $this->form->init();
        $this->selector->init();
        $this->creator->init();
        
        // Don't initialize original class - using only new components
        // UCP_Product_Page::get_instance();
    }
    
    /**
     * Initialize WordPress hooks
     *
     * @return void
     */
    private function init_hooks() {
        // No additional hooks needed here as each component registers its own hooks
        // during the init() method call in init_components()
        
        // Add plugin init hook for late initialization
        add_action('plugins_loaded', array($this, 'plugin_loaded'));
        
        // 初始化愿望清单版本AJAX处理
        UCP_Wishlist_Version_Ajax::get_instance();
    }
    
    /**
     * Register admin menu
     *
     * @return void
     */
    public function plugin_loaded() {
        // Actions to perform after all plugins are loaded
        do_action('ucp_plugin_loaded');
    }
    
    /**
     * Get form component
     *
     * @return UCP_Product_Form
     */
    public function get_form() {
        return $this->form;
    }
    
    /**
     * Get selector component
     *
     * @return UCP_Product_Selector
     */
    public function get_selector() {
        return $this->selector;
    }
    
    /**
     * Get creator component
     *
     * @return UCP_Page_Creator
     */
    public function get_creator() {
        return $this->creator;
    }
    
    /**
     * Get settings component
     *
     * @return UCP_Settings
     */
    public function get_settings() {
        return $this->settings;
    }
}

// Initialize the main class
UCP_Main::get_instance();
