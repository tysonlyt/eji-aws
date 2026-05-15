<?php
/**
 * System Info Component
 * 
 * Provides plugin system status, performance monitoring, and diagnostics
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * System Info Class
 */
class UCP_System_Info {
    /**
     * Class instance
     * 
     * @var UCP_System_Info
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     * 
     * @return UCP_System_Info Component instance
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
        // Initialize code
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        add_action('admin_menu', array($this, 'add_system_info_menu'), 99);
        add_action('admin_init', array($this, 'handle_system_actions'));
    }
    
    /**
     * Add system info menu
     */
    public function add_system_info_menu() {
        add_submenu_page(
            'unique-client-page',
            __('System Info', 'unique-client-page'),
            __('System Info', 'unique-client-page'),
            'manage_options',
            'ucp-system-info',
            array($this, 'render_system_info_page')
        );
    }
    
    /**
     * Handle system actions
     */
    public function handle_system_actions() {
        if (!isset($_GET['page']) || $_GET['page'] != 'ucp-system-info') {
            return;
        }
        
        if (isset($_GET['action']) && current_user_can('manage_options')) {
            // Verify nonce
            if (isset($_GET['_wpnonce']) && !wp_verify_nonce($_GET['_wpnonce'], 'ucp_system_action')) {
                wp_die(__('Security check failed', 'unique-client-page'));
            }
            
            switch ($_GET['action']) {
                case 'repair_tables':
                    $this->repair_tables();
                    break;
                case 'clear_cache':
                    $this->clear_cache();
                    break;
                case 'optimize_tables':
                    $this->optimize_tables();
                    break;
            }
        }
    }
    
    /**
     * Repair database tables
     */
    private function repair_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'ucp_wishlist_versions'
        );
        
        $results = array();
        foreach ($tables as $table) {
            $result = $wpdb->get_results("REPAIR TABLE {$table}");
            $results[$table] = !empty($result[0]->Msg_text) ? $result[0]->Msg_text : 'Unknown result';
        }
        
        add_settings_error('ucp_system_info', 'ucp_repair', 'Table repair completed: ' . implode(', ', $results), 'success');
    }
    
    /**
     * Clear cache
     */
    private function clear_cache() {
        global $wpdb;
        
        // Clear temporary files
        $temp_dir = get_temp_dir() . 'ucp-temp';
        if (is_dir($temp_dir)) {
            $files = glob($temp_dir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
        }
        
        // Clear cached options
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_ucp_cache_%'");
        
        // Clear transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_ucp_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%_transient_timeout_ucp_%'");
        
        add_settings_error('ucp_system_info', 'ucp_cache_cleared', 'Cache has been cleared', 'success');
    }
    
    /**
     * Optimize database tables
     */
    private function optimize_tables() {
        global $wpdb;
        
        $tables = array(
            $wpdb->prefix . 'ucp_wishlist_versions'
        );
        
        $results = array();
        foreach ($tables as $table) {
            $result = $wpdb->get_results("OPTIMIZE TABLE {$table}");
            $results[$table] = !empty($result[0]->Msg_text) ? $result[0]->Msg_text : 'Unknown result';
        }
        
        add_settings_error('ucp_system_info', 'ucp_optimize', 'Table optimization completed: ' . implode(', ', $results), 'success');
    }
    
    /**
     * Render system info page
     */
    public function render_system_info_page() {
        echo '<div class="wrap">';
        echo '<h1>' . __('System Info', 'unique-client-page') . '</h1>';
        
        settings_errors('ucp_system_info');
        
        echo '<div class="ucp-system-info">';
        
        // Action buttons
        echo '<div class="ucp-actions" style="margin-bottom: 20px;">';
        echo '<a href="' . wp_nonce_url(admin_url('admin.php?page=ucp-system-info&action=repair_tables'), 'ucp_system_action') . '" class="button">' . __('Repair Database Tables', 'unique-client-page') . '</a> ';
        echo '<a href="' . wp_nonce_url(admin_url('admin.php?page=ucp-system-info&action=clear_cache'), 'ucp_system_action') . '" class="button">' . __('Clear Cache', 'unique-client-page') . '</a> ';
        echo '<a href="' . wp_nonce_url(admin_url('admin.php?page=ucp-system-info&action=optimize_tables'), 'ucp_system_action') . '" class="button">' . __('Optimize Database Tables', 'unique-client-page') . '</a>';
        echo '</div>';
        
        // System status
        echo '<h2>' . __('System Status', 'unique-client-page') . '</h2>';
        $this->render_system_status();
        
        // WordPress environment
        echo '<h2>' . __('WordPress Environment', 'unique-client-page') . '</h2>';
        $this->render_wordpress_info();
        
        // Server environment
        echo '<h2>' . __('Server Environment', 'unique-client-page') . '</h2>';
        $this->render_server_info();
        
        // Database status
        echo '<h2>' . __('Database Status', 'unique-client-page') . '</h2>';
        $this->render_database_info();
        
        echo '</div>'; // .ucp-system-info
        echo '</div>'; // .wrap
    }
    
    /**
     * Render system status
     */
    private function render_system_status() {
        echo '<table class="widefat" style="margin-bottom: 20px;">';
        echo '<tbody>';
        
        // Check key components
        $components = array(
            'UCP_Product_Display' => 'Product Display Component',
            'UCP_Admin_UI' => 'Admin UI Component',
            'UCP_Template_Handler' => 'Template Handler Component',
            'UCP_Ajax_Controller' => 'AJAX Controller Component',
            'UCP_Wishlist_Manager' => 'Wishlist Manager Component',
            'UCP_Assets_Manager' => 'Assets Manager Component',
            'UCP_Debug_Manager' => 'Debug Manager Component',
            'UCP_System_Info' => 'System Info Component'
        );
        
        foreach ($components as $class => $name) {
            $status = class_exists($class) ? '<span style="color:green;">✓ Loaded</span>' : '<span style="color:red;">✗ Not loaded</span>';
            $this->add_system_info_row($name, $status);
        }
        
        // Check database tables
        global $wpdb;
        $table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") == $table_name;
        $table_status = $table_exists ? '<span style="color:green;">✓ Exists</span>' : '<span style="color:red;">✗ Missing</span>';
        $this->add_system_info_row('Wishlist Versions Table', $table_status);
        
        // Check file permissions
        $template_dir = plugin_dir_path(dirname(dirname(__FILE__))) . 'templates';
        $writable = is_writable($template_dir) ? '<span style="color:green;">✓ Writable</span>' : '<span style="color:red;">✗ Not writable</span>';
        $this->add_system_info_row('Template Directory Permissions', $writable);
        
        echo '</tbody></table>';
    }
    
    /**
     * Render WordPress environment info
     */
    private function render_wordpress_info() {
        echo '<table class="widefat" style="margin-bottom: 20px;">';
        echo '<tbody>';
        
        // WordPress version
        $wp_version = get_bloginfo('version');
        $this->add_system_info_row('WordPress Version', $wp_version);
        
        // Site URL
        $site_url = site_url();
        $this->add_system_info_row('Site URL', $site_url);
        
        // WP Debug
        $debug_status = defined('WP_DEBUG') && WP_DEBUG ? '<span style="color:green;">✓ Enabled</span>' : '<span style="color:gray;">✗ Disabled</span>';
        $this->add_system_info_row('WP_DEBUG', $debug_status);
        
        // Active theme
        $theme = wp_get_theme();
        $this->add_system_info_row('Active Theme', $theme->get('Name') . ' (' . $theme->get('Version') . ')');
        
        // Check WooCommerce
        $woocommerce_status = class_exists('WooCommerce') ? '<span style="color:green;">✓ Active</span>' : '<span style="color:red;">✗ Not active</span>';
        $this->add_system_info_row('WooCommerce', $woocommerce_status);
        
        // Plugin version
        $plugin_version = defined('UCP_VERSION') ? UCP_VERSION : 'Unknown';
        $this->add_system_info_row('UCP Plugin Version', $plugin_version);
        
        echo '</tbody></table>';
    }
    
    /**
     * Render server environment info
     */
    private function render_server_info() {
        echo '<table class="widefat" style="margin-bottom: 20px;">';
        echo '<tbody>';
        
        // PHP version
        $this->add_system_info_row('PHP Version', phpversion());
        
        // MySQL version
        global $wpdb;
        $mysql_version = $wpdb->get_var('SELECT VERSION()');
        $this->add_system_info_row('MySQL Version', $mysql_version);
        
        // Server software
        $server_software = $_SERVER['SERVER_SOFTWARE'];
        $this->add_system_info_row('Web Server', $server_software);
        
        // PHP memory limit
        $memory_limit = ini_get('memory_limit');
        $this->add_system_info_row('PHP Memory Limit', $memory_limit);
        
        // PHP max execution time
        $max_execution = ini_get('max_execution_time');
        $this->add_system_info_row('PHP Max Execution Time', $max_execution . ' seconds');
        
        // PHP post max size
        $post_max_size = ini_get('post_max_size');
        $this->add_system_info_row('PHP Post Max Size', $post_max_size);
        
        echo '</tbody></table>';
    }
    
    /**
     * Render database info
     */
    private function render_database_info() {
        global $wpdb;
        
        echo '<table class="widefat">';
        echo '<tbody>';
        
        // Database name
        $db_name = $wpdb->dbname;
        $this->add_system_info_row('Database Name', $db_name);
        
        // Table prefix
        $table_prefix = $wpdb->prefix;
        $this->add_system_info_row('Table Prefix', $table_prefix);
        
        // UCP table structure
        $table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") == $table_name) {
            $columns = $wpdb->get_results("DESCRIBE {$table_name}");
            $column_names = array();
            
            foreach ($columns as $column) {
                $column_names[] = $column->Field;
            }
            
            $this->add_system_info_row('Wishlist Versions Table Structure', implode(', ', $column_names));
            
            // Table rows count
            $row_count = $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
            $this->add_system_info_row('Wishlist Versions Count', $row_count);
        } else {
            $this->add_system_info_row('Wishlist Versions Table Structure', '<span style="color:red;">Table not found</span>');
        }
        
        echo '</tbody></table>';
    }
    
    /**
     * Add a row to system info table
     *
     * @param string $title Row title
     * @param string $value Row value
     */
    private function add_system_info_row($title, $value) {
        echo '<tr>';
        echo '<td style="width: 30%; padding: 10px;"><strong>' . esc_html($title) . '</strong></td>';
        echo '<td style="padding: 10px;">' . $value . '</td>';
        echo '</tr>';
    }
}
