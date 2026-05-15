<?php
/**
 * Database Handler
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PMC_Database {
    
    /**
     * Table name
     */
    private static $table_name = 'product_media_gallery';
    
    /**
     * Get table name with prefix
     */
    public static function get_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$table_name;
    }
    
    /**
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $table_name = self::get_table_name();
        $charset_collate = $wpdb->get_charset_collate();
        
        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") === $table_name;
        
        if ($table_exists) {
            // Check if variation_id column exists
            $column_exists = $wpdb->get_results("SHOW COLUMNS FROM `{$table_name}` LIKE 'variation_id'");
            
            if (empty($column_exists)) {
                // Add variation_id column to existing table
                $wpdb->query("ALTER TABLE `{$table_name}` ADD COLUMN `variation_id` bigint(20) DEFAULT 0 AFTER `product_id`");
                $wpdb->query("ALTER TABLE `{$table_name}` ADD KEY `idx_variation` (`variation_id`)");
            }
        } else {
            // Create new table with variation_id
            $sql = "CREATE TABLE `{$table_name}` (
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
            
            $wpdb->query($sql);
        }
        
        // Save version
        update_option('pmc_db_version', PMC_VERSION);
        
        return true;
    }
    
    /**
     * Get media items for a product
     * 
     * @param int $product_id Product ID
     * @param int $variation_id Optional variation ID (0 for main product)
     */
    public static function get_product_media($product_id, $variation_id = 0) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE product_id = %d AND variation_id = %d ORDER BY display_order ASC",
            $product_id,
            $variation_id
        ));
        
        return $results ? $results : array();
    }
    
    /**
     * Get all media items for a product including variations
     */
    public static function get_all_product_media($product_id) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table_name} WHERE product_id = %d ORDER BY variation_id, display_order ASC",
            $product_id
        ));
        
        return $results ? $results : array();
    }
    
    /**
     * Get max order for a product
     * 
     * @param int $product_id Product ID
     * @param int $variation_id Optional variation ID (0 for main product)
     * @return int Max order value
     */
    public static function get_max_order($product_id, $variation_id = 0) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        $max_order = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(display_order) FROM {$table_name} WHERE product_id = %d AND variation_id = %d",
            $product_id,
            $variation_id
        ));
        
        return $max_order ? intval($max_order) : 0;
    }
    
    /**
     * Add media item
     * 
     * @param int $product_id Product ID
     * @param string $media_type Media type
     * @param string $media_value Media value
     * @param int $display_order Display order
     * @param int $variation_id Optional variation ID (0 for main product)
     */
    public static function add_media($product_id, $media_type, $media_value, $display_order = 0, $variation_id = 0) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        return $wpdb->insert(
            $table_name,
            array(
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'media_type' => $media_type,
                'media_value' => $media_value,
                'display_order' => $display_order
            ),
            array('%d', '%d', '%s', '%s', '%d')
        );
    }
    
    /**
     * Update media item
     */
    public static function update_media($id, $data) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        $allowed_fields = array('media_type', 'media_value', 'display_order');
        $update_data = array();
        $format = array();
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $update_data[$key] = $value;
                $format[] = ($key === 'display_order') ? '%d' : '%s';
            }
        }
        
        if (empty($update_data)) {
            return false;
        }
        
        return $wpdb->update(
            $table_name,
            $update_data,
            array('id' => $id),
            $format,
            array('%d')
        );
    }
    
    /**
     * Delete media item
     */
    public static function delete_media($id) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        return $wpdb->delete(
            $table_name,
            array('id' => $id),
            array('%d')
        );
    }
    
    /**
     * Delete all media for a product
     */
    public static function delete_product_media($product_id) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        return $wpdb->delete(
            $table_name,
            array('product_id' => $product_id),
            array('%d')
        );
    }
    
    /**
     * Update display order
     * Optimized to use a single query with CASE statement
     */
    public static function update_display_order($product_id, $order_data) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        if (empty($order_data)) {
            return false;
        }
        
        // Build CASE statement for bulk update
        $case_sql = "CASE id ";
        $ids = array();
        
        foreach ($order_data as $order => $id) {
            $id = intval($id);
            $order = intval($order);
            $case_sql .= $wpdb->prepare("WHEN %d THEN %d ", $id, $order);
            $ids[] = $id;
        }
        
        $case_sql .= "END";
        $ids_placeholder = implode(',', array_fill(0, count($ids), '%d'));
        
        // Execute bulk update
        $sql = $wpdb->prepare(
            "UPDATE {$table_name} SET display_order = {$case_sql} WHERE product_id = %d AND id IN ({$ids_placeholder})",
            array_merge(array($product_id), $ids)
        );
        
        return $wpdb->query($sql) !== false;
    }
    
    /**
     * Get media count for a product
     */
    public static function get_media_count($product_id) {
        global $wpdb;
        $table_name = self::get_table_name();
        
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table_name} WHERE product_id = %d",
            $product_id
        ));
    }
}
