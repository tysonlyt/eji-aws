<?php
/**
 * Cleanup Handler for UCP Plugin
 * 
 * Handles cleanup tasks like removing wishlist versions when posts are deleted
 *
 * @package Unique_Client_Page
 * @since 1.3.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UCP_Cleanup_Handler {
    private static $instance = null;
    
    /**
     * Get singleton instance
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
        // Register cleanup hooks
        add_action('before_delete_post', array($this, 'cleanup_wishlist_versions'), 10, 1);
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('UCP Cleanup Handler initialized');
        }
    }
    
    /**
     * Delete wishlist versions when a post/page is deleted
     * 
     * @param int $post_id The post ID being deleted
     */
    public function cleanup_wishlist_versions($post_id) {
        // Verify post type - we only care about posts that might have wishlist versions
        $post_type = get_post_type($post_id);
        if (!in_array($post_type, array('page', 'product', 'ucp-client-page'))) {
            return;
        }
        
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log("UCP: Cleanup wishlist versions for post ID: {$post_id}, type: {$post_type}");
        }
        
        // Delete all wishlist versions for this page
        if (class_exists('UCP_Wishlist_Version_Manager')) {
            $version_manager = UCP_Wishlist_Version_Manager::get_instance();
            $deleted = $version_manager->delete_page_versions($post_id);
            
            if (defined('WP_DEBUG') && WP_DEBUG) {
                if ($deleted !== false) {
                    error_log("UCP: Deleted {$deleted} wishlist version(s) for post ID: {$post_id}");
                } else {
                    error_log("UCP: Failed to delete wishlist versions for post ID: {$post_id}");
                }
            }
        }
    }
}

// Initialize
UCP_Cleanup_Handler::get_instance();
