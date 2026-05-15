<?php
/**
 * Compatibility Checker
 * Checks system requirements and displays warnings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Compatibility {
    
    /**
     * Minimum requirements
     */
    const MIN_PHP_VERSION = '7.4';
    const MIN_WP_VERSION = '5.8';
    const MIN_WC_VERSION = '5.0';
    const MIN_ELEMENTOR_VERSION = '3.0';
    
    /**
     * Check all requirements
     */
    public static function check_requirements() {
        $errors = array();
        
        // Check PHP version
        if (!self::check_php_version()) {
            $errors[] = sprintf(
                __('Product Media Carousel requires PHP %s or higher. You are running PHP %s.', 'product-media-carousel'),
                self::MIN_PHP_VERSION,
                PHP_VERSION
            );
        }
        
        // Check WordPress version
        if (!self::check_wp_version()) {
            $errors[] = sprintf(
                __('Product Media Carousel requires WordPress %s or higher. You are running WordPress %s.', 'product-media-carousel'),
                self::MIN_WP_VERSION,
                get_bloginfo('version')
            );
        }
        
        // Check WooCommerce
        if (!self::check_woocommerce()) {
            $errors[] = sprintf(
                __('Product Media Carousel requires WooCommerce %s or higher to be installed and activated.', 'product-media-carousel'),
                self::MIN_WC_VERSION
            );
        }
        
        // Check Elementor (warning only, not required)
        if (!self::check_elementor()) {
            // This is just a notice, not an error
            add_action('admin_notices', array(__CLASS__, 'elementor_notice'));
        }
        
        return $errors;
    }
    
    /**
     * Check PHP version
     */
    public static function check_php_version() {
        return version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '>=');
    }
    
    /**
     * Check WordPress version
     */
    public static function check_wp_version() {
        return version_compare(get_bloginfo('version'), self::MIN_WP_VERSION, '>=');
    }
    
    /**
     * Check WooCommerce
     */
    public static function check_woocommerce() {
        if (!class_exists('WooCommerce')) {
            return false;
        }
        
        if (defined('WC_VERSION')) {
            return version_compare(WC_VERSION, self::MIN_WC_VERSION, '>=');
        }
        
        return true;
    }
    
    /**
     * Check Elementor
     */
    public static function check_elementor() {
        if (!did_action('elementor/loaded')) {
            return false;
        }
        
        if (defined('ELEMENTOR_VERSION')) {
            return version_compare(ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '>=');
        }
        
        return true;
    }
    
    /**
     * Display error notices
     */
    public static function display_errors($errors) {
        if (empty($errors)) {
            return;
        }
        
        foreach ($errors as $error) {
            echo '<div class="notice notice-error"><p><strong>Product Media Carousel:</strong> ' . esc_html($error) . '</p></div>';
        }
    }
    
    /**
     * Elementor notice
     */
    public static function elementor_notice() {
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong><?php _e('Product Media Carousel:', 'product-media-carousel'); ?></strong>
                <?php _e('Elementor is not installed. The Elementor widget will not be available. You can still use the shortcode.', 'product-media-carousel'); ?>
            </p>
        </div>
        <?php
    }
    
    /**
     * Get system info for debugging
     */
    public static function get_system_info() {
        global $wpdb;
        
        $info = array(
            'PHP Version' => PHP_VERSION,
            'WordPress Version' => get_bloginfo('version'),
            'WooCommerce Version' => defined('WC_VERSION') ? WC_VERSION : 'Not installed',
            'Elementor Version' => defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'Not installed',
            'MySQL Version' => $wpdb->db_version(),
            'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'PHP Memory Limit' => ini_get('memory_limit'),
            'PHP Max Upload Size' => ini_get('upload_max_filesize'),
            'PHP Post Max Size' => ini_get('post_max_size'),
            'PHP Max Execution Time' => ini_get('max_execution_time'),
            'Active Theme' => wp_get_theme()->get('Name') . ' ' . wp_get_theme()->get('Version'),
            'Active Plugins' => count(get_option('active_plugins')),
        );
        
        return $info;
    }
    
    /**
     * Check for known plugin conflicts
     */
    public static function check_conflicts() {
        $conflicts = array();
        
        // Check for conflicting plugins
        $conflicting_plugins = array(
            // Add known conflicting plugins here
        );
        
        foreach ($conflicting_plugins as $plugin => $message) {
            if (is_plugin_active($plugin)) {
                $conflicts[] = $message;
            }
        }
        
        return $conflicts;
    }
    
    /**
     * Test database connection and permissions
     */
    public static function test_database() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'product_media_gallery';
        
        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
        
        if (!$table_exists) {
            return array(
                'status' => 'error',
                'message' => __('Database table does not exist. Try deactivating and reactivating the plugin.', 'product-media-carousel')
            );
        }
        
        // Test write permission
        $test_result = $wpdb->query("SELECT 1 FROM {$table_name} LIMIT 1");
        
        if ($test_result === false) {
            return array(
                'status' => 'error',
                'message' => __('Cannot read from database table.', 'product-media-carousel')
            );
        }
        
        return array(
            'status' => 'success',
            'message' => __('Database connection OK', 'product-media-carousel')
        );
    }
}
