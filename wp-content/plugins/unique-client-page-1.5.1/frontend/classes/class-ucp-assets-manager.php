<?php
/**
 * Assets Manager Component
 * 
 * Handles all frontend and admin assets (JS, CSS)
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Assets Manager Class
 */
class UCP_Assets_Manager {
    /**
     * Component instance
     *
     * @var UCP_Assets_Manager
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return UCP_Assets_Manager Component instance
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
        // Frontend assets
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Admin assets
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        global $post;
        
        // 生成包含时间戳的版本号，强制刷新缓存
        $version_with_timestamp = defined('UCP_VERSION') ? UCP_VERSION . '.' . time() : '1.3.2.' . time();
        
        // Only load assets on pages using our template or if shortcode is present
        if (is_page() && !empty($post)) {
            $template = get_post_meta($post->ID, '_wp_page_template', true);
            $is_client_page = ($template === 'unique-client-template.php');
            $has_shortcode = has_shortcode($post->post_content, 'unique_client_products');
            
            if ($is_client_page || $has_shortcode) {
                // 加载主样式表
                wp_enqueue_style('ucp-styles', UCP_PLUGIN_URL . 'assets/css/ucp-styles.css', array(), $version_with_timestamp);
                
                // 加载核心样式
                wp_enqueue_style('ucp-core', UCP_PLUGIN_URL . 'assets/shared/css/_variables.css', array(), $version_with_timestamp);
                
                // 加载基础样式
                wp_enqueue_style('ucp-base', UCP_PLUGIN_URL . 'assets/shared/css/_base.css', array('ucp-core'), $version_with_timestamp);
                
                // 加载组件样式
                wp_enqueue_style('ucp-components', UCP_PLUGIN_URL . 'assets/css/components/_buttons.css', array('ucp-core'), $version_with_timestamp);
                
                // 加载产品网格样式
                wp_enqueue_style('ucp-product-grid', UCP_PLUGIN_URL . 'assets/css/layouts/_product-grid.css', array('ucp-core'), $version_with_timestamp);
                
                // 加载模态框样式
                wp_enqueue_style('ucp-modals', UCP_PLUGIN_URL . 'assets/css/components/_modals.css', array('ucp-core'), $version_with_timestamp);
                
                // 加载布局样式
                wp_enqueue_style('ucp-layouts', UCP_PLUGIN_URL . 'assets/css/layouts/_grid.css', array('ucp-core'), $version_with_timestamp);
                
                // 加载页面特定样式
                wp_enqueue_style('ucp-pages', UCP_PLUGIN_URL . 'assets/css/pages/_product.css', array('ucp-core'), $version_with_timestamp);
                
                // 加载JavaScript脚本
                wp_enqueue_script('ucp-scripts', UCP_PLUGIN_URL . 'assets/js/ucp-scripts.js', array('jquery'), $version_with_timestamp, true);
                
                // 加载模态框脚本
                wp_enqueue_script('ucp-modal-manager', UCP_PLUGIN_URL . 'frontend/assets/js/ucp-modal-manager.js', array('jquery'), $version_with_timestamp, true);
                
                // 本地化脚本，添加AJAX URL和nonce
                wp_localize_script('ucp-scripts', 'ucp_params', array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('ucp-ajax-nonce'),
                    'loading_text' => __('Loading...', 'unique-client-page'),
                    'error_text' => __('An error occurred. Please try again.', 'unique-client-page'),
                    'strings' => array(
                        'loading' => __('Loading...', 'unique-client-page'),
                        'no_products' => __('No products found', 'unique-client-page'),
                        'add_to_wishlist' => __('Add to wishlist', 'unique-client-page'),
                        'added_to_wishlist' => __('Added to wishlist', 'unique-client-page'),
                        'error' => __('Error', 'unique-client-page')
                    )
                ));
            }
        }
    }
    
    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page
     */
    public function enqueue_admin_scripts($hook) {
        // Check if we're on our plugin's admin pages
        if (strpos($hook, 'unique-client-page') !== false || strpos($hook, 'ucp-') !== false) {
            // Admin CSS
            wp_enqueue_style(
                'ucp-admin-css',
                plugin_dir_url(dirname(dirname(__FILE__))) . 'admin/assets/css/admin.css',
                array(),
                defined('UCP_VERSION') ? UCP_VERSION : '1.3.2'
            );
            
            // Admin JS
            wp_enqueue_script(
                'ucp-admin-js',
                plugin_dir_url(dirname(dirname(__FILE__))) . 'admin/assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                defined('UCP_VERSION') ? UCP_VERSION : '1.3.2',
                true
            );
            
            // Localize admin script
            wp_localize_script(
                'ucp-admin-js',
                'ucp_admin_vars',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('ucp_admin_nonce'),
                    'strings' => array(
                        'confirm_delete' => __('Are you sure you want to delete this item?', 'unique-client-page'),
                        'saving' => __('Saving...', 'unique-client-page'),
                        'saved' => __('Saved!', 'unique-client-page'),
                        'error' => __('Error', 'unique-client-page')
                    )
                )
            );
            
            // Enqueue WordPress media library scripts
            wp_enqueue_media();
            
            // Check if we're on the wishlist management page and load specific scripts
            if (strpos($hook, 'ucp-wishlist-manage') !== false) {
                // Modal styles if not already included
                wp_enqueue_style(
                    'ucp-modal-css',
                    plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/css/ucp-modal.css',
                    array(),
                    defined('UCP_VERSION') ? UCP_VERSION : '1.3.2'
                );
                
                // Wishlist management specific JS
                wp_enqueue_script(
                    'ucp-wishlist-manager-js',
                    plugin_dir_url(dirname(dirname(__FILE__))) . 'admin/assets/js/wishlist-manager.js',
                    array('jquery', 'ucp-admin-js'),
                    defined('UCP_VERSION') ? UCP_VERSION : '1.3.2',
                    true
                );
                
                // Add additional data for wishlist management
                wp_localize_script(
                    'ucp-wishlist-manager-js',
                    'ucp_wishlist_vars',
                    array(
                        'ajax_url' => admin_url('admin-ajax.php'),
                        'nonce' => wp_create_nonce('ucp_wishlist_nonce'),
                        'strings' => array(
                            'loading' => __('Loading version details...', 'unique-client-page'),
                            'confirm_set_current' => __('Are you sure you want to set this as the current wishlist version?', 'unique-client-page'),
                            'version_set' => __('Version set as current successfully!', 'unique-client-page'),
                            'error' => __('Error processing your request', 'unique-client-page')
                        )
                    )
                );
            }
        }
    }
    
    /**
     * Get an asset URL
     *
     * @param string $asset Asset path relative to the plugin's assets directory
     * @return string Full URL to the asset
     */
    public function get_asset_url($asset) {
        return plugin_dir_url(dirname(dirname(__FILE__))) . 'assets/' . $asset;
    }
}
