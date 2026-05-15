<?php
/**
 * Handles database operations for wishlist versioning
 * 
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UCP_Wishlist_Versioning {
    private static $instance = null;
    public $version = '1.0.0';
    public $db_version = '1.0';
    private $table_name;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        add_action('plugins_loaded', array($this, 'check_db_version'));
        register_activation_hook(UCP_PLUGIN_FILE, array($this, 'install'));
    }

    public function check_db_version() {
        if (get_site_option('ucp_wishlist_db_version') !== $this->db_version) {
            $this->install();
        }
    }

    public function install() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            version_id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            page_id bigint(20) NOT NULL,
            version_number int(11) NOT NULL,
            version_name varchar(100) NOT NULL,
            wishlist_data longtext NOT NULL,
            created_by bigint(20) NOT NULL,
            created_at datetime NOT NULL,
            is_current tinyint(1) DEFAULT 0,
            notes text,
            PRIMARY KEY  (version_id),
            KEY user_page (user_id, page_id),
            KEY is_current (is_current)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        update_option('ucp_wishlist_db_version', $this->db_version);
    }
}

// Initialize
UCP_Wishlist_Versioning::get_instance();
