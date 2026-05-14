<?php
/**
 * 強制重新加載插件
 * 訪問: https://eji.com.hk/reload_plugin.php
 */

require_once('wp-load.php');

echo "<h1>重新加載 Product Media Carousel Pro</h1>";
echo "<hr>";

// 1. 清除 Elementor 緩存
if (class_exists('\Elementor\Plugin')) {
    echo "<h2>1. 清除 Elementor 緩存</h2>";
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "✅ Elementor 緩存已清除<br>";
}

// 2. 清除 WordPress 對象緩存
echo "<h2>2. 清除 WordPress 緩存</h2>";
wp_cache_flush();
echo "✅ WordPress 緩存已清除<br>";

// 3. 停用並重新啟用插件
echo "<h2>3. 重新啟用插件</h2>";
deactivate_plugins('product-media-carousel-pro/product-media-carousel.php');
echo "⏸️  插件已停用<br>";

activate_plugin('product-media-carousel-pro/product-media-carousel.php');
echo "✅ 插件已重新啟用<br>";

// 4. 檢查類是否存在
echo "<h2>4. 檢查類</h2>";
if (class_exists('PMC_Elementor_Product_Media_Carousel_Widget')) {
    echo "✅ Widget 類已加載<br>";
} else {
    echo "❌ Widget 類仍未加載<br>";
    echo "<p>嘗試手動加載...</p>";
    
    // 手動加載
    if (file_exists(WP_PLUGIN_DIR . '/product-media-carousel-pro/includes/elementor-widgets/product-media-carousel-widget.php')) {
        require_once WP_PLUGIN_DIR . '/product-media-carousel-pro/includes/elementor-widgets/product-media-carousel-widget.php';
        
        if (class_exists('PMC_Elementor_Product_Media_Carousel_Widget')) {
            echo "✅ 手動加載成功<br>";
        } else {
            echo "❌ 手動加載失敗<br>";
        }
    }
}

// 5. 檢查 Elementor widget 註冊
if (class_exists('\Elementor\Plugin')) {
    echo "<h2>5. 檢查 Widget 註冊</h2>";
    $widgets_manager = \Elementor\Plugin::$instance->widgets_manager;
    $widget_types = $widgets_manager->get_widget_types();
    
    if (isset($widget_types['product-media-carousel'])) {
        echo "✅ Widget 已在 Elementor 中註冊<br>";
    } else {
        echo "❌ Widget 未在 Elementor 中註冊<br>";
    }
}

echo "<hr>";
echo "<h2>完成!</h2>";
echo "<p>請返回 Elementor 編輯器並刷新頁面 (Ctrl+F5)</p>";
echo "<p><a href='/wp-admin/admin.php?page=elementor' target='_blank'>前往 Elementor</a></p>";
