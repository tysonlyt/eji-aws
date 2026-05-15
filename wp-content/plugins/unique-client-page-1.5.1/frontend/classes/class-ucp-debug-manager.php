<?php
/**
 * Debug Manager Component
 * 
 * Handles plugin debugging, logging and performance monitoring
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Debug Manager Component Class
 */
class UCP_Debug_Manager {
    /**
     * Class instance
     *
     * @var UCP_Debug_Manager
     */
    private static $instance = null;
    
    /**
     * Debug enabled flag
     *
     * @var bool
     */
    private $debug_enabled = false;
    
    /**
     * Log file path
     *
     * @var string
     */
    private $log_file = '';
    
    /**
     * Performance timers
     *
     * @var array
     */
    private $timers = array();
    
    /**
     * Get singleton instance
     *
     * @return UCP_Debug_Manager Component instance
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
        // Check if debug mode is enabled
        $this->debug_enabled = defined('WP_DEBUG') && WP_DEBUG;
        
        // Set log file path
        $this->log_file = WP_CONTENT_DIR . '/ucp-debug.log';
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Add debug info to admin and frontend
        if ($this->debug_enabled) {
            add_action('admin_footer', array($this, 'render_admin_debug_info'));
            add_action('wp_footer', array($this, 'render_frontend_debug_info'));
            add_action('admin_menu', array($this, 'add_debug_menu'), 99);
        }
    }
    
    /**
     * Add debug menu
     */
    public function add_debug_menu() {
        add_submenu_page(
            'unique-client-page',
            __('Debug Info', 'unique-client-page'),
            __('Debug Info', 'unique-client-page'),
            'manage_options',
            'ucp-debug-info',
            array($this, 'render_debug_page')
        );
    }
    
    /**
     * Log debug information
     *
     * @param string $message Debug message
     * @param string $level Log level (debug, info, warning, error)
     * @param string $component Component name
     */
    public function log($message, $level = 'debug', $component = 'core') {
        if (!$this->debug_enabled) {
            return;
        }
        
        $timestamp = date('Y-m-d H:i:s');
        $formatted = "[{$timestamp}] [{$level}] [{$component}] {$message}\n";
        
        // Write to log file
        error_log($formatted, 3, $this->log_file);
    }
    
    /**
     * Log database error
     *
     * @param string $operation Database operation description
     * @param string $error Error message
     * @param string $query Optional SQL query
     */
    public function log_db_error($operation, $error, $query = '') {
        $message = "Database error({$operation}): {$error}";
        if (!empty($query)) {
            $message .= "\nQuery: {$query}";
        }
        $this->log($message, 'error', 'database');
    }
    
    /**
     * Start performance timer
     *
     * @param string $name Timer name
     */
    public function start_timer($name) {
        $this->timers[$name] = microtime(true);
    }
    
    /**
     * End timer and log
     *
     * @param string $name Timer name
     * @return float Execution time (ms)
     */
    public function end_timer($name) {
        if (!isset($this->timers[$name])) {
            return 0;
        }
        
        $execution_time = microtime(true) - $this->timers[$name];
        $execution_time_ms = round($execution_time * 1000, 2);
        
        $this->log("Timer [{$name}]: {$execution_time_ms}ms", 'info', 'performance');
        
        unset($this->timers[$name]);
        return $execution_time_ms;
    }
    
    /**
     * Render debug info in admin footer
     */
    public function render_admin_debug_info() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        echo '<div class="ucp-debug-info" style="margin-top: 20px; padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-size: 12px;">';
        echo '<h4>UCP Debug Info</h4>';
        
        // Display memory usage
        $memory_usage = memory_get_usage() / 1024 / 1024;
        echo '<p>Memory usage: ' . round($memory_usage, 2) . ' MB</p>';
        
        // Display database queries
        global $wpdb;
        echo '<p>Database queries: ' . $wpdb->num_queries . '</p>';
        
        // Display load time
        $load_time = timer_stop(0, 3);
        echo '<p>Page load time: ' . $load_time . ' seconds</p>';
        
        echo '</div>';
    }
    
    /**
     * Render debug info in frontend footer
     */
    public function render_frontend_debug_info() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        echo '<div class="ucp-debug-info" style="margin-top: 20px; padding: 10px; background: #f7f7f7; border-top: 1px solid #ddd; font-size: 12px; color: #666;">';
        echo '<h4>UCP Debug Info</h4>';
        
        // Display load time
        $load_time = timer_stop(0, 3);
        echo '<p>Page load time: ' . $load_time . ' seconds</p>';
        
        echo '</div>';
    }
    
    /**
     * Render debug page
     */
    public function render_debug_page() {
        echo '<div class="wrap">';
        echo '<h1>' . __('UCP Debug Info', 'unique-client-page') . '</h1>';
        
        // Handle actions
        if (isset($_GET['action']) && $_GET['action'] == 'clear_log' && current_user_can('manage_options')) {
            check_admin_referer('ucp_clear_log');
            $this->clear_log();
            echo '<div class="notice notice-success"><p>' . __('Log has been cleared', 'unique-client-page') . '</p></div>';
        }
        
        // Display log file content
        echo '<h2>Log File</h2>';
        
        if (file_exists($this->log_file)) {
            $log_content = file_get_contents($this->log_file);
            $log_size = filesize($this->log_file) / 1024;
            
            echo '<div style="margin-bottom: 15px;">';
            echo '<p>Log size: ' . round($log_size, 2) . ' KB</p>';
            echo '<p><a href="' . wp_nonce_url(admin_url('admin.php?page=ucp-debug-info&action=clear_log'), 'ucp_clear_log') . '" class="button">Clear Log</a></p>';
            echo '</div>';
            
            echo '<div style="background: #fff; padding: 15px; border: 1px solid #ddd; max-height: 500px; overflow-y: auto;">';
            echo '<pre>' . esc_html($log_content) . '</pre>';
            echo '</div>';
        } else {
            echo '<p>Log file does not exist or is empty.</p>';
        }
        
        // Display active components
        echo '<h2>Active Components</h2>';
        $this->show_active_components();
        
        echo '</div>'; // .wrap
    }
    
    /**
     * Display active components
     */
    private function show_active_components() {
        echo '<table class="widefat">';
        echo '<thead><tr><th>Component</th><th>Status</th></tr></thead>';
        echo '<tbody>';
        
        $components = array(
            'UCP_Product_Display' => 'Product Display Component',
            'UCP_Admin_UI' => 'Admin UI Component',
            'UCP_Template_Handler' => 'Template Handler Component',
            'UCP_Ajax_Controller' => 'AJAX Controller Component',
            'UCP_Wishlist_Manager' => 'Wishlist Manager Component',
            'UCP_Assets_Manager' => 'Assets Manager Component',
            'UCP_Debug_Manager' => 'Debug Manager Component'
        );
        
        foreach ($components as $class => $name) {
            $status = class_exists($class) ? '<span style="color:green;">✓ Active</span>' : '<span style="color:red;">✗ Not loaded</span>';
            echo "<tr><td>{$name}</td><td>{$status}</td></tr>";
        }
        
        echo '</tbody></table>';
    }
    
    /**
     * Clear log file
     */
    public function clear_log() {
        if (file_exists($this->log_file)) {
            @unlink($this->log_file);
        }
        
        // Create new empty file
        @file_put_contents($this->log_file, '');
    }
    
    /**
     * Debug print variable (only when debug mode is enabled)
     *
     * @param mixed $var Variable to print
     * @param bool $return Whether to return instead of outputting
     * @return string|void If $return is true, returns formatted content
     */
    public function debug_print($var, $return = false) {
        if (!$this->debug_enabled) {
            return;
        }
        
        $output = '<pre style="background: #f5f5f5; padding: 10px; border: 1px solid #ddd; margin: 10px 0; text-align: left;">';
        $output .= print_r($var, true);
        $output .= '</pre>';
        
        if ($return) {
            return $output;
        }
        
        echo $output;
    }
}
