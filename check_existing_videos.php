<?php
/**
 * 檢查已有視頻的產品
 * 比較現有視頻與要添加的視頻是否相同
 */

require_once('wp-load.php');

$csv_file = 'products_with_youtube.csv';

if (!file_exists($csv_file)) {
    die("錯誤: 找不到文件 $csv_file\n");
}

echo "==========================================\n";
echo "檢查已有視頻的產品\n";
echo "==========================================\n\n";

$handle = fopen($csv_file, 'r');
$header = fgetcsv($handle);

$same_video = 0;
$different_video = 0;
$multiple_videos = 0;
$examples = [];

while (($data = fgetcsv($handle)) !== FALSE) {
    $product_id = trim($data[0]);
    $product_name = trim($data[1]);
    $youtube_link = trim($data[2]);
    
    // 獲取現有視頻
    $existing_videos = get_post_meta($product_id, '_nickx_video_text_url', true);
    
    if (!empty($existing_videos) && is_array($existing_videos)) {
        // 有現有視頻
        if (count($existing_videos) > 1) {
            $multiple_videos++;
        }
        
        if (in_array($youtube_link, $existing_videos)) {
            // 相同視頻
            $same_video++;
            
            if (count($examples) < 5) {
                $examples[] = [
                    'type' => 'same',
                    'id' => $product_id,
                    'name' => $product_name,
                    'new_video' => $youtube_link,
                    'existing_videos' => $existing_videos
                ];
            }
        } else {
            // 不同視頻
            $different_video++;
            
            if (count($examples) < 10) {
                $examples[] = [
                    'type' => 'different',
                    'id' => $product_id,
                    'name' => $product_name,
                    'new_video' => $youtube_link,
                    'existing_videos' => $existing_videos
                ];
            }
        }
    }
}

fclose($handle);

echo "統計結果:\n";
echo "==========================================\n";
echo "相同視頻 (已存在): $same_video\n";
echo "不同視頻 (需要添加): $different_video\n";
echo "有多個視頻的產品: $multiple_videos\n";
echo "\n";

if (!empty($examples)) {
    echo "示例:\n";
    echo "==========================================\n\n";
    
    foreach ($examples as $i => $ex) {
        echo "[" . ($i + 1) . "] " . $ex['name'] . " (ID: " . $ex['id'] . ")\n";
        echo "    類型: " . ($ex['type'] == 'same' ? '✅ 相同視頻' : '⚠️  不同視頻') . "\n";
        echo "    要添加的視頻: " . $ex['new_video'] . "\n";
        echo "    現有視頻:\n";
        foreach ($ex['existing_videos'] as $j => $v) {
            echo "      " . ($j + 1) . ". " . $v . "\n";
        }
        echo "\n";
    }
}

echo "==========================================\n";
echo "結論:\n";
echo "==========================================\n";

if ($different_video > 0) {
    echo "⚠️  發現 $different_video 個產品有不同的視頻!\n";
    echo "這些產品的現有視頻與Excel中的視頻不同。\n";
    echo "需要確認是否要添加為第二個視頻,還是替換現有視頻。\n";
} else {
    echo "✅ 所有已跳過的產品都是因為視頻已存在(相同視頻)。\n";
}
