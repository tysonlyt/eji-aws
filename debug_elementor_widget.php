<?php
/**
 * 調試 Elementor Widget 執行
 * 在瀏覽器中訪問: https://eji.com.hk/debug_elementor_widget.php?product_id=59630
 */

require_once('wp-load.php');

// 設置產品ID
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 59630;

echo "<h1>Product Media Carousel Debug</h1>";
echo "<hr>";

// 1. 檢查產品
echo "<h2>1. 產品檢查</h2>";
$product = wc_get_product($product_id);
if ($product) {
    echo "✅ 產品存在: " . $product->get_name() . " (ID: $product_id)<br>";
} else {
    echo "❌ 產品不存在<br>";
    exit;
}

// 2. 檢查圖片
echo "<h2>2. 產品圖片</h2>";
$thumbnail_id = $product->get_image_id();
echo "Featured Image ID: " . ($thumbnail_id ? $thumbnail_id : "無") . "<br>";

$gallery_ids = $product->get_gallery_image_ids();
echo "Gallery Images: " . count($gallery_ids) . " 張<br>";

// 3. 檢查視頻
echo "<h2>3. 產品視頻</h2>";
$videos = get_post_meta($product_id, '_nickx_video_text_url', true);
if (!empty($videos) && is_array($videos)) {
    echo "✅ 視頻數量: " . count($videos) . "<br>";
    foreach ($videos as $i => $video) {
        echo "視頻 " . ($i + 1) . ": " . esc_html($video) . "<br>";
    }
} else {
    echo "❌ 沒有視頻<br>";
}

// 4. 檢查 Frontend 類
echo "<h2>4. PMC_Frontend 類檢查</h2>";
if (class_exists('PMC_Frontend')) {
    echo "✅ PMC_Frontend 類存在<br>";
    $frontend = PMC_Frontend::get_instance();
    
    // 獲取所有媒體
    $media_items = $frontend->get_all_media($product_id, $product);
    
    echo "媒體項目數量: " . count($media_items) . "<br>";
    
    if (!empty($media_items)) {
        echo "<h3>媒體項目列表:</h3>";
        echo "<pre>";
        foreach ($media_items as $i => $item) {
            echo "項目 " . ($i + 1) . ":\n";
            echo "  類型: " . $item['type'] . "\n";
            if ($item['type'] === 'image') {
                echo "  URL: " . $item['url'] . "\n";
            } else if ($item['type'] === 'video') {
                echo "  URL: " . $item['url'] . "\n";
                echo "  視頻ID: " . $item['video_id'] . "\n";
            }
            echo "\n";
        }
        echo "</pre>";
    } else {
        echo "❌ 沒有媒體項目<br>";
    }
} else {
    echo "❌ PMC_Frontend 類不存在<br>";
}

// 5. 檢查 Elementor Widget 類
echo "<h2>5. Elementor Widget 類檢查</h2>";
if (class_exists('PMC_Elementor_Product_Media_Carousel_Widget')) {
    echo "✅ Widget 類存在<br>";
} else {
    echo "❌ Widget 類不存在<br>";
}

// 6. 檢查 Elementor 是否已加載
echo "<h2>6. Elementor 檢查</h2>";
if (class_exists('\Elementor\Plugin')) {
    echo "✅ Elementor 已加載<br>";
    
    // 檢查 widget 是否已註冊
    $widgets_manager = \Elementor\Plugin::$instance->widgets_manager;
    $widget_types = $widgets_manager->get_widget_types();
    
    if (isset($widget_types['product-media-carousel'])) {
        echo "✅ Widget 已註冊<br>";
    } else {
        echo "❌ Widget 未註冊<br>";
        echo "已註冊的 widgets:<br>";
        foreach ($widget_types as $name => $widget) {
            if (strpos($name, 'product') !== false || strpos($name, 'media') !== false) {
                echo "  - $name<br>";
            }
        }
    }
} else {
    echo "❌ Elementor 未加載<br>";
}

// 7. 模擬 Widget 渲染
echo "<h2>7. 模擬 Widget 渲染</h2>";
if (class_exists('PMC_Frontend') && !empty($media_items)) {
    echo "<div style='border: 2px solid #ccc; padding: 20px; margin: 20px 0;'>";
    echo "<h3>應該顯示的內容:</h3>";
    
    foreach ($media_items as $item) {
        if ($item['type'] === 'image') {
            echo "<div style='margin: 10px 0;'>";
            echo "<img src='" . esc_url($item['url']) . "' style='max-width: 300px; height: auto;'>";
            echo "</div>";
        } else if ($item['type'] === 'video') {
            echo "<div style='margin: 10px 0;'>";
            echo "<iframe width='300' height='169' src='https://www.youtube.com/embed/" . esc_attr($item['video_id']) . "' frameborder='0' allowfullscreen></iframe>";
            echo "</div>";
        }
    }
    
    echo "</div>";
} else {
    echo "❌ 無法模擬渲染<br>";
}

echo "<hr>";
echo "<p>測試完成</p>";
