<?php
/**
 * 診斷 Product Media Carousel 顯示問題
 * 檢查產品是否有必要的數據
 */

require_once('wp-load.php');

echo "==========================================\n";
echo "Product Media Carousel 顯示診斷\n";
echo "==========================================\n\n";

// 測試幾個產品
$test_products = [59630, 59510, 55070, 53820, 59355];

foreach ($test_products as $product_id) {
    $product = wc_get_product($product_id);
    
    if (!$product) {
        echo "❌ 產品 ID $product_id 不存在\n\n";
        continue;
    }
    
    echo "產品: {$product->get_name()} (ID: $product_id)\n";
    echo str_repeat("-", 80) . "\n";
    
    // 1. 檢查 Featured Image
    $thumbnail_id = $product->get_image_id();
    echo "1. Featured Image: ";
    if ($thumbnail_id) {
        echo "✅ 有 (ID: $thumbnail_id)\n";
    } else {
        echo "❌ 沒有 - 這可能是問題!\n";
    }
    
    // 2. 檢查 Gallery Images
    $gallery_ids = $product->get_gallery_image_ids();
    echo "2. Gallery Images: ";
    if (!empty($gallery_ids)) {
        echo "✅ 有 " . count($gallery_ids) . " 張\n";
        foreach ($gallery_ids as $i => $img_id) {
            echo "   - 圖片 " . ($i + 1) . ": ID $img_id\n";
        }
    } else {
        echo "⚠️  沒有 Gallery 圖片\n";
    }
    
    // 3. 檢查視頻
    $videos = get_post_meta($product_id, '_nickx_video_text_url', true);
    echo "3. 視頻: ";
    if (!empty($videos) && is_array($videos)) {
        echo "✅ 有 " . count($videos) . " 個\n";
        foreach ($videos as $i => $video) {
            echo "   - 視頻 " . ($i + 1) . ": $video\n";
        }
    } else {
        echo "❌ 沒有視頻\n";
    }
    
    // 4. 檢查產品狀態
    echo "4. 產品狀態: ";
    if ($product->get_status() == 'publish') {
        echo "✅ 已發布\n";
    } else {
        echo "⚠️  " . $product->get_status() . "\n";
    }
    
    // 5. 總結
    echo "\n總結: ";
    if ($thumbnail_id || !empty($gallery_ids)) {
        if (!empty($videos)) {
            echo "✅ 此產品應該可以正常顯示 Carousel (有圖片和視頻)\n";
        } else {
            echo "⚠️  此產品只有圖片,沒有視頻\n";
        }
    } else {
        echo "❌ 此產品沒有圖片! Carousel 無法顯示!\n";
        echo "   解決方案: 為產品添加 Featured Image 或 Gallery 圖片\n";
    }
    
    echo "\n" . str_repeat("=", 80) . "\n\n";
}

echo "\n";
echo "==========================================\n";
echo "Elementor 預覽建議\n";
echo "==========================================\n\n";

echo "如果整個 Carousel 都不顯示,可能的原因:\n\n";

echo "1. ❌ 預覽產品沒有設置\n";
echo "   解決: 在 Elementor 設置中選擇上面任一產品ID\n\n";

echo "2. ❌ 預覽產品沒有圖片\n";
echo "   解決: 選擇有 Featured Image 的產品\n\n";

echo "3. ❌ Widget 放置位置錯誤\n";
echo "   解決: 確保 Product Media Carousel widget 在 Single Product template 中\n\n";

echo "4. ❌ Template 條件設置錯誤\n";
echo "   解決: 檢查 Template 的 Display Conditions\n\n";

echo "5. ❌ 插件衝突或 CSS 問題\n";
echo "   解決: 檢查瀏覽器控制台是否有 JavaScript 錯誤\n\n";

echo "==========================================\n";
echo "推薦的測試步驟\n";
echo "==========================================\n\n";

echo "1. 在 Elementor 中打開 'Single Product 2' template\n";
echo "2. 點擊左下角 ⚙️ → Preview Settings\n";
echo "3. 選擇產品 ID: 59630 (確認有圖片和視頻)\n";
echo "4. 如果還是不顯示,檢查:\n";
echo "   - Widget 是否在正確的位置\n";
echo "   - 瀏覽器控制台是否有錯誤\n";
echo "   - 嘗試切換到其他產品\n";
