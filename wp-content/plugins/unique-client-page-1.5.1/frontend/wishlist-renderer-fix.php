<?php
/**
 * 愿望清单渲染修复加载器
 * 
 * 这个文件用于修复前端愿望清单显示问题，包括：
 * 1. 添加SKU列
 * 2. 修复图片显示问题
 * 3. 修复Remove按钮点击关闭页面的问题
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 加载修复后的渲染器类
require_once dirname(__FILE__) . '/classes/class-ucp-ui-renderer-fixed.php';

/**
 * 覆盖原始的render_wishlist_items方法
 */
function ucp_override_wishlist_renderer() {
    // 移除原始渲染器的使用点
    global $ucp_ui_renderer;
    
    // 获取修复后的渲染器实例
    $fixed_renderer = UCP_UI_Renderer_Fixed::get_instance();
    
    // 添加过滤器以覆盖原始的渲染方法
    add_filter('ucp_render_wishlist_items', function($html, $items, $wishlist_key, $page_id) use ($fixed_renderer) {
        // 使用修复后的渲染器生成HTML
        return $fixed_renderer->render_wishlist_items($items, $wishlist_key, $page_id);
    }, 10, 4);
    
    // 完全替换原始类的方法，使用更直接的方式
    if (class_exists('UCP_UI_Renderer') && method_exists('UCP_UI_Renderer', 'get_instance')) {
        $original_renderer = UCP_UI_Renderer::get_instance();
        if ($original_renderer) {
            // 直接覆盖原始方法
            $original_renderer->render_wishlist_items = array($fixed_renderer, 'render_wishlist_items');
        }
    }
}

// 在plugins_loaded钩子上调用覆盖函数，确保在插件加载后执行
add_action('plugins_loaded', 'ucp_override_wishlist_renderer', 20);

/**
 * 直接替换整个渲染方法 - 如果过滤器方法不起作用，可以使用此方法
 */
function ucp_replace_renderer_method() {
    // 仅在前端运行
    if (is_admin()) {
        return;
    }
    
    // 定义新的渲染方法
    function render_wishlist_items_fixed($items, $wishlist_key, $page_id) {
        // 获取修复后的渲染器实例并调用其方法
        $fixed_renderer = UCP_UI_Renderer_Fixed::get_instance();
        return $fixed_renderer->render_wishlist_items($items, $wishlist_key, $page_id);
    }
    
    // 使用runkit扩展替换方法 (需要安装PHP runkit扩展)
    if (function_exists('runkit7_method_redefine') && class_exists('UCP_UI_Renderer')) {
        @runkit7_method_redefine(
            'UCP_UI_Renderer',
            'render_wishlist_items',
            '$items, $wishlist_key, $page_id',
            'return render_wishlist_items_fixed($items, $wishlist_key, $page_id);'
        );
    }
}

// 尝试替换方法 (这需要runkit扩展，大多数环境不支持，仅作为备选方案)
// add_action('init', 'ucp_replace_renderer_method', 999);

// 添加调试信息
function ucp_add_wishlist_debug_info() {
    if (current_user_can('manage_options') && isset($_GET['ucp_debug'])) {
        echo '<div style="background:#f8f8f8; border:1px solid #ddd; padding:10px; margin:10px 0; font-family:monospace; font-size:12px;">';
        echo '<h3>UCP Wishlist Debug Info</h3>';
        echo '<p>Fixed renderer loaded: ' . (class_exists('UCP_UI_Renderer_Fixed') ? 'Yes' : 'No') . '</p>';
        echo '</div>';
    }
}
add_action('wp_footer', 'ucp_add_wishlist_debug_info');
