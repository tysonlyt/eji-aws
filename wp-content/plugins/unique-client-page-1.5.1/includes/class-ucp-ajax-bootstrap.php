<?php
/**
 * 启动AJAX处理
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UCP_Ajax_Bootstrap {
    
    /**
     * 初始化AJAX处理
     */
    public static function init() {
        // 管理版本AJAX动作
        // Deprecated: centralized in admin handler to maintain nonce/permissions consistency
        /* add_action('wp_ajax_ucp_get_wishlist_version', function() {
            // 确保用户有权限
            if (!current_user_can('manage_options')) {
                wp_send_json_error(array('message' => '权限不足'));
                return;
            }
            
            // 验证nonce
            check_ajax_referer('ucp_view_version', 'nonce');
            
            // 获取版本ID
            $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
            if (empty($version_id)) {
                wp_send_json_error(array('message' => '需要版本ID'));
                return;
            }
            
            // 加载版本管理类
            require_once UCP_PLUGIN_DIR . 'includes/class-ucp-wishlist-version-manager.php';
            $version_manager = UCP_Wishlist_Version_Manager::get_instance();
            $version = $version_manager->get_version($version_id);
            
            if (!$version) {
                wp_send_json_error(array('message' => '找不到版本'));
                return;
            }
            
            // 准备响应数据
            ob_start();
            ?>
            <div class="version-details">
                <h3>版本详情</h3>
                <table class="widefat">
                    <tr>
                        <th>版本号</th>
                        <td><?php echo esc_html($version->version_number); ?></td>
                    </tr>
                    <tr>
                        <th>版本名称</th>
                        <td><?php echo esc_html($version->version_name); ?></td>
                    </tr>
                    <tr>
                        <th>创建日期</th>
                        <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($version->created_at)); ?></td>
                    </tr>
                    <?php if (!empty($version->notes)): ?>
                    <tr>
                        <th>备注</th>
                        <td><?php echo esc_html($version->notes); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
                
                <div class="wishlist-content">
                    <h4>愿望清单内容</h4>
                    <?php 
                    $wishlist_data = maybe_unserialize($version->wishlist_data);
                    if (!empty($wishlist_data)): 
                    ?>
                        <div class="wishlist-data-raw">
                            <pre><?php print_r($wishlist_data); ?></pre>
                        </div>
                    <?php else: ?>
                        <p>没有愿望清单数据</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            $html_content = ob_get_clean();
            
            // 返回成功响应
            wp_send_json_success(array(
                'html' => $html_content
            ));
        }); */
    }
}

// 初始化AJAX处理
add_action('init', array('UCP_Ajax_Bootstrap', 'init'));
