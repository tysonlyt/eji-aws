<?php
/**
 * UCP 资源加载器
 * 负责统一管理所有CSS和JS资源
 */
class UCP_Loader {
    private static $instance = null;
    private $version;
    
    private function __construct() {
        $this->version = defined('UCP_VERSION') ? UCP_VERSION : '1.0.0';
    }
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 注册脚本
     */
    public function register_script($handle, $src = '', $deps = array(), $ver = '', $in_footer = false) {
        wp_register_script($handle, $src, $deps, $ver ?: $this->version, $in_footer);
    }
    
    /**
     * 注册样式
     */
    public function register_style($handle, $src = '', $deps = array(), $ver = '', $media = 'all') {
        wp_register_style($handle, $src, $deps, $ver ?: $this->version, $media);
    }
    
    /**
     * 加载模块资源
     */
    public function load_module_resources($module_name, $resources) {
        $module_path = UCP_PLUGIN_URL . 'modules/' . $module_name . '/';
        
        // 加载JS资源
        if (!empty($resources['js'])) {
            $this->register_script(
                'ucp-' . $module_name . '-js',
                $module_path . 'assets/js/ucp-' . $module_name . '.js',
                array('jquery'),
                time(),
                true
            );
            wp_enqueue_script('ucp-' . $module_name . '-js');
        }
        
        // 加载CSS资源
        if (!empty($resources['css'])) {
            $this->register_style(
                'ucp-' . $module_name . '-css',
                $module_path . 'assets/css/ucp-' . $module_name . '.css',
                array(),
                time()
            );
            wp_enqueue_style('ucp-' . $module_name . '-css');
        }
    }
}
