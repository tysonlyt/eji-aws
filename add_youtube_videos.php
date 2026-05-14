<?php
/**
 * 添加YouTube視頻到WooCommerce產品
 * 使用自定義字段存儲視頻URL
 */

// 載入WordPress
require_once('wp-load.php');

// 讀取CSV文件
$csv_file = 'products_with_youtube.csv';

if (!file_exists($csv_file)) {
    die("錯誤: 找不到文件 $csv_file\n");
}

echo "==========================================\n";
echo "開始添加YouTube視頻到產品\n";
echo "==========================================\n\n";

$handle = fopen($csv_file, 'r');
$header = fgetcsv($handle); // 跳過標題行

$counter = 0;
$success = 0;
$failed = 0;
$skipped = 0;

while (($data = fgetcsv($handle)) !== FALSE) {
    $counter++;
    
    $product_id = trim($data[0]);
    $product_name = trim($data[1]);
    $youtube_link = trim($data[2]);
    $item_no = trim($data[3]);
    
    // 檢查產品是否存在
    $product = wc_get_product($product_id);
    
    if (!$product) {
        echo "[$counter] ❌ 產品 ID $product_id 不存在\n";
        $failed++;
        continue;
    }
    
    echo "[$counter] 處理產品: $product_name (ID: $product_id)\n";
    echo "        YouTube: $youtube_link\n";
    
    // Product Media Carousel Pro 使用的字段
    // 字段名: _nickx_video_text_url
    // 格式: 序列化數組 array(0 => "youtube_url")
    
    // 檢查是否已有視頻
    $existing_videos = get_post_meta($product_id, '_nickx_video_text_url', true);
    
    if (empty($existing_videos) || !is_array($existing_videos)) {
        // 沒有視頻,創建新數組
        $video_array = array($youtube_link);
    } else {
        // 已有視頻,檢查是否已存在
        if (!in_array($youtube_link, $existing_videos)) {
            // 添加到現有視頻列表
            $video_array = $existing_videos;
            $video_array[] = $youtube_link;
        } else {
            echo "        ⏭️  視頻已存在,跳過\n";
            $skipped++;
            continue;
        }
    }
    
    // 更新視頻URL
    update_post_meta($product_id, '_nickx_video_text_url', $video_array);
    
    // 設置視頻類型標記 (用於WPML)
    update_post_meta($product_id, '_wpml_media_has_media', 1);
    
    echo "        ✅ 成功添加視頻到Product Media Carousel\n";
    $success++;
    
    // 每10個產品顯示進度
    if ($counter % 10 == 0) {
        echo "\n進度: $counter 個產品已處理 (成功: $success, 失敗: $failed)\n\n";
    }
}

fclose($handle);

echo "\n==========================================\n";
echo "完成!\n";
echo "==========================================\n";
echo "總計: $counter 個產品\n";
echo "成功: $success\n";
echo "失敗: $failed\n";
echo "跳過: $skipped\n";
