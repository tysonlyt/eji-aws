<?php
/**
 * Fix Product Media Carousel Database
 * This script will:
 * 1. Create the wp_pmc_product_media table if it doesn't exist
 * 2. Migrate video data from ACF fields to the new table
 */

// Load WordPress
require_once('/home/customer/www/eji.com.hk/public_html/wp-load.php');

if (!defined('ABSPATH')) {
    die('WordPress not loaded');
}

echo "=== Product Media Carousel Database Fix ===\n\n";

// Step 1: Create table
echo "Step 1: Creating database table...\n";

global $wpdb;
$table_name = $wpdb->prefix . 'pmc_product_media';
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `product_id` bigint(20) NOT NULL,
    `variation_id` bigint(20) DEFAULT 0,
    `media_type` varchar(20) NOT NULL DEFAULT 'image',
    `media_value` varchar(500) NOT NULL,
    `display_order` int(11) NOT NULL DEFAULT 0,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_product` (`product_id`),
    KEY `idx_variation` (`variation_id`),
    KEY `idx_order` (`display_order`)
) {$charset_collate}";

$result = $wpdb->query($sql);

if ($result === false) {
    echo "❌ Error creating table: " . $wpdb->last_error . "\n";
    exit(1);
} else {
    echo "✅ Table created successfully!\n\n";
}

// Step 2: Migrate ACF video data
echo "Step 2: Migrating ACF video data...\n";

// Get all products with ACF video field
$products_with_videos = $wpdb->get_results("
    SELECT post_id, meta_value 
    FROM {$wpdb->postmeta} 
    WHERE meta_key = '_nickx_video_text_url' 
    AND meta_value != ''
");

echo "Found " . count($products_with_videos) . " products with videos\n\n";

$migrated = 0;
$skipped = 0;

foreach ($products_with_videos as $product) {
    $product_id = $product->post_id;
    $video_data = maybe_unserialize($product->meta_value);
    
    echo "Processing Product ID: {$product_id}\n";
    
    if (!is_array($video_data)) {
        echo "  ⚠️  Skipped: Invalid video data format\n";
        $skipped++;
        continue;
    }
    
    // Check if already migrated
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$table_name} WHERE product_id = %d AND media_type = 'youtube'",
        $product_id
    ));
    
    if ($existing > 0) {
        echo "  ⚠️  Skipped: Already migrated\n";
        $skipped++;
        continue;
    }
    
    // Extract YouTube video IDs from ACF data
    $order = 1000; // Start after images (which use negative order)
    
    foreach ($video_data as $key => $url) {
        if (empty($url)) {
            continue;
        }
        
        // Extract YouTube video ID
        $video_id = '';
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            $video_id = $matches[1];
        }
        
        if (empty($video_id)) {
            echo "  ⚠️  Could not extract video ID from: {$url}\n";
            continue;
        }
        
        // Insert into database
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'product_id' => $product_id,
                'variation_id' => 0,
                'media_type' => 'youtube',
                'media_value' => $video_id,
                'display_order' => $order++
            ),
            array('%d', '%d', '%s', '%s', '%d')
        );
        
        if ($inserted) {
            echo "  ✅ Migrated video: {$video_id} (from {$url})\n";
            $migrated++;
        } else {
            echo "  ❌ Error inserting video: " . $wpdb->last_error . "\n";
        }
    }
}

echo "\n=== Migration Complete ===\n";
echo "✅ Migrated: {$migrated} videos\n";
echo "⚠️  Skipped: {$skipped} products\n";

// Step 3: Test with product 59630
echo "\n=== Testing Product 59630 ===\n";

$test_media = $wpdb->get_results($wpdb->prepare(
    "SELECT * FROM {$table_name} WHERE product_id = %d ORDER BY display_order ASC",
    59630
));

if (empty($test_media)) {
    echo "⚠️  No media found in database for product 59630\n";
    
    // Check ACF data
    $acf_data = get_post_meta(59630, '_nickx_video_text_url', true);
    echo "\nACF Data for product 59630:\n";
    print_r($acf_data);
} else {
    echo "✅ Found " . count($test_media) . " media items:\n";
    foreach ($test_media as $item) {
        echo "  - Type: {$item->media_type}, Value: {$item->media_value}, Order: {$item->display_order}\n";
    }
}

echo "\n=== Done! ===\n";
echo "Please refresh your Elementor editor (Ctrl+F5) to see the changes.\n";
