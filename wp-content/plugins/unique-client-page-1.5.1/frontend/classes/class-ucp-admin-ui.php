<?php
/**
 * Admin UI Component
 *
 * 处理所有与管理界面相关的功能，包括菜单、页面渲染和设置
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Admin UI Component
 */
class UCP_Admin_UI {
    /**
     * Class instance
     *
     * @var UCP_Admin_UI
     */
    private static $instance = null;
    
    /**
     * Debug manager reference
     * 
     * @var UCP_Debug_Manager
     */
    private $debug_manager = null;

    /**
     * Version manager reference
     * 
     * @var UCP_Version_Manager
     */
    private $version_manager = null;

    /**
     * UI renderer reference
     * 
     * @var UCP_UI_Renderer
     */
    private $ui_renderer = null;
    
    /**
     * Get the singleton instance
     *
     * @return UCP_Admin_UI instance
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

        // Get reference to version manager
        if (class_exists('UCP_Version_Manager')) {
            $this->version_manager = UCP_Version_Manager::get_instance();
        }

        // Get reference to UI renderer
        if (class_exists('UCP_UI_Renderer')) {
            $this->ui_renderer = UCP_UI_Renderer::get_instance();
        }
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // 添加管理菜单
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // 注册管理页面样式和脚本
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // 添加主菜单
        add_menu_page(
            __('Unique Client Pages', 'unique-client-page'),
            __('Client Pages', 'unique-client-page'),
            'manage_options',
            'ucp-pages',
            array($this, 'render_admin_page'),
            'dashicons-welcome-view-site',
            30
        );
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        // 检查当前用户是否有权限
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'unique-client-page'));
        }
        
        // 处理页面参数
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;
        
        echo '<div class="wrap">';
        echo '<h1>' . __('Unique Client Pages', 'unique-client-page') . '</h1>';
        
        // 根据操作类型显示相应页面
        if ($action === 'edit' && $page_id > 0) {
            $this->render_edit_page($page_id);
        } else {
            $this->render_wishlist_pages_list();
        }
        
        echo '</div>';
    }
    
    /**
     * Render wishlist pages list when no page_id is provided
     */
    public function render_wishlist_pages_list() {
        global $wpdb;
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Rendering wishlist pages list', 'debug', 'admin_ui');
        }
        
        // 创建新页面处理
        if (isset($_POST['ucp_create_page']) && check_admin_referer('ucp_create_page')) {
            $this->handle_create_page_request();
        }
        
        // 获取所有自定义页面
        $table_name = $wpdb->prefix . 'ucp_pages';
        $pages = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY created_at DESC");
        
        // 显示页面列表和创建表单
        echo '<h2>' . __('Client Pages List', 'unique-client-page') . '</h2>';
        
        if (!empty($pages)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>' . __('Page Title', 'unique-client-page') . '</th>';
            echo '<th>' . __('Access Code', 'unique-client-page') . '</th>';
            echo '<th>' . __('Created At', 'unique-client-page') . '</th>';
            echo '<th>' . __('Actions', 'unique-client-page') . '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($pages as $page) {
                echo '<tr>';
                echo '<td>' . esc_html($page->title) . '</td>';
                echo '<td>' . esc_html($page->access_code) . '</td>';
                echo '<td>' . esc_html($page->created_at) . '</td>';
                echo '<td>';
                echo '<a href="' . admin_url('admin.php?page=ucp-pages&action=edit&page_id=' . $page->id) . '" class="button">' . __('Edit', 'unique-client-page') . '</a> ';
                echo '<a href="' . home_url('?ucp_page=' . urlencode($page->access_code)) . '" class="button" target="_blank">' . __('View', 'unique-client-page') . '</a>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>' . __('No client pages found.', 'unique-client-page') . '</p>';
        }
        
        echo '<h3>' . __('Create New Client Page', 'unique-client-page') . '</h3>';
        echo '<form method="post" action="">';
        wp_nonce_field('ucp_create_page');
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th scope="row"><label for="page_title">' . __('Page Title', 'unique-client-page') . '</label></th>';
        echo '<td><input type="text" id="page_title" name="page_title" class="regular-text" required></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="page_content">' . __('Page Content', 'unique-client-page') . '</label></th>';
        echo '<td><textarea id="page_content" name="page_content" class="large-text" rows="5"></textarea></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="access_code">' . __('Access Code', 'unique-client-page') . '</label></th>';
        echo '<td>';
        echo '<input type="text" id="access_code" name="access_code" class="regular-text">';
        echo '<p class="description">' . __('Leave empty to generate random code', 'unique-client-page') . '</p>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '<p class="submit"><input type="submit" name="ucp_create_page" class="button button-primary" value="' . __('Create Page', 'unique-client-page') . '"></p>';
        echo '</form>';
    }
    
    /**
     * Enqueue admin scripts
     *
     * @param string $hook Current admin page
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_ucp-pages') {
            return;
        }
        
        // Enqueue admin styles
        wp_enqueue_style('ucp-admin-style', UCP_PLUGIN_URL . 'assets/css/admin.css', array(), UCP_VERSION);
        
        // Enqueue admin scripts
        wp_enqueue_script('ucp-admin-script', UCP_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), UCP_VERSION, true);
        
        // Pass data to script
        wp_localize_script('ucp-admin-script', 'ucp_admin_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ucp_admin_nonce')
        ));
    }

    /**
     * Render edit page for a specific client page
     *
     * @param int $page_id Page ID
     */
    public function render_edit_page($page_id) {
        global $wpdb;
        
        if ($this->debug_manager) {
            $this->debug_manager->log('Rendering edit page for ID: ' . $page_id, 'debug', 'admin_ui');
        }
        
        // 验证页面ID
        $page_id = absint($page_id);
        if (!$page_id) {
            echo '<div class="notice notice-error"><p>' . __('无效的页面ID。', 'unique-client-page') . '</p></div>';
            return;
        }
        
        // 获取页面数据
        $page = get_post($page_id);
        if (!$page || $page->post_type !== 'page') {
            echo '<div class="notice notice-error"><p>' . __('未找到指定的客户页面。', 'unique-client-page') . '</p></div>';
            return;
        }
        
        // 检查是否为客户页面模板
        $template = get_post_meta($page_id, '_wp_page_template', true);
        if ($template !== 'unique-client-template.php') {
            echo '<div class="notice notice-error"><p>' . __('此页面不是客户页面模板。', 'unique-client-page') . '</p></div>';
            return;
        }
        
        // 处理表单提交
        if (isset($_POST['ucp_update_page']) && check_admin_referer('ucp_update_page_' . $page_id)) {
            $this->handle_update_page_request($page_id);
        }
        
        // 处理版本创建请求
        if (isset($_POST['ucp_create_version']) && check_admin_referer('ucp_create_version_' . $page_id)) {
            $this->handle_create_version_request($page_id);
        }
        
        // 获取页面配置数据
        $page_meta = array(
            'sale_name' => get_post_meta($page_id, '_ucp_sale_name', true),
            'sale_email' => get_post_meta($page_id, '_ucp_sale_email', true),
            'product_limit' => get_post_meta($page_id, '_ucp_product_limit', true) ?: 12,
            'product_columns' => get_post_meta($page_id, '_ucp_product_columns', true) ?: 4,
            'access_code' => get_post_meta($page_id, '_ucp_access_code', true),
        );
        
        // 获取管理员选择的产品
        $admin_products = get_post_meta($page_id, '_ucp_wishlist', true);
        if (!is_array($admin_products)) {
            $admin_products = array();
        }
        
        // 获取用户愿望单
        $user_meta_key = '_ucp_wishlist_' . $page_id;
        $user_products = array();
        
        // 查询所有用户的愿望单
        $users_with_wishlist = $wpdb->get_results($wpdb->prepare(
            "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = %s",
            $user_meta_key
        ));
        
        // 处理用户愿望单数据
        foreach ($users_with_wishlist as $user_data) {
            $products = maybe_unserialize($user_data->meta_value);
            if (is_array($products)) {
                foreach ($products as $product_id) {
                    if (!isset($user_products[$product_id])) {
                        $user_products[$product_id] = 0;
                    }
                    $user_products[$product_id]++;
                }
            }
        }
        
        // 获取版本记录
        $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
        $versions = array();
        
        if ($wpdb->get_var("SHOW TABLES LIKE '{$version_table}'") === $version_table) {
            $versions = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$version_table} WHERE page_id = %d ORDER BY version_number DESC",
                $page_id
            ));
        }
        
        // 显示编辑表单
        echo '<div class="ucp-admin-container">';
        echo '<h2>' . __('编辑客户页面', 'unique-client-page') . ': ' . esc_html($page->post_title) . '</h2>';
        
        // 页面链接和预览按钮
        echo '<div class="ucp-admin-actions">';
        echo '<a href="' . get_permalink($page_id) . '" class="button" target="_blank"><span class="dashicons dashicons-visibility"></span> ' . __('查看页面', 'unique-client-page') . '</a> ';
        echo '<a href="' . admin_url('post.php?post=' . $page_id . '&action=edit') . '" class="button"><span class="dashicons dashicons-edit"></span> ' . __('编辑页面内容', 'unique-client-page') . '</a>';
        echo '</div>';
        
        // 显示标签菜单
        echo '<div class="ucp-admin-tabs">';
        echo '<ul class="ucp-tab-nav">';
        echo '<li class="active"><a href="#tab-settings">' . __('页面设置', 'unique-client-page') . '</a></li>';
        echo '<li><a href="#tab-products">' . __('产品管理', 'unique-client-page') . '</a></li>';
        echo '<li><a href="#tab-versions">' . __('愿望单版本', 'unique-client-page') . '</a></li>';
        echo '</ul>';
        
        // 设置标签内容
        echo '<div id="tab-settings" class="ucp-tab-content active">';
        echo '<form method="post" action="" class="ucp-admin-form">';
        wp_nonce_field('ucp_update_page_' . $page_id);
        echo '<input type="hidden" name="ucp_update_page" value="1">';
        
        echo '<table class="form-table">';
        // 销售人员姓名
        echo '<tr>';
        echo '<th><label for="sale_name">' . __('销售人员姓名', 'unique-client-page') . '</label></th>';
        echo '<td><input type="text" id="sale_name" name="sale_name" value="' . esc_attr($page_meta['sale_name']) . '" class="regular-text"></td>';
        echo '</tr>';
        
        // 销售人员邮箱
        echo '<tr>';
        echo '<th><label for="sale_email">' . __('销售人员邮箱', 'unique-client-page') . '</label></th>';
        echo '<td><input type="email" id="sale_email" name="sale_email" value="' . esc_attr($page_meta['sale_email']) . '" class="regular-text"></td>';
        echo '</tr>';
        
        // 每页产品数
        echo '<tr>';
        echo '<th><label for="product_limit">' . __('每页产品数', 'unique-client-page') . '</label></th>';
        echo '<td><input type="number" id="product_limit" name="product_limit" value="' . esc_attr($page_meta['product_limit']) . '" class="small-text" min="1" max="50"></td>';
        echo '</tr>';
        
        // 产品列数
        echo '<tr>';
        echo '<th><label for="product_columns">' . __('产品列数', 'unique-client-page') . '</label></th>';
        echo '<td>';
        echo '<select id="product_columns" name="product_columns">';
        for ($i = 2; $i <= 6; $i++) {
            $selected = ($i == $page_meta['product_columns']) ? 'selected' : '';
            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        
        // 访问码
        echo '<tr>';
        echo '<th><label for="access_code">' . __('访问码', 'unique-client-page') . '</label></th>';
        echo '<td>';
        echo '<input type="text" id="access_code" name="access_code" value="' . esc_attr($page_meta['access_code']) . '" class="regular-text">';
        echo '<p class="description">' . __('设置访问码以限制页面访问。留空表示公开访问。', 'unique-client-page') . '</p>';
        echo '<button type="button" id="generate-access-code" class="button">' . __('生成随机码', 'unique-client-page') . '</button>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('更新设置', 'unique-client-page') . '"></p>';
        echo '</form>';
        echo '</div>'; // 结束设置标签
        
        // 产品管理标签内容
        echo '<div id="tab-products" class="ucp-tab-content">';
        echo '<h3>' . __('产品管理', 'unique-client-page') . '</h3>';
        echo '<p>' . __('在此管理此客户页面的产品。', 'unique-client-page') . '</p>';
        
        // 将具体的产品管理功能实现在这里
        
        echo '</div>'; // 结束产品管理标签
        
        // 愿望单版本标签内容
        echo '<div id="tab-versions" class="ucp-tab-content">';
        echo '<h3>' . __('愿望单版本历史记录', 'unique-client-page') . '</h3>';
        
        if (empty($versions)) {
            echo '<p>' . __('尚未创建愿望单版本记录。', 'unique-client-page') . '</p>';
        } else {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>' . __('版本', 'unique-client-page') . '</th>';
            echo '<th>' . __('名称', 'unique-client-page') . '</th>';
            echo '<th>' . __('创建时间', 'unique-client-page') . '</th>';
            echo '<th>' . __('创建者', 'unique-client-page') . '</th>';
            echo '<th>' . __('操作', 'unique-client-page') . '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($versions as $version) {
                $user_info = get_userdata($version->created_by);
                $username = $user_info ? $user_info->display_name : __('未知用户', 'unique-client-page');
                
                echo '<tr>';
                echo '<td>' . $version->version_number . '</td>';
                echo '<td>' . ($version->version_name ?: sprintf(__('版本%02d', 'unique-client-page'), $version->version_number)) . '</td>';
                echo '<td>' . date_i18n('Y-m-d H:i', strtotime($version->created_at)) . '</td>';
                echo '<td>' . esc_html($username) . '</td>';
                echo '<td>';
                echo '<a href="#" class="view-version-link button button-small" data-version-id="' . $version->version_id . '">' . __('查看', 'unique-client-page') . '</a> ';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        }
        
        // 添加新版本按钮
        if ($this->version_manager) {
            echo '<div class="version-actions" style="margin-top:15px">';
            echo '<form method="post" action="" class="inline-form">';
            wp_nonce_field('ucp_create_version_' . $page_id);
            echo '<input type="hidden" name="ucp_create_version" value="1">';
            echo '<input type="hidden" name="page_id" value="' . $page_id . '">';
            echo '<input type="submit" name="submit" class="button" value="' . __('创建新版本快照', 'unique-client-page') . '">';
            echo '</form>';
            echo '</div>';
        }
        
        echo '</div>'; // 结束版本标签
        echo '</div>'; // 结束标签容器
        echo '</div>'; // 结束admin-container
        
        // 添加Tab切换JavaScript
        echo '<script type="text/javascript">
        jQuery(document).ready(function($) {
            // Tab切换功能
            $(".ucp-tab-nav li a").on("click", function(e) {
                e.preventDefault();
                var target = $(this).attr("href");
                
                // 激活选项卡
                $(".ucp-tab-nav li").removeClass("active");
                $(this).parent().addClass("active");
                
                // 显示内容
                $(".ucp-tab-content").removeClass("active");
                $(target).addClass("active");
            });
            
            // 生成随机访问码
            $("#generate-access-code").on("click", function() {
                var code = "";
                var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                
                for (var i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                $("#access_code").val(code);
            });
        });
        </script>';
    }
    
    /**
     * Handle update page request
     *
     * @param int $page_id Page ID
     */
    /**
     * Handle create version request
     *
     * @param int $page_id Page ID
     */
    private function handle_create_version_request($page_id) {
        // 验证参数
        $page_id = absint($page_id);
        $user_id = get_current_user_id();
        $version_name = isset($_POST['version_name']) ? sanitize_text_field($_POST['version_name']) : '';
        $notes = isset($_POST['version_notes']) ? sanitize_textarea_field($_POST['version_notes']) : '';
        
        if (!$page_id) {
            add_settings_error('ucp_version_error', 'invalid_page', __('无效的页面ID。', 'unique-client-page'), 'error');
            return;
        }
        
        // 创建新版本
        if ($this->version_manager) {
            $result = $this->version_manager->create_wishlist_version($page_id, $user_id, $version_name, $notes);
            
            if ($result) {
                // 记录日志
                if ($this->debug_manager) {
                    $this->debug_manager->log('Created new wishlist version for page ID: ' . $page_id, 'info', 'admin_ui');
                }
                
                // 显示成功消息
                add_settings_error('ucp_version_created', 'version_created', __('愿望清单版本创建成功。', 'unique-client-page'), 'success');
            } else {
                // 显示错误消息
                add_settings_error('ucp_version_error', 'version_error', __('创建愿望清单版本失败，请检查愿望清单数据是否存在。', 'unique-client-page'), 'error');
                
                // 记录错误
                if ($this->debug_manager) {
                    $this->debug_manager->log('Failed to create wishlist version for page ID: ' . $page_id, 'error', 'admin_ui');
                }
            }
        } else {
            // 显示错误消息
            add_settings_error('ucp_version_error', 'no_manager', __('版本管理器未初始化，无法创建版本。', 'unique-client-page'), 'error');
        }
    }
    
    /**
     * Handle update page request
     *
     * @param int $page_id Page ID
     */
    private function handle_update_page_request($page_id) {
        // 验证并更新页面设置
        $sale_name = isset($_POST['sale_name']) ? sanitize_text_field($_POST['sale_name']) : '';
        $sale_email = isset($_POST['sale_email']) ? sanitize_email($_POST['sale_email']) : '';
        $product_limit = isset($_POST['product_limit']) ? intval($_POST['product_limit']) : 12;
        $product_columns = isset($_POST['product_columns']) ? intval($_POST['product_columns']) : 4;
        $access_code = isset($_POST['access_code']) ? sanitize_text_field($_POST['access_code']) : '';
        
        // 更新页面元数据
        update_post_meta($page_id, '_ucp_sale_name', $sale_name);
        update_post_meta($page_id, '_ucp_sale_email', $sale_email);
        update_post_meta($page_id, '_ucp_product_limit', $product_limit);
        update_post_meta($page_id, '_ucp_product_columns', $product_columns);
        update_post_meta($page_id, '_ucp_access_code', $access_code);
        
        // 记录日志
        if ($this->debug_manager) {
            $this->debug_manager->log('Updated settings for page ID: ' . $page_id, 'info', 'admin_ui');
        }
        
        // 显示成功消息
        add_settings_error('ucp_page_updated', 'page_updated', __('客户页面设置已更新。', 'unique-client-page'), 'success');
    }
    
    /**
     * Handle create page request
     */
    private function handle_create_page_request() {
        global $wpdb;
        
        // 获取表单数据
        $page_title = isset($_POST['page_title']) ? sanitize_text_field($_POST['page_title']) : '';
        $page_content = isset($_POST['page_content']) ? wp_kses_post($_POST['page_content']) : '';
        $access_code = isset($_POST['access_code']) ? sanitize_text_field($_POST['access_code']) : '';
        
        // 验证标题是否为空
        if (empty($page_title)) {
            add_settings_error('ucp_page_title', 'title_required', __('Page title is required.', 'unique-client-page'), 'error');
            return;
        }
        
        // 如果未提供访问码，则生成随机码
        if (empty($access_code)) {
            $access_code = $this->generate_random_access_code();
        } else {
            // 检查访问码是否已存在
            $table_name = $wpdb->prefix . 'ucp_pages';
            $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE access_code = %s", $access_code));
            
            if ($exists > 0) {
                add_settings_error('ucp_access_code', 'code_exists', __('This access code is already in use. Please choose another.', 'unique-client-page'), 'error');
                return;
            }
        }
        
        // 准备插入数据
        $table_name = $wpdb->prefix . 'ucp_pages';
        $result = $wpdb->insert(
            $table_name,
            array(
                'title' => $page_title,
                'content' => $page_content,
                'access_code' => $access_code,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql')
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );
        
        if ($result) {
            // 日志记录
            if ($this->debug_manager) {
                $this->debug_manager->log('Created new client page: ' . $page_title, 'info', 'admin_ui');
            }
            
            // 显示成功消息
            add_settings_error('ucp_page_created', 'page_created', __('Client page created successfully.', 'unique-client-page'), 'success');
            
            // 重定向到编辑页面
            $page_id = $wpdb->insert_id;
            wp_redirect(admin_url('admin.php?page=ucp-pages&action=edit&page_id=' . $page_id));
            exit;
        } else {
            // 显示错误消息
            add_settings_error('ucp_page_error', 'page_error', __('Error creating client page. Please try again.', 'unique-client-page'), 'error');
            
            // 记录错误
            if ($this->debug_manager) {
                $this->debug_manager->log('Error creating client page: ' . $wpdb->last_error, 'error', 'admin_ui');
            }
        }
    }
    
    /**
     * Generate random access code
     *
     * @return string Random access code
     */
    private function generate_random_access_code() {
        $length = 8;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $code;
    }
}
