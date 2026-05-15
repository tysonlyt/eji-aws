<?php
/**
 * Migration Helper Component
 *
 * Handles plugin version migrations, database upgrades, and data structure changes
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Migration Helper Component
 */
class UCP_Migration_Helper {
    /**
     * Class instance
     *
     * @var UCP_Migration_Helper
     */
    private static $instance = null;
    
    /**
     * Database version option name
     * 
     * @var string
     */
    private $db_version_option = 'ucp_database_version';
    
    /**
     * Current plugin version
     * 
     * @var string
     */
    private $current_version;
    
    /**
     * Debug manager reference
     * 
     * @var UCP_Debug_Manager
     */
    private $debug_manager = null;
    
    /**
     * Get the singleton instance
     *
     * @return UCP_Migration_Helper instance
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
        $this->current_version = defined('UCP_VERSION') ? UCP_VERSION : '1.3.2';
        
        // Get reference to debug manager
        if (class_exists('UCP_Debug_Manager')) {
            $this->debug_manager = UCP_Debug_Manager::get_instance();
        }
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Check for upgrades on admin init (but not during AJAX calls)
        if (is_admin() && !wp_doing_ajax()) {
            add_action('admin_init', array($this, 'check_version'));
        }
    }
    
    /**
     * Check if plugin needs upgrade
     */
    public function check_version() {
        $db_version = get_option($this->db_version_option, '1.0.0');
        
        if (version_compare($db_version, $this->current_version, '<')) {
            $this->run_upgrades($db_version);
        }
    }
    
    /**
     * Run necessary upgrades
     *
     * @param string $from_version Current database version
     */
    public function run_upgrades($from_version) {
        $this->log("Starting database upgrade from version {$from_version} to {$this->current_version}");
        
        // Run upgrades sequentially
        if (version_compare($from_version, '1.1.0', '<')) {
            $this->upgrade_to_110();
        }
        
        if (version_compare($from_version, '1.2.0', '<')) {
            $this->upgrade_to_120();
        }
        
        if (version_compare($from_version, '1.3.0', '<')) {
            $this->upgrade_to_130();
        }
        
        if (version_compare($from_version, '1.3.2', '<')) {
            $this->upgrade_to_132();
        }
        
        // Update database version
        update_option($this->db_version_option, $this->current_version);
        
        $this->log("Database upgrade completed successfully");
    }
    
    /**
     * Upgrade to version 1.1.0
     */
    private function upgrade_to_110() {
        $this->log("Running upgrade to 1.1.0");
        
        // Create initial wishlist table
        $this->create_wishlist_table();
    }
    
    /**
     * Upgrade to version 1.2.0
     */
    private function upgrade_to_120() {
        $this->log("Running upgrade to 1.2.0");
        
        // Add wishlist version tracking table
        $this->create_wishlist_versions_table();
    }
    
    /**
     * Upgrade to version 1.3.0
     */
    private function upgrade_to_130() {
        $this->log("Running upgrade to 1.3.0");
        
        // Fix column names in wishlist version table
        $this->fix_wishlist_versions_columns();
    }
    
    /**
     * Upgrade to version 1.3.2
     */
    private function upgrade_to_132() {
        $this->log("Running upgrade to 1.3.2");
        
        // Add notes column to wishlist versions if not exists
        $this->add_notes_column_to_versions();
    }
    
    /**
     * Create wishlist table
     */
    private function create_wishlist_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ucp_wishlist';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            page_id bigint(20) NOT NULL,
            wishlist_key varchar(64) NOT NULL,
            wishlist_data longtext,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY wishlist_key (wishlist_key)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        if ($wpdb->last_error) {
            $this->log("Error creating wishlist table: " . $wpdb->last_error, 'error');
        } else {
            $this->log("Wishlist table created successfully");
        }
    }
    
    /**
     * Create wishlist versions table
     */
    private function create_wishlist_versions_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            version_id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            page_id bigint(20) NOT NULL,
            version_number int(11) NOT NULL,
            version_name varchar(255),
            wishlist_data longtext,
            created_by bigint(20) NOT NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            is_current tinyint(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY  (version_id),
            KEY user_page (user_id,page_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        if ($wpdb->last_error) {
            $this->log("Error creating wishlist versions table: " . $wpdb->last_error, 'error');
        } else {
            $this->log("Wishlist versions table created successfully");
        }
    }
    
    /**
     * Fix column names in wishlist version table
     * 
     * 根据之前的记忆，表中的'products'字段应该改为'wishlist_data'
     */
    private function fix_wishlist_versions_columns() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        
        // Check if the column exists
        $columns = $wpdb->get_results("SHOW COLUMNS FROM {$table_name}");
        $column_names = wp_list_pluck($columns, 'Field');
        
        if (in_array('products', $column_names) && !in_array('wishlist_data', $column_names)) {
            // Rename products to wishlist_data
            $wpdb->query("ALTER TABLE {$table_name} CHANGE products wishlist_data longtext");
            
            if ($wpdb->last_error) {
                $this->log("Error renaming products column: " . $wpdb->last_error, 'error');
            } else {
                $this->log("Column renamed from products to wishlist_data successfully");
            }
        }
    }
    
    /**
     * Add notes column to wishlist versions table
     */
    private function add_notes_column_to_versions() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        
        // Check if the column exists
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM {$table_name} LIKE 'notes'");
        
        if (empty($column_exists)) {
            // Add notes column
            $wpdb->query("ALTER TABLE {$table_name} ADD COLUMN notes text AFTER is_current");
            
            if ($wpdb->last_error) {
                $this->log("Error adding notes column: " . $wpdb->last_error, 'error');
            } else {
                $this->log("Notes column added successfully");
            }
        }
    }
    
    /**
     * Check and repair database tables
     *
     * @return array Results of the repair operation
     */
    public function check_and_repair_tables() {
        global $wpdb;
        
        $this->log("Starting database table check and repair");
        
        $tables = array(
            $wpdb->prefix . 'ucp_wishlist',
            $wpdb->prefix . 'ucp_wishlist_versions'
        );
        
        $tables_string = implode(', ', $tables);
        $repair_results = $wpdb->get_results("REPAIR TABLE {$tables_string}");
        
        $results = array();
        foreach ($repair_results as $result) {
            $results[$result->Table] = $result->Msg_text;
            $this->log("Repair result for {$result->Table}: {$result->Msg_text}");
        }
        
        return $results;
    }
    
    /**
     * Optimize database tables
     *
     * @return array Results of the optimization
     */
    public function optimize_tables() {
        global $wpdb;
        
        $this->log("Starting database table optimization");
        
        $tables = array(
            $wpdb->prefix . 'ucp_wishlist',
            $wpdb->prefix . 'ucp_wishlist_versions'
        );
        
        $tables_string = implode(', ', $tables);
        $optimize_results = $wpdb->get_results("OPTIMIZE TABLE {$tables_string}");
        
        $results = array();
        foreach ($optimize_results as $result) {
            $results[$result->Table] = $result->Msg_text;
            $this->log("Optimization result for {$result->Table}: {$result->Msg_text}");
        }
        
        return $results;
    }
    
    /**
     * Get current database structures
     * 
     * @return array Table structures
     */
    public function get_table_structures() {
        global $wpdb;
        
        $tables = array(
            'wishlist' => $wpdb->prefix . 'ucp_wishlist',
            'wishlist_versions' => $wpdb->prefix . 'ucp_wishlist_versions'
        );
        
        $structures = array();
        foreach ($tables as $name => $table) {
            $structures[$name] = $wpdb->get_results("DESCRIBE {$table}");
        }
        
        return $structures;
    }
    
    /**
     * Log a message
     *
     * @param string $message Message to log
     * @param string $level Log level (debug, info, warning, error)
     */
    private function log($message, $level = 'debug') {
        if ($this->debug_manager) {
            $this->debug_manager->log($message, $level, 'migration');
        }
    }
}
