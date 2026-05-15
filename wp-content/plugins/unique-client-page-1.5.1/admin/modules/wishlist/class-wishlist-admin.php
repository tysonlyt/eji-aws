<?php
/**
 * Wishlist Management Module
 *
 * Handles all wishlist version management functionality in the admin area
 * @package Unique_Client_Page
 * @since 1.4.0
 */
class UCP_Wishlist_Admin {
    private static $instance = null;
    private $loader;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->loader = UCP_Loader::get_instance();
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Register admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register resources
        add_action('admin_enqueue_scripts', array($this, 'enqueue_resources'));
        
        // Register AJAX handlers
        add_action('wp_ajax_ucp_get_wishlist_version', array($this, 'ajax_get_wishlist_version'));
        add_action('wp_ajax_ucp_save_wishlist_version', array($this, 'ajax_save_wishlist_version'));
        add_action('wp_ajax_ucp_get_wishlist_versions', array($this, 'ajax_get_wishlist_versions'));
        add_action('wp_ajax_ucp_restore_wishlist_version', array($this, 'ajax_restore_wishlist_version'));
        add_action('wp_ajax_ucp_get_version_template', array($this, 'ajax_get_version_template'));
    }
    
    public function add_admin_menu() {
        // 不添加菜单项，以避免与现有的 View Wishlist 重复
        // 保留此方法，但不进行菜单注册
        return;
    }
    
    public function enqueue_resources($hook) {
        // 只在愿望清单管理页面加载资源
        if (strpos($hook, 'ucp-wishlist-manage') === false) { // 使用一致的页面slug
            return;
        }
        
        // 加载愿望清单特定资源
        $this->loader->register_script(
            'ucp-wishlist-admin',
            UCP_PLUGIN_URL . 'admin/modules/wishlist/assets/js/wishlist-admin.js',
            array('jquery', 'ucp-modal-js'),
            UCP_VERSION
        );
        
        // 直接加载模态框资源
        wp_register_script(
            'ucp-modal-js',
            UCP_PLUGIN_URL . 'modules/modal/assets/js/ucp-modal.js',
            array('jquery'),
            UCP_VERSION,
            true
        );
        wp_enqueue_script('ucp-modal-js');
        
        wp_register_style(
            'ucp-modal-css',
            UCP_PLUGIN_URL . 'modules/modal/assets/css/ucp-modal.css',
            array(),
            UCP_VERSION
        );
        wp_enqueue_style('ucp-modal-css');
        
        // 本地化脚本
        wp_localize_script('ucp-wishlist-admin', 'ucpWishlistAdmin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ucp_wishlist_nonce'),
            'template_url' => admin_url('admin-ajax.php') . '?action=ucp_get_version_template'
        ));
        
        // 最后加载感情帅单JS
        wp_enqueue_script('ucp-wishlist-admin');
    }
    
    public function render_management_page() {
        // 检查权限
        if (!current_user_can('manage_options')) {
            return;
        }

        // 处理操作
        if (isset($_GET['action']) && isset($_GET['user_id']) && isset($_GET['product_id'])) {
            if ($_GET['action'] === 'remove' && wp_verify_nonce($_GET['_wpnonce'], 'ucp_remove_wishlist_item')) {
                // 移除愿望清单项
                $this->remove_wishlist_item($_GET['user_id'], $_GET['product_id']);
            }
        }
        
        // 获取愿望清单版本数据
        global $wpdb;
        $wishlists = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions ORDER BY created_at DESC"
        );
        
        // 渲染视图
        include_once plugin_dir_path(__FILE__) . 'views/wishlist-management.php';
    }
    
    /**
     * 获取所有愿望清单数据
     * 
     * @param string $search_user 用户搜索词
     * @param string $search_product 产品搜索词
     * @return array 愿望清单数据
     */
    private function get_all_wishlists($search_user = '', $search_product = '') {
        global $wpdb;
        
        // 获取所有带有愿望清单数据的用户
        $users = $wpdb->get_results(
            "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '_ucp_global_wishlist'"
        );
        
        $wishlist_data = array();
        
        // 遍历每个用户
        foreach ($users as $user) {
            $user_id = $user->user_id;
            
            // 如果有用户搜索，则过滤
            if (!empty($search_user)) {
                $user_info = get_userdata($user_id);
                if (!$user_info || (
                    stripos($user_info->user_login, $search_user) === false && 
                    stripos($user_info->user_email, $search_user) === false &&
                    stripos($user_info->display_name, $search_user) === false
                )) {
                    continue;
                }
            }
            
            // 获取用户的愿望清单
            $wishlist = get_user_meta($user_id, '_ucp_global_wishlist', true);
            
            if (is_array($wishlist) && !empty($wishlist)) {
                // 获取日期元数据
                $wishlist_dates = get_user_meta($user_id, '_ucp_global_wishlist_dates', true);
                if (!is_array($wishlist_dates)) {
                    $wishlist_dates = array();
                }
                
                foreach ($wishlist as $product_id) {
                    // 如果有产品搜索，则过滤
                    if (!empty($search_product)) {
                        $product = wc_get_product($product_id);
                        if (!$product || stripos($product->get_name(), $search_product) === false) {
                            continue;
                        }
                    }
                    
                    $wishlist_data[] = array(
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'date_added' => isset($wishlist_dates[$product_id]) ? $wishlist_dates[$product_id] : time()
                    );
                }
            }
        }
        
        return $wishlist_data;
    }
    
    /**
     * 从愿望清单中移除项目
     * 
     * @param int $user_id 用户ID
     * @param int $product_id 产品ID
     * @return bool 是否成功
     */
    private function remove_wishlist_item($user_id, $product_id) {
        // 获取用户的愿望清单
        $wishlist = get_user_meta($user_id, '_ucp_global_wishlist', true);
        
        // 确保为数组
        if (!is_array($wishlist)) {
            return false;
        }
        
        // 找到并移除产品
        $key = array_search($product_id, $wishlist);
        if ($key !== false) {
            unset($wishlist[$key]);
            
            // 重建索引
            $wishlist = array_values($wishlist);
            update_user_meta($user_id, '_ucp_global_wishlist', $wishlist);
            
            // 更新日期列表
            $wishlist_dates = get_user_meta($user_id, '_ucp_global_wishlist_dates', true);
            if (is_array($wishlist_dates) && isset($wishlist_dates[$product_id])) {
                unset($wishlist_dates[$product_id]);
                update_user_meta($user_id, '_ucp_global_wishlist_dates', $wishlist_dates);
            }
            
            return true;
        }
        
        return false;
    }
    
    public function ajax_get_wishlist_version() {
        // Verify nonce
        check_ajax_referer('ucp_wishlist_nonce', 'security');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
            return;
        }
        
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (!$version_id) {
            wp_send_json_error(array('message' => 'Invalid version ID'));
            return;
        }
        
        global $wpdb;
        $version_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions 
             WHERE version_id = %d",
            $version_id
        ));
        
        if (!$version_data) {
            wp_send_json_error(array('message' => 'Version data not found'));
            return;
        }
        
        // 处理产品数据
        $products = array();
        $wishlist_data = maybe_unserialize($version_data->wishlist_data);
        
        if (is_array($wishlist_data)) {
            foreach ($wishlist_data as $product_id) {
                $product = wc_get_product($product_id);
                if ($product) {
                    $products[] = array(
                        'id' => $product_id,
                        'name' => $product->get_name(),
                        'thumbnail' => get_the_post_thumbnail_url($product_id, 'thumbnail'),
                        'sku' => $product->get_sku(),
                        'price' => $product->get_price_html()
                    );
                }
            }
        }
        
        // 格式化创建时间以确保与前端预期格式一致
        $created_at = new DateTime($version_data->created_at);
        $formatted_date = $created_at->format('Y-m-d H:i:s');
        
        // Return version data
        wp_send_json_success(array(
            'version' => $version_data,
            'version_number' => $version_data->version_number,
            'version_name' => $version_data->version_name,
            'created_at' => $formatted_date,
            'wishlist_data' => $products
        ));
    }
    
    /**
     * Load version details template via AJAX
     * This function loads the template file and outputs its contents
     */
    public function ajax_get_version_template() {
        // Verify nonce
        check_ajax_referer('ucp_wishlist_nonce', 'security');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Insufficient permissions'));
            return;
        }
        
        $version_id = isset($_REQUEST['version_id']) ? intval($_REQUEST['version_id']) : 0;
        $page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : 0;
        
        if (!$version_id) {
            wp_send_json_error(array('message' => 'Invalid version ID'));
            return;
        }
        
        // Capture output buffer to return template contents
        ob_start();
        include plugin_dir_path(__FILE__) . 'views/version-details-template.php';
        $template_content = ob_get_clean();
        
        wp_send_json_success(array(
            'html' => $template_content,
            'version_id' => $version_id
        ));
    }
    
    /**
     * Save a new wishlist version
     */
    public function ajax_save_wishlist_version() {
        check_ajax_referer('ucp_wishlist_nonce', 'nonce');
        
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
        
        global $wpdb;
        
        // Get the highest version number for this page
        $highest_version = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(version_number) FROM {$wpdb->prefix}ucp_wishlist_versions WHERE page_id = %d",
            $page_id
        ));
        
        $new_version = $highest_version ? $highest_version + 1 : 1;
        
        // Reset is_current flag for all versions of this page
        $wpdb->update(
            "{$wpdb->prefix}ucp_wishlist_versions",
            array('is_current' => 0),
            array('page_id' => $page_id),
            array('%d'),
            array('%d')
        );
        
        // Insert the new version
        $result = $wpdb->insert(
            "{$wpdb->prefix}ucp_wishlist_versions",
            array(
                'user_id' => $user_id,
                'page_id' => $page_id,
                'version_number' => $new_version,
                'version_name' => $version_name,
                'wishlist_data' => maybe_serialize($wishlist_data),
                'created_by' => $user_id,
                'created_at' => current_time('mysql'),
                'is_current' => 1,
                'notes' => $notes
            ),
            array('%d', '%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s')
        );
        
        if ($result === false) {
            wp_send_json_error('Failed to save wishlist version');
            return;
        }
        
        wp_send_json_success(array(
            'version_id' => $wpdb->insert_id,
            'version_number' => $new_version,
            'message' => 'Wishlist version saved successfully'
        ));
    }
    
    /**
     * Get all versions for a specific wishlist page
     */
    public function ajax_get_wishlist_versions() {
        check_ajax_referer('ucp_wishlist_nonce', 'security');
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('User not logged in');
            return;
        }
        
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (empty($page_id)) {
            wp_send_json_error('Missing page ID');
            return;
        }
        
        global $wpdb;
        $versions = $wpdb->get_results($wpdb->prepare(
            "SELECT version_id, version_number, version_name, created_at, is_current, notes 
             FROM {$wpdb->prefix}ucp_wishlist_versions 
             WHERE page_id = %d 
             ORDER BY version_number DESC",
            $page_id
        ));
        
        wp_send_json_success(array('versions' => $versions));
    }
    
    /**
     * Restore a wishlist to a previous version
     */
    public function ajax_restore_wishlist_version() {
        check_ajax_referer('ucp_wishlist_nonce', 'security');
        
        $user_id = get_current_user_id();
        if (!$user_id) {
            wp_send_json_error('User not logged in');
            return;
        }
        
        $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        if (empty($version_id) || empty($page_id)) {
            wp_send_json_error('Missing required parameters');
            return;
        }
        
        global $wpdb;
        
        // Get the version data
        $version_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions WHERE version_id = %d AND page_id = %d",
            $version_id, $page_id
        ));
        
        if (!$version_data) {
            wp_send_json_error('Version not found');
            return;
        }
        
        // Reset is_current flag for all versions of this page
        $wpdb->update(
            "{$wpdb->prefix}ucp_wishlist_versions",
            array('is_current' => 0),
            array('page_id' => $page_id),
            array('%d'),
            array('%d')
        );
        
        // Mark this version as current
        $result = $wpdb->update(
            "{$wpdb->prefix}ucp_wishlist_versions",
            array('is_current' => 1),
            array('version_id' => $version_id),
            array('%d'),
            array('%d')
        );
        
        if ($result === false) {
            wp_send_json_error('Failed to restore wishlist version');
            return;
        }
        
        wp_send_json_success(array(
            'message' => 'Wishlist version restored successfully',
            'wishlist_data' => maybe_unserialize($version_data->wishlist_data)
        ));
    }
}

// Initialize module
function ucp_wishlist_admin_init() {
    return UCP_Wishlist_Admin::get_instance();
}

// Initialize on appropriate hook
add_action('plugins_loaded', 'ucp_wishlist_admin_init');
