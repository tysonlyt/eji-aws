<?php
/**
 * Base Class for UCP Components
 *
 * @package Unique_Client_Page
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Abstract Base Class for all UCP components
 */
abstract class UCP_Base {
    
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
     * Constructor
     *
     * @param string $plugin_name The name of the plugin
     * @param string $version The version of the plugin
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Get plugin name
     *
     * @return string
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    
    /**
     * Get plugin version
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }
    
    /**
     * Enqueue a script
     *
     * @param string $handle Script handle
     * @param string $src Script source
     * @param array $deps Dependencies
     * @param bool $in_footer Whether to load in footer
     * @return void
     */
    protected function enqueue_script($handle, $src, $deps = array(), $in_footer = true) {
        wp_enqueue_script(
            $handle,
            $src,
            $deps,
            $this->version,
            $in_footer
        );
    }
    
    /**
     * Enqueue a style
     *
     * @param string $handle Style handle
     * @param string $src Style source
     * @param array $deps Dependencies
     * @param string $media Media type
     * @return void
     */
    protected function enqueue_style($handle, $src, $deps = array(), $media = 'all') {
        wp_enqueue_style(
            $handle,
            $src,
            $deps,
            $this->version,
            $media
        );
    }
    
    /**
     * Validate nonce
     *
     * @param string $nonce Nonce value
     * @param string $action Nonce action
     * @return bool
     */
    protected function verify_nonce($nonce, $action) {
        if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
            return false;
        }
        return true;
    }
    
    /**
     * Check if user has capability
     *
     * @param string $capability Capability to check
     * @return bool
     */
    protected function current_user_can($capability) {
        return current_user_can($capability);
    }
    
    /**
     * Get plugin file path
     *
     * @param string $relative_path Relative path from plugin root
     * @return string
     */
    protected function get_plugin_file_path($relative_path) {
        return plugin_dir_path(dirname(__FILE__, 2)) . $relative_path;
    }
    
    /**
     * Get plugin file URL
     *
     * @param string $relative_path Relative path from plugin root
     * @return string
     */
    protected function get_plugin_file_url($relative_path) {
        if (defined('UCP_PLUGIN_URL')) {
            return UCP_PLUGIN_URL . $relative_path;
        }
        
        $plugin_dir = plugin_dir_path(dirname(__FILE__));
        $plugin_url = plugins_url('/', dirname(__FILE__));
        
        error_log('UCP Debug - Plugin URL calculation: ' . $plugin_url . ' for path: ' . $relative_path);
        
        return $plugin_url . $relative_path;
    }
}
