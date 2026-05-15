<?php
/**
 * Database update functions for Unique Client Page plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UCP_DB_Updates {
    private static $instance = null;
    private $db_version = '1.4.0';
    private $current_version;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->current_version = get_option('ucp_db_version', '1.0.0');
        
        if (version_compare($this->current_version, '1.4.0', '<')) {
            $this->create_wishlist_versions_table();
            $this->update_wishlist_structure();
            update_option('ucp_db_version', '1.4.0');
        }
    }

    private function create_wishlist_versions_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ucp_wishlist_versions';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            version_id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            page_id bigint(20) NOT NULL,
            version_number int(11) NOT NULL DEFAULT 1,
            version_name varchar(100) NOT NULL,
            is_current tinyint(1) NOT NULL DEFAULT 0,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (version_id),
            KEY user_page (user_id, page_id),
            KEY is_current (is_current)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    private function update_wishlist_structure() {
        global $wpdb;
        
        // Get all users with wishlists
        $users = get_users(array(
            'meta_query' => array(
                array(
                    'key'     => $wpdb->get_blog_prefix() . 'capabilities',
                    'compare' => 'EXISTS',
                ),
            ),
        ));

        foreach ($users as $user) {
            $wishlists = get_user_meta($user->ID);
            
            foreach ($wishlists as $key => $wishlist) {
                if (strpos($key, '_ucp_wishlist_') === 0) {
                    $page_id = str_replace('_ucp_wishlist_', '', $key);
                    
                    // Create initial version
                    $this->create_wishlist_version($user->ID, $page_id, 'Initial Version');
                }
            }
        }
    }


    public function create_wishlist_version($user_id, $page_id, $version_name = '') {
        global $wpdb;
        
        // Mark all other versions as not current
        $wpdb->update(
            $wpdb->prefix . 'ucp_wishlist_versions',
            array('is_current' => 0),
            array(
                'user_id' => $user_id,
                'page_id' => $page_id
            )
        );
        
        // Get next version number
        $version_number = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(version_number) FROM {$wpdb->prefix}ucp_wishlist_versions 
             WHERE user_id = %d AND page_id = %d",
            $user_id,
            $page_id
        )) + 1;
        
        if (empty($version_name)) {
            $version_name = 'Version ' . $version_number;
        }
        
        // Insert new version
        $wpdb->insert(
            $wpdb->prefix . 'ucp_wishlist_versions',
            array(
                'user_id' => $user_id,
                'page_id' => $page_id,
                'version_number' => $version_number,
                'version_name' => $version_name,
                'is_current' => 1,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ),
            array('%d', '%d', '%d', '%s', '%d', '%s', '%s')
        );
        
        return $wpdb->insert_id;
    }
}

// Initialize database updates
add_action('plugins_loaded', array('UCP_DB_Updates', 'get_instance'));
