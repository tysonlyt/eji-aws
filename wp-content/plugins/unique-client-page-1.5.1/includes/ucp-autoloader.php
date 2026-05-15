<?php
/**
 * UCP类文件自动加载器
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * UCP类自动加载函数
 *
 * @param string $class_name 要加载的类名
 * @return void
 */
function ucp_autoload_classes($class_name) {
    // 只处理UCP开头的类
    if (strpos($class_name, 'UCP_') !== 0) {
        return;
    }
    
    // 将类名转换为文件名
    $class_file_name = str_replace('_', '-', strtolower($class_name));
    $class_file_name = 'class-' . substr($class_file_name, 4); // 移除"UCP_"前缀
    
    // 定义可能的文件路径
    $plugin_dir = plugin_dir_path(dirname(__FILE__));
    $paths = [
        $plugin_dir . 'frontend/classes/',
        $plugin_dir . 'admin/classes/',
        $plugin_dir . 'includes/',
        $plugin_dir . 'admin/modules/wishlist/'
    ];
    
    // 尝试查找并加载文件
    foreach ($paths as $path) {
        $file = $path . $class_file_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // 调试输出 - 如果文件没有找到
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('UCP自动加载器: 无法找到 ' . $class_name . ' 的类文件 (' . $class_file_name . '.php)');
    }
}

// 注册自动加载函数
spl_autoload_register('ucp_autoload_classes');
