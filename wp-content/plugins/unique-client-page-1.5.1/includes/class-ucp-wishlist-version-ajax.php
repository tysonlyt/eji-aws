<?php
/**
 * Handles AJAX requests for wishlist versioning
 * 
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UCP_Wishlist_Version_Ajax {
    private static $instance = null;
    private $version_manager;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->version_manager = UCP_Wishlist_Version_Manager::get_instance();
        $this->init_hooks();
    }
    
    private function init_hooks() {
        add_action('wp_ajax_ucp_save_wishlist_version', array($this, 'save_version'));
        add_action('wp_ajax_ucp_get_wishlist_versions', array($this, 'get_versions'));
        add_action('wp_ajax_ucp_restore_wishlist_version', array($this, 'restore_version'));
        
        // 添加管理界面Ajax处理方法
        // Deprecated: This action is now handled by admin module to maintain nonce compatibility.
        // add_action('wp_ajax_ucp_get_wishlist_version', array($this, 'get_version_details'));
        add_action('wp_ajax_ucp_set_current_wishlist_version', array($this, 'set_current_version'));
    }
    
    public function save_version() {
        check_ajax_referer('ucp-ajax-nonce', 'nonce');
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('User not logged in');
            return;
        }
        
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        $wishlist_data = isset($_POST['wishlist_data']) ? $_POST['wishlist_data'] : array();
        $version_name = isset($_POST['version_name']) ? sanitize_text_field($_POST['version_name']) : '';
        $notes = isset($_POST['notes']) ? sanitize_textarea_field($_POST['notes']) : '';
        
        if (empty($page_id) || empty($wishlist_data)) {
            wp_send_json_error('Missing required parameters');
            return;
        }
        
        $version_id = $this->version_manager->save_version(
            $user_id,
            $page_id,
            $wishlist_data,
            $version_name,
            $notes
        );
        
        if ($version_id) {
            wp_send_json_success(array(
                'version_id' => $version_id,
                'message' => 'Version saved successfully'
            ));
        } else {
            wp_send_json_error('Failed to save version');
        }
    }
    
    public function get_versions() {
        check_ajax_referer('ucp-ajax-nonce', 'nonce');
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('User not logged in');
            return;
        }
        
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        if (empty($page_id)) {
            wp_send_json_error('Page ID is required');
            return;
        }
        
        $versions = $this->version_manager->get_versions($user_id, $page_id);
        wp_send_json_success($versions);
    }
    
    public function restore_version() {
        check_ajax_referer('ucp-ajax-nonce', 'nonce');
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('User not logged in');
            return;
        }
        
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        if (empty($version_id)) {
            wp_send_json_error('Version ID is required');
            return;
        }
        
        $version = $this->version_manager->get_version($version_id);
        if (!$version || $version->user_id != $user_id) {
            wp_send_json_error('Invalid version or access denied');
            return;
        }
        
        $new_version_id = $this->version_manager->restore_version($version_id);
        
        if ($new_version_id) {
            wp_send_json_success(array(
                'version_id' => $new_version_id,
                'message' => 'Version restored successfully',
                'wishlist_data' => $version->wishlist_data
            ));
        } else {
            wp_send_json_error('Failed to restore version');
        }
    }
    
    /**
     * Get single wishlist version details for admin interface
     */
    public function get_version_details() {
        check_ajax_referer('ucp_view_version', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => '权限不足'));
            return;
        }
        
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        if (empty($version_id)) {
            wp_send_json_error(array('message' => '需要版本ID'));
            return;
        }
        
        $version = $this->version_manager->get_version($version_id);
        if (!$version) {
            wp_send_json_error(array('message' => '找不到版本'));
            return;
        }
        
        // 使用输出缓冲来捕获模板输出
        ob_start();
        // 传递$version变量给模板
        include(UCP_PLUGIN_DIR . 'admin/pages/version-details-template.php');
        $html_content = ob_get_clean();
        
        // 返回成功响应，包含HTML内容
        wp_send_json_success(array(
            'html' => $html_content
        ));
    }
    
    /**
     * Set a wishlist version as the current active version
     */
    public function set_current_version() {
        check_ajax_referer('ucp_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied'));
            return;
        }
        
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (empty($version_id) || empty($page_id)) {
            wp_send_json_error(array('message' => 'Version ID and Page ID are required'));
            return;
        }
        
        $result = $this->version_manager->set_current_version($version_id, $page_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Version set as current successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to set version as current'));
        }
    }
}

// Initialize
UCP_Wishlist_Version_Ajax::get_instance();
