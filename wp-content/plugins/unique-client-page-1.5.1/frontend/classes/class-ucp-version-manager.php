<?php
/**
 * Version Manager Component
 *
 * 管理愿望清单版本的创建、查看和恢复功能
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Version Manager Component
 */
class UCP_Version_Manager {
    /**
     * Class instance
     *
     * @var UCP_Version_Manager
     */
    private static $instance = null;
    
    /**
     * Debug manager reference
     * 
     * @var UCP_Debug_Manager
     */
    private $debug_manager = null;
    
    /**
     * Get the singleton instance
     *
     * @return UCP_Version_Manager instance
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
        // Get reference to debug manager
        if (class_exists('UCP_Debug_Manager')) {
            $this->debug_manager = UCP_Debug_Manager::get_instance();
        }
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Ajax handler registrations (disabled to prevent duplicates)
        // NOTE: These AJAX actions are now centralized in
        // `frontend/classes/class-ucp-ajax-handler.php` (UCP_AJAX_Handler) for frontend
        // and `admin/modules/wishlist/class-wishlist-admin.php` (UCP_Wishlist_Admin) for admin.
        // The following add_action calls are intentionally commented out for de-duplication.
        //
        // add_action('wp_ajax_ucp_create_wishlist_version', array($this, 'create_wishlist_version_ajax'));   // Deprecated: handled by UCP_AJAX_Handler
        // add_action('wp_ajax_ucp_restore_wishlist_version', array($this, 'restore_wishlist_version_ajax')); // Deprecated: handled by UCP_AJAX_Handler
        // add_action('wp_ajax_ucp_get_wishlist_versions', array($this, 'get_wishlist_versions_ajax'));       // Deprecated: handled by UCP_AJAX_Handler
    }
    
    /**
     * Create a new wishlist version record
     *
     * @param int $page_id Page ID
     * @param int $user_id User ID
     * @param string $version_name Version name
     * @param string $notes Version notes
     * @return int|false Version ID if successful, false on failure
     */
    public function create_wishlist_version($page_id, $user_id, $version_name = '', $notes = '') {
        global $wpdb;
        
        $this->log("Creating new wishlist version for page {$page_id}, user {$user_id}");
        
        // Get current wishlist data
        $wishlist_manager = UCP_Wishlist_Manager::get_instance();
        $wishlist_key = $wishlist_manager->get_wishlist_key($page_id, $user_id);
        $wishlist_data = $wishlist_manager->get_wishlist_data($wishlist_key);
        
        if (empty($wishlist_data)) {
            $this->log("Cannot create version - wishlist data is empty", "error");
            return false;
        }
        
        // Prepare wishlist data for storage
        $wishlist_data_json = json_encode($wishlist_data);
        
        // Get current version number
        $current_version = $this->get_current_version_number($page_id, $user_id);
        $new_version = $current_version + 1;
        
        // Reset all versions to not current
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $wpdb->update(
            $versions_table,
            array('is_current' => 0),
            array('page_id' => $page_id, 'user_id' => $user_id),
            array('%d'),
            array('%d', '%d')
        );
        
        // Insert new version record
        $result = $wpdb->insert(
            $versions_table,
            array(
                'user_id' => $user_id,
                'page_id' => $page_id,
                'version_number' => $new_version,
                'version_name' => !empty($version_name) ? $version_name : 'Version ' . $new_version,
                'wishlist_data' => $wishlist_data_json,
                'created_by' => get_current_user_id(),
                'created_at' => current_time('mysql'),
                'is_current' => 1,
                'notes' => $notes
            ),
            array('%d', '%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s')
        );
        
        if ($result === false) {
            $this->log("Failed to insert version record: " . $wpdb->last_error, "error");
            return false;
        }
        
        $version_id = $wpdb->insert_id;
        $this->log("Created wishlist version {$version_id} with number {$new_version}");
        
        return $version_id;
    }
    
    /**
     * Get the current version number for a page and user
     *
     * @param int $page_id Page ID
     * @param int $user_id User ID
     * @return int Current version number (0 if none)
     */
    public function get_current_version_number($page_id, $user_id) {
        global $wpdb;
        
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $version = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT MAX(version_number) FROM {$versions_table} 
                WHERE page_id = %d AND user_id = %d",
                $page_id, $user_id
            )
        );
        
        return $version ? (int) $version : 0;
    }
    
    /**
     * Get all versions for a page and user
     *
     * @param int $page_id Page ID
     * @param int $user_id User ID
     * @return array Array of version objects
     */
    public function get_wishlist_versions($page_id, $user_id) {
        global $wpdb;
        
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $versions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT 
                    version_id, version_number, version_name, created_at, created_by, is_current, notes
                FROM {$versions_table} 
                WHERE page_id = %d AND user_id = %d
                ORDER BY version_number DESC",
                $page_id, $user_id
            )
        );
        
        // Add creator name
        foreach ($versions as &$version) {
            $creator = get_user_by('id', $version->created_by);
            $version->creator_name = $creator ? $creator->display_name : 'Unknown';
        }
        
        return $versions;
    }
    
    /**
     * Get a specific version record
     *
     * @param int $version_id Version ID
     * @return object|false Version object if successful, false on failure
     */
    public function get_wishlist_version($version_id) {
        global $wpdb;
        
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $version = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$versions_table} WHERE version_id = %d",
                $version_id
            )
        );
        
        if (!$version) {
            $this->log("Version {$version_id} not found", "error");
            return false;
        }
        
        return $version;
    }
    
    /**
     * Restore a wishlist version
     *
     * @param int $version_id Version ID to restore
     * @return boolean True if successful, false on failure
     */
    public function restore_wishlist_version($version_id) {
        global $wpdb;
        
        $this->log("Attempting to restore wishlist version {$version_id}");
        
        // Get version record
        $version = $this->get_wishlist_version($version_id);
        if (!$version) {
            return false;
        }
        
        // Set this version as current
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        
        // First, reset all versions to not current
        $wpdb->update(
            $versions_table,
            array('is_current' => 0),
            array('page_id' => $version->page_id, 'user_id' => $version->user_id),
            array('%d'),
            array('%d', '%d')
        );
        
        // Then, set this version as current
        $wpdb->update(
            $versions_table,
            array('is_current' => 1),
            array('version_id' => $version_id),
            array('%d'),
            array('%d')
        );
        
        // Now update the wishlist data
        $wishlist_data = json_decode($version->wishlist_data, true);
        if (empty($wishlist_data)) {
            $this->log("Failed to decode wishlist data for version {$version_id}", "error");
            return false;
        }
        
        $wishlist_manager = UCP_Wishlist_Manager::get_instance();
        $wishlist_key = $wishlist_manager->get_wishlist_key($version->page_id, $version->user_id);
        
        // Update wishlist with version data
        $result = $wishlist_manager->update_wishlist($wishlist_key, $wishlist_data);
        
        $this->log("Restored wishlist version {$version_id} with result: " . ($result ? "success" : "failure"));
        
        return $result;
    }
    
    /**
     * AJAX handler for creating a new wishlist version
     */
    public function create_wishlist_version_ajax() {
        // Verify nonce and permissions
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_version_action')) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'You do not have permission to perform this action'));
            return;
        }
        
        // Get parameters
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $version_name = isset($_POST['version_name']) ? sanitize_text_field($_POST['version_name']) : '';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        if (!$page_id || !$user_id) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        // Create version
        $version_id = $this->create_wishlist_version($page_id, $user_id, $version_name, $notes);
        
        if (!$version_id) {
            wp_send_json_error(array('message' => 'Failed to create version'));
            return;
        }
        
        wp_send_json_success(array(
            'version_id' => $version_id,
            'message' => 'Version created successfully'
        ));
    }
    
    /**
     * AJAX handler for restoring a wishlist version
     */
    public function restore_wishlist_version_ajax() {
        // Verify nonce and permissions
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_version_action')) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'You do not have permission to perform this action'));
            return;
        }
        
        // Get parameters
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        
        if (!$version_id) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        // Restore version
        $result = $this->restore_wishlist_version($version_id);
        
        if (!$result) {
            wp_send_json_error(array('message' => 'Failed to restore version'));
            return;
        }
        
        wp_send_json_success(array(
            'message' => 'Version restored successfully'
        ));
    }
    
    /**
     * AJAX handler for getting wishlist versions
     */
    public function get_wishlist_versions_ajax() {
        // Verify nonce and permissions
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_version_action')) {
            wp_send_json_error(array('message' => 'Security check failed'));
            return;
        }
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'You do not have permission to perform this action'));
            return;
        }
        
        // Get parameters
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$page_id || !$user_id) {
            wp_send_json_error(array('message' => 'Missing required parameters'));
            return;
        }
        
        // Get versions
        $versions = $this->get_wishlist_versions($page_id, $user_id);
        
        wp_send_json_success(array(
            'versions' => $versions
        ));
    }
    
    /**
     * Check if a page has an existing version
     *
     * @param int $page_id Page ID
     * @param int $user_id User ID
     * @return bool True if version exists, false otherwise
     */
    public function has_wishlist_version($page_id, $user_id) {
        global $wpdb;
        
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$versions_table} 
                WHERE page_id = %d AND user_id = %d",
                $page_id, $user_id
            )
        );
        
        return $count > 0;
    }
    
    /**
     * Get the current active version for a page
     *
     * @param int $page_id Page ID
     * @param int $user_id User ID
     * @return object|false Current version object if exists, false otherwise
     */
    public function get_current_version($page_id, $user_id) {
        global $wpdb;
        
        $versions_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $version = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT 
                    version_id, version_number, version_name, created_at, created_by, notes
                FROM {$versions_table} 
                WHERE page_id = %d AND user_id = %d AND is_current = 1
                LIMIT 1",
                $page_id, $user_id
            )
        );
        
        if ($version) {
            $creator = get_user_by('id', $version->created_by);
            $version->creator_name = $creator ? $creator->display_name : 'Unknown';
        }
        
        return $version;
    }
    
    /**
     * Generate version dropdown HTML for admin interface
     *
     * @param int $page_id Page ID
     * @param int $user_id User ID
     * @return string HTML for versions dropdown
     */
    public function get_versions_dropdown_html($page_id, $user_id) {
        $versions = $this->get_wishlist_versions($page_id, $user_id);
        
        if (empty($versions)) {
            return '<p>No versions available</p>';
        }
        
        $html = '<select name="wishlist_version" id="wishlist-version-select">';
        foreach ($versions as $version) {
            $current = $version->is_current ? ' (Current)' : '';
            $html .= sprintf(
                '<option value="%d" %s>%s (v%d)%s - %s</option>',
                $version->version_id,
                $version->is_current ? 'selected="selected"' : '',
                esc_html($version->version_name),
                $version->version_number,
                $current,
                date('Y-m-d H:i', strtotime($version->created_at))
            );
        }
        $html .= '</select>';
        
        return $html;
    }
    
    /**
     * Log a message
     *
     * @param string $message Message to log
     * @param string $level Log level (debug, info, warning, error)
     */
    private function log($message, $level = 'debug') {
        if ($this->debug_manager) {
            $this->debug_manager->log($message, $level, 'version_manager');
        }
    }
}
