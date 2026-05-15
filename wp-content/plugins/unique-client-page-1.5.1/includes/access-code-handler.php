<?php
/**
 * Access Code Handler
 * 
 * 处理特定客户页面的访问码验证功能
 * 
 * @package Unique_Client_Page
 * @since 1.0.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit; // 如果直接访问则退出
}

/**
 * 访问码处理类
 */
class UCP_Access_Code_Handler {
    
    /**
     * 类实例
     */
    private static $instance = null;
    
    /**
     * 获取单例实例
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 构造函数
     */
    private function __construct() {
        // 初始化钩子
        $this->init_hooks();
    }
    
    /**
     * 初始化钩子
     */
    private function init_hooks() {
        // 添加访问码验证 - 降低優先級避免覆蓋模板處理器
        add_filter('template_include', array($this, 'check_page_access'), 5);
        
        // 处理访问码提交
        add_action('template_redirect', array($this, 'handle_access_code_submission'));
    }
    
    /**
     * 处理访问码提交
     */
    public function handle_access_code_submission() {
        if (isset($_POST['submit_access_code']) && isset($_POST['access_code']) && isset($_POST['page_id'])) {
            $submitted_code = sanitize_text_field($_POST['access_code']);
            $page_id = intval($_POST['page_id']);
            
            // 验证页面ID
            if ($page_id <= 0) {
                return;
            }
            
            // 获取页面的访问码
            $access_code = get_post_meta($page_id, '_ucp_access_code', true);
            
            // 验证访问码
            if (!empty($access_code) && $submitted_code === $access_code) {
                // 访问码正确，设置cookie，有效期30天
                $cookie_name = 'ucp_access_' . $page_id;
                setcookie($cookie_name, $submitted_code, time() + (86400 * 30), '/');
                
                // 重定向到同一页面，移除POST参数
                wp_redirect(get_permalink($page_id));
                exit;
            } else {
                // 访问码错误，添加错误提示
                set_transient('ucp_access_error_' . $page_id, true, 30);
            }
        }
    }
    
    /**
     * 验证页面访问权限
     * 
     * @param string $template 当前模板路径
     * @return string 模板路径
     */
    public function check_page_access($template) {
        global $post;
        
        // 检查是否是单页面
        if (!is_singular('page') || !$post) {
            return $template;
        }
        
        // 检查是否使用特定客户页面模板
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        if ($page_template !== 'unique-client-template.php') {
            return $template;
        }
        
        // 获取页面访问码
        $access_code = get_post_meta($post->ID, '_ucp_access_code', true);
        
        // 如果没有设置访问码，允许访问
        if (empty($access_code)) {
            return $template;
        }
        
        // 验证访问码
        if ($this->verify_access_code($post->ID, $access_code)) {
            return $template;
        }
        
        // 显示访问码输入表单
        $this->render_access_code_form($post->ID);
        exit;
    }
    
    /**
     * 验证访问码
     * 
     * @param int $page_id 页面ID
     * @param string $correct_code 正确的访问码
     * @return bool 访问码是否有效
     */
    private function verify_access_code($page_id, $correct_code) {
        // 检查Cookie中是否有有效的访问码
        $cookie_name = 'ucp_access_' . $page_id;
        $user_code = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
        
        // 检查URL参数
        if (isset($_GET['access_code'])) {
            $url_code = sanitize_text_field($_GET['access_code']);
            if ($url_code === $correct_code) {
                // 如果URL参数正确，设置cookie
                setcookie($cookie_name, $url_code, time() + (86400 * 30), '/');
                return true;
            }
        }
        
        // 验证Cookie中的访问码
        if ($user_code === $correct_code) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 渲染访问码输入表单
     * 
     * @param int $page_id 页面ID
     */
    private function render_access_code_form($page_id) {
        $page = get_post($page_id);
        $show_error = get_transient('ucp_access_error_' . $page_id);
        
        // 删除错误提示
        if ($show_error) {
            delete_transient('ucp_access_error_' . $page_id);
        }
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title><?php echo esc_html($page->post_title); ?> - <?php bloginfo('name'); ?></title>
            <?php wp_head(); ?>
            <style>
                .ucp-access-form-container {
                    max-width: 500px;
                    margin: 100px auto;
                    padding: 30px;
                    background-color: #fff;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    text-align: center;
                }
                .ucp-access-form input[type="text"] {
                    width: 100%;
                    padding: 10px;
                    margin: 10px 0;
                    border: 1px solid #ddd;
                    border-radius: 3px;
                }
                .ucp-access-form button {
                    background-color: #0073aa;
                    color: #fff;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 3px;
                    cursor: pointer;
                }
                .ucp-access-error {
                    color: #f44336;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body <?php body_class(); ?>>
            <div class="ucp-access-form-container">
                <h2><?php echo esc_html($page->post_title); ?></h2>
                <p><?php _e('This page requires an access code.', 'unique-client-page'); ?></p>
                
                <form method="post" class="ucp-access-form">
                    <?php if ($show_error): ?>
                        <div class="ucp-access-error"><?php _e('Invalid access code. Please try again.', 'unique-client-page'); ?></div>
                    <?php endif; ?>
                    
                    <input type="hidden" name="page_id" value="<?php echo esc_attr($page_id); ?>">
                    <input type="text" name="access_code" placeholder="<?php _e('Enter access code', 'unique-client-page'); ?>" required>
                    <button type="submit" name="submit_access_code"><?php _e('Submit', 'unique-client-page'); ?></button>
                </form>
            </div>
            <?php wp_footer(); ?>
        </body>
        </html>
        <?php
    }
}

// 初始化访问码处理器
UCP_Access_Code_Handler::get_instance();
