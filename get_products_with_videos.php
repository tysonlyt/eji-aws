<?php
/**
 * 獲取所有有視頻的產品列表
 * 用於 Elementor 預覽選擇
 */

require_once('wp-load.php');

echo "==========================================\n";
echo "有視頻的產品列表 (用於Elementor預覽)\n";
echo "==========================================\n\n";

// 查詢所有有視頻的產品
global $wpdb;

$products_with_videos = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm.meta_value
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
    WHERE p.post_type = 'product'
    AND p.post_status = 'publish'
    AND pm.meta_key = '_nickx_video_text_url'
    AND pm.meta_value != ''
    ORDER BY p.ID DESC
    LIMIT 50
");

echo "找到 " . count($products_with_videos) . " 個有視頻的產品\n\n";
echo "前50個產品:\n";
echo "==========================================\n\n";

foreach ($products_with_videos as $i => $product) {
    $videos = maybe_unserialize($product->meta_value);
    $video_count = is_array($videos) ? count($videos) : 1;
    
    echo ($i + 1) . ". ID: {$product->ID} | {$product->post_title}\n";
    echo "   視頻數量: {$video_count}\n";
    
    if (is_array($videos)) {
        foreach ($videos as $j => $video) {
            echo "   視頻" . ($j + 1) . ": {$video}\n";
        }
    }
    echo "\n";
}

echo "==========================================\n";
echo "使用方法:\n";
echo "==========================================\n";
echo "1. 在 Elementor 編輯器中打開 'Single Product 2' template\n";
echo "2. 點擊左下角的 ⚙️ 設置圖標\n";
echo "3. 找到 'Preview Settings'\n";
echo "4. 在搜索框中輸入上面的產品ID或名稱\n";
echo "5. 選擇產品後,預覽應該會顯示視頻\n";
