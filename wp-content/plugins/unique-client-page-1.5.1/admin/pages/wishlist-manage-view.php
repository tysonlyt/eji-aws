<?php
/**
 * Wishlist Management View Template
 * 
 * Template for displaying the wishlist management page with filtering and version history
 *
 * @package Unique_Client_Page
 * @since 1.3.1
 */

// Check and process view request for specific page ID
// Handle in a secure way

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register AJAX handler function
// Deprecated: duplicate registration disabled; centralized admin handler is used.
/* add_action('wp_ajax_ucp_get_wishlist_version', 'ucp_get_wishlist_version_ajax'); */


// AJAX handler function removed, replaced with static content

/**
 * AJAX handler to mark version as sent to sales
 */
function ucp_mark_version_sent_ajax() {
    check_ajax_referer('ucp_mark_sent', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
    $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
    
    if (empty($version_id) || empty($page_id)) {
        wp_send_json_error(array('message' => 'Missing required parameters'));
        return;
    }
    
    // Save version ID to page metadata
    $result = update_post_meta($page_id, '_wishlist_sent_version', $version_id);
    
    if ($result) {
        wp_send_json_success(array(
            'message' => 'Successfully marked as sent to sales',
            'version_id' => $version_id,
            'page_id' => $page_id
        ));
    } else {
        wp_send_json_error(array('message' => 'Failed to update metadata'));
    }
}

// Register AJAX handler function for marking version as sent
add_action('wp_ajax_ucp_mark_version_sent', 'ucp_mark_version_sent_ajax');

// Debug mode
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Output errors directly to page for debugging
function exception_error_handler($errno, $errstr, $errfile, $errline) {
    echo "<div style='background:#ffdddd; padding:10px; border:1px solid red;'>
        <h3>Error:</h3>
        <p><strong>Type:</strong> [$errno] $errstr</p>
        <p><strong>File:</strong> $errfile on line $errline</p>
    </div>";
}
set_error_handler("exception_error_handler");

// Get current page ID (if provided)
$page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;

try {

// Get search parameters
$search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
$date_from = isset($_GET['date_from']) ? sanitize_text_field($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? sanitize_text_field($_GET['date_to']) : '';

// Get global wpdb object
global $wpdb;

// Set tables
$posts_table = $wpdb->posts;
$postmeta_table = $wpdb->postmeta;
$version_table = $wpdb->prefix . 'ucp_wishlist_versions';

// Check if wishlist versions table exists
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$version_table}'") === $version_table;
if (!$table_exists) {
    // Create table if not exists
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $version_table (
        version_id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        page_id bigint(20) NOT NULL,
        version_number int(11) NOT NULL,
        version_name varchar(255),
        wishlist_data longtext,
        created_by bigint(20),
        created_at datetime NOT NULL,
        is_current tinyint(1) DEFAULT 0,
        notes text,
        PRIMARY KEY (version_id),
        KEY page_id (page_id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    dbDelta($sql);
    echo '<div class="notice notice-info"><p>' . __('Wishlist versions table has been created.', 'unique-client-page') . '</p></div>';
}

// Define base query
$base_query = "SELECT p.ID, p.post_title, p.post_date, 
           (SELECT 
                CASE 
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sale_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sale_name' LIMIT 1)
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sales_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sales_name' LIMIT 1)
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = 'sales_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = 'sales_name' LIMIT 1)
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_sales_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_sales_name' LIMIT 1)
                    ELSE NULL
                END
           ) as sales_name,
           (SELECT COUNT(*) FROM $version_table WHERE page_id = p.ID) as version_count
           FROM $posts_table p";

// Initialize query variables           
$query = '';
$query_processed = false;

if ($page_id > 0) {
    // Handle query for single page
    try {
        $post = get_post($page_id);
        
        if ($post && ($post->post_type == 'ucp-client-page' || $post->post_type == 'page')) {
            // 创建结果对象
            $client_pages = array();
            $page_obj = new stdClass();
            $page_obj->ID = $post->ID;
            $page_obj->post_title = $post->post_title;
            $page_obj->post_date = $post->post_date;
            $page_obj->post_type = $post->post_type;
            
            // Get send target information
            $page_obj->send_to = get_post_meta($post->ID, '_ucp_send_to', true);
            
            // Get version count
            $version_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}ucp_wishlist_versions WHERE page_id = %d",
                $post->ID
            ));
            $page_obj->version_count = $version_count ? $version_count : 0;
            
            // Add object to results array
            $client_pages = array($page_obj);
            
            // Show notice if page type is not ucp-client-page
            if ($post->post_type != 'ucp-client-page') {
                echo "<div class='notice notice-warning'>
                    <p>Note: The current page type is '" . esc_html($post->post_type) . "', which is not the standard client page type.</p>
                </div>";
            }
            
            // 设置标志变量指示已经处理过查询
            $query_processed = true;
        } else {
            // Page doesn't exist or has incorrect type
            echo "<div class='notice notice-error'>
                <p>Error: Could not find a valid client page with ID " . esc_html($page_id) . ".</p>
            </div>";
            // Use empty array, will show no data message later
            $client_pages = array();
            $query_processed = true;
        }
    } catch (Exception $e) {
        // Catch and display any errors
        echo "<div class='notice notice-error'>
            <p>Error occurred while processing page ID " . esc_html($page_id) . ": " . esc_html($e->getMessage()) . "</p>
        </div>";
        // Use empty array, will show no data message later
        $client_pages = array();
        $query_processed = true;
    }
} else {
    // Apply general query - only show pages with wishlist versions
    $query = "SELECT p.ID, p.post_title, p.post_date, 
              (SELECT 
                CASE 
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sale_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sale_name' LIMIT 1)
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sales_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sales_name' LIMIT 1)
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = 'sales_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = 'sales_name' LIMIT 1)
                    WHEN EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_sales_name' LIMIT 1) THEN 
                        (SELECT meta_value FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_sales_name' LIMIT 1)
                    ELSE NULL
                END
              ) as sales_name,
              pm.meta_value as send_to, 
              (SELECT COUNT(*) FROM $version_table WHERE page_id = p.ID) as version_count
              FROM $posts_table p
              LEFT JOIN $postmeta_table pm ON p.ID = pm.post_id AND pm.meta_key = '_ucp_send_to'
              WHERE p.post_status IN ('publish', 'draft')
              AND EXISTS (SELECT 1 FROM $version_table WHERE page_id = p.ID)";
}

// Ensure $query is initialized before adding conditions
if (!$query_processed && !empty($query)) {
    // Add search condition if provided
    if (!empty($search_term)) {
        $search_term = (string)$search_term; // Ensure it's a string
        $query .= $wpdb->prepare(" AND (p.post_title LIKE %s OR EXISTS (SELECT 1 FROM $postmeta_table WHERE post_id = p.ID AND meta_key = '_ucp_sale_name' AND meta_value LIKE %s))", 
                                '%' . $wpdb->esc_like($search_term) . '%', 
                                '%' . $wpdb->esc_like($search_term) . '%');
    }

    // Add date range condition if provided
    if (!empty($date_from) && !empty($date_to)) {
        $date_from = (string)$date_from; // Ensure it's a string
        $date_to = (string)$date_to; // Ensure it's a string
        $query .= $wpdb->prepare(" AND p.post_date BETWEEN %s AND %s", 
                                $date_from . ' 00:00:00', 
                                $date_to . ' 23:59:59');
    } elseif (!empty($date_from)) {
        $date_from = (string)$date_from; // Ensure it's a string
        $query .= $wpdb->prepare(" AND p.post_date >= %s", $date_from . ' 00:00:00');
    } elseif (!empty($date_to)) {
        $date_to = (string)$date_to; // Ensure it's a string
        $query .= $wpdb->prepare(" AND p.post_date <= %s", $date_to . ' 23:59:59');
    }

    // Order by post date
    $query .= " ORDER BY p.post_date DESC";

}

// Initialize client pages array
$client_pages = isset($client_pages) && is_array($client_pages) ? $client_pages : array();

try {
    // If query has not been processed, execute normal query
    if (empty($query_processed) && !empty($query)) {
        // Execute query and get client pages
        $client_pages = $wpdb->get_results($query);
        
        if ($wpdb->last_error) {
            throw new Exception($wpdb->last_error);
        }
    }
    
    // Ensure initialized
    $client_pages = is_array($client_pages) ? $client_pages : array();
    
    // Display number of results
    $page_count = count($client_pages);
    echo "<div style='background:#e6ffe6; padding:10px; margin:10px 0; border:1px solid green;'>
        <p>Query successful: Found $page_count pages</p>
    </div>";
} catch (Exception $e) {
    echo "<div style='background:#ffdddd; padding:10px; margin:10px 0; border:1px solid red;'>
        <h3>Query error:</h3>
        <p>" . esc_html($e->getMessage()) . "</p>
    </div>";
    // Create empty array to avoid errors in subsequent code
    $client_pages = array();
}

// Start the page output
?>
<div class="wrap">
    <h1><?php _e('Wishlist Management', 'unique-client-page'); ?></h1>
    
    <style>
    /* Version row styles */
    .version-row {
        cursor: pointer;
    }
    .version-row:hover {
        background-color: #f0f0f0;
    }
    .version-row td {
        padding: 8px;
    }
    .version-row:nth-child(odd) {
        background-color: #f9f9f9;
    }
    .version-row:nth-child(odd):hover {
        background-color: #f0f0f0;
    }
    .version-sent td {
        background-color: #dff0d8 !important;
    }
    
    /* Version details modal styles */
    .version-details {
        padding: 20px;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .version-details h3 {
        font-size: 18px;
        margin-bottom: 15px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
    }
    
    .version-meta {
        margin-bottom: 15px;
    }
    
    .version-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        font-weight: 500;
    }
    
    .version-status.sent {
        background-color: #dff0d8;
        color: #3c763d;
        border: 1px solid #d6e9c6;
    }
    
    .ucp-modal .version-info-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .ucp-modal .version-primary-info {
        flex: 1 1 400px;
    }
    
    .ucp-modal .version-secondary-info {
        flex: 1 1 300px;
    }
    
    .ucp-modal .version-notes {
        background-color: #fafafa;
        padding: 10px;
        border: 1px solid #eee;
        border-radius: 3px;
    }
    
    .ucp-modal .version-notes h4 {
        margin-top: 0;
        margin-bottom: 5px;
    }
    
    .ucp-modal .notes-content p {
        margin-top: 0;
    }
    
    .ucp-modal .wishlist-content {
        margin-top: 20px;
    }
    
    .ucp-modal .wishlist-content h4 {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #eee;
    }
    
    .ucp-modal .wishlist-items-table-container {
        max-height: 400px;
        overflow-y: auto;
        margin-bottom: 20px;
        border: 1px solid #eee;
    }
    
    .ucp-modal .wishlist-items-table th {
        position: sticky;
        top: 0;
        background-color: #fff;
        box-shadow: 0 1px 0 0 #ddd;
        z-index: 10;
    }
    
    .ucp-modal .item-properties {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .item-property {
        padding: 2px 0;
        border-bottom: 1px dotted #eee;
    }
    
    .version-actions {
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        text-align: right;
    }
    
    .version-actions .button {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Debug area styles */
    .debug-area {
        margin-top: 30px;
        padding: 15px;
        background-color: #f5f5f5;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    
    .debug-area h3 {
        margin-top: 0;
        margin-bottom: 10px;
    }
    
    .debug-output {
        max-height: 300px;
        overflow-y: auto;
        background-color: #fff;
        padding: 10px;
        border: 1px solid #ddd;
        font-family: monospace;
        white-space: pre-wrap;
    }
    
    /* Rebuilt table styles to ensure fixed width and no overflow */
    .wrap {
        margin-right: 15px;
        box-sizing: border-box;
    }
    
    /* Map layer styles */
    .widefat {
        clear: both;
        width: 100% !important;
        max-width: 100% !important;
        table-layout: fixed !important;
        margin: 0 !important;
        box-sizing: border-box;
        word-wrap: break-word;
    }
    
    /* Main table styles to fix width issue */
    .wp-list-table {
        width: 98% !important;
        max-width: 98% !important;
        table-layout: fixed !important;
        border-spacing: 0;
        border-collapse: separate;
        margin-right: 10px;
        box-sizing: border-box;
        table-layout: fixed;
        display: table;
    }
    
    .wp-list-table th {
        text-align: left;
        padding: 8px;
        font-weight: bold;
        background-color: #f1f1f1;
    }
    
    .wp-list-table td {
        padding: 10px 8px;
        vertical-align: top;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    /* Column width constraints - reset ratios */
    .wp-list-table td,
    .wp-list-table th {
        padding: 8px 5px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        overflow: hidden;
    }
    
    /*  Set ID column */
    .wp-list-table th:nth-child(1), 
    .wp-list-table td:nth-child(1) {
        width: 5%;
    }
    
    /* Set company name column */
    .wp-list-table th:nth-child(2),
    .wp-list-table td:nth-child(2) {
        width: 20%;
    }
    
    /* Set sales name column */
    .wp-list-table th:nth-child(3),
    .wp-list-table td:nth-child(3) {
        width: 15%;
    }
    
    /* Set date column */
    .wp-list-table th:nth-child(4),
    .wp-list-table td:nth-child(4) {
        width: 10%;
    }
    
    /* Set status column */
    .wp-list-table th:nth-child(5),
    .wp-list-table td:nth-child(5) {
        width: 15%;
    }
    
    /* Set version column - ensure this column content does not overflow */
    .wp-list-table th:nth-last-child(1),
    .wp-list-table td:nth-last-child(1) {
        width: 35%;
        overflow: hidden;
    }
    
    /* Set version column styles - completely reset */
    .version-list-direct {
        display: block;
        padding: 3px 0;
        max-height: 150px;
        overflow-y: auto;
        width: 100%;
        box-sizing: border-box;
    }
    
    .version-item {
        display: block;
        margin-bottom: 3px;
        background: #f9f9f9;
        padding: 3px 5px;
        border-radius: 3px;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }
    
    .version-item:hover {
        background: #f0f0f0;
    }
    
    .version-item a {
        text-decoration: none;
        color: #0073aa;
        font-weight: 500;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 95%;
    }
    
    .version-item a:hover {
        color: #00a0d2;
    }
    
    .sent-mark {
        color: #46b450;
        margin-left: 5px;
        font-size: 11px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-style: italic;
    }
    </style>
    
    <!-- Search and Filter Section -->
    <div class="tablenav top">
        <form method="get">
            <input type="hidden" name="page" value="ucp-wishlist-manage">
            
            <div class="alignleft actions">
                <input type="search" name="search" value="<?php echo esc_attr($search_term); ?>" placeholder="<?php _e('Search company or sales name...', 'unique-client-page'); ?>">
                
                <label for="date-from"><?php _e('From:', 'unique-client-page'); ?></label>
                <input type="date" id="date-from" name="date_from" value="<?php echo esc_attr($date_from); ?>">
                
                <label for="date-to"><?php _e('To:', 'unique-client-page'); ?></label>
                <input type="date" id="date-to" name="date_to" value="<?php echo esc_attr($date_to); ?>">
                
                <input type="submit" class="button" value="<?php _e('Filter', 'unique-client-page'); ?>">
                <?php if (!empty($search_term) || !empty($date_from) || !empty($date_to)): ?>
                <a href="<?php echo admin_url('admin.php?page=ucp-wishlist-manage'); ?>" class="button"><?php _e('Reset', 'unique-client-page'); ?></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    
    <!-- Wishlist Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('ID', 'unique-client-page'); ?></th>
                <th><?php _e('Company Name', 'unique-client-page'); ?></th>
                <th><?php _e('Sales Name', 'unique-client-page'); ?></th>
                <th><?php _e('Created Date', 'unique-client-page'); ?></th>
                <th><?php _e('Status', 'unique-client-page'); ?></th>
                <th><?php _e('Versions', 'unique-client-page'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($client_pages)): ?>
            <tr>
                <td colspan="6"><?php _e('No client pages found.', 'unique-client-page'); ?></td>
            </tr>
            <?php else: ?>
                <?php foreach ($client_pages as $page): ?>
                <?php
                    // Get wishlist sent status
                    $is_sent = get_post_meta($page->ID, '_wishlist_sent', true);
                    $sent_date = get_post_meta($page->ID, '_wishlist_sent_date', true);
                    
                    // Get all versions for this page, sorted by creation time (newest first)
                    $sql = $wpdb->prepare(
                        "SELECT * FROM $version_table WHERE page_id = %d ORDER BY created_at DESC",
                        $page->ID
                    );
                    $versions = $wpdb->get_results($sql);
                    
                    // Remove duplicate version numbers, keep only the latest record for each version number
                    // Reorganize array using version ID as key
                    $ordered_versions = array();
                    
                    // Sort by creation date first
                    usort($versions, function($a, $b) {
                        return strtotime($b->created_at) - strtotime($a->created_at);
                    });
                    
                    // Reassign version numbers, ensure they are numbered from latest to oldest
                    $new_versions = array();
                    $count = count($versions);
                    
                    foreach ($versions as $index => $version) {
                        $version_clone = clone $version;
                        $version_clone->display_number = $count - $index;
                        $new_versions[] = $version_clone;
                    }
                    
                    // Sort by new assigned version number from highest to lowest
                    usort($new_versions, function($a, $b) {
                        return $b->display_number - $a->display_number;
                    });
                    
                    $versions = $new_versions;
                ?>
                <tr>
                    <td><?php echo esc_html($page->ID); ?></td>
                    <td>
                        <strong><?php echo esc_html($page->post_title); ?></strong>
                    </td>
                    <td><?php echo !empty($page->sales_name) ? esc_html($page->sales_name) : '-'; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($page->post_date)); ?></td>
                    <td>
                        <?php if ($is_sent): ?>
                            <span class="dashicons dashicons-yes" style="color:green;"></span> 
                            <?php _e('Sent', 'unique-client-page'); ?>
                            <?php if ($sent_date): ?>
                                <br><small><?php echo date('d/m/Y', strtotime($sent_date)); ?></small>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="dashicons dashicons-no" style="color:red;"></span> 
                            <?php _e('Not Sent', 'unique-client-page'); ?>
                        <?php endif; ?>
                    </td>
                    <td>

                        <?php if (!empty($versions)): ?>
                            <div class="version-list-direct">
                                <?php foreach ($versions as $key => $version): ?>
                                    <?php 
                                        // Check if necessary properties exist
                                        $has_display_number = isset($version->display_number);
                                        $has_version_number = isset($version->version_number);
                                        
                                        // Use available version number
                                        $version_num = $has_display_number ? $version->display_number : 
                                                     ($has_version_number ? $version->version_number : $key + 1);
                                        $version_num_formatted = sprintf('%02d', $version_num);
                                        
                                        // Format creation date
                                        $created_date = isset($version->created_at) ? 
                                            (new DateTime($version->created_at))->setTimezone(new DateTimeZone('Asia/Shanghai'))->format('d/m/Y') : (new DateTime())->setTimezone(new DateTimeZone('Asia/Shanghai'))->format('d/m/Y');
                                        
                                        // Check if this version is the version sent to sales
                                        $sent_version = get_post_meta($page->ID, '_wishlist_sent_version', true);
                                        $is_sent_version = !empty($sent_version) && $sent_version == $version->version_id;
                                    ?>
                                    <div class="version-item">
                                        <a href="#" class="view-version" data-id="<?php echo esc_attr($version->version_id); ?>" data-page="<?php echo esc_attr($page->ID); ?>">
                                            version-<?php echo $version_num_formatted; ?> <?php echo esc_html($created_date); ?>
                                            <?php if ($is_sent_version): ?>
                                                <span class="sent-mark">(the date send to sales)</span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <em><?php _e('No versions', 'unique-client-page'); ?></em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Version View Modal - Static version, no AJAX loading required -->
<div id="ucp-wishlist-version-modal" class="ucp-modal">
    <div class="ucp-modal-overlay"></div>
    <div class="ucp-modal-container">
        <div class="ucp-modal-header">
            <div class="ucp-header-left">
                <h2 class="ucp-modal-title"><?php _e('Wishlist Version Details', 'unique-client-page'); ?></h2>
            </div>
            <div class="ucp-header-right">
                <button type="button" class="ucp-modal-close" aria-label="<?php _e('Close', 'unique-client-page'); ?>">
                    <span class="dashicons dashicons-no-alt"></span>
                </button>
            </div>
        </div>
        
        <div class="ucp-modal-content-wrapper">
            <div class="ucp-modal-content">
                <?php
                // Pre-render all version content
                global $wpdb;
                $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
                $all_versions = $wpdb->get_results("SELECT * FROM {$version_table}");
                
                if (!empty($all_versions)) :
                    foreach ($all_versions as $version) :
                        // Get page information
                        $post = null;
                        if (!empty($version->page_id)) {
                            $post = get_post($version->page_id);
                        }
                        
                        // Get sales name
                        $sales_name = get_post_meta($version->page_id, '_ucp_sale_name', true);
                        
                        // Check if sent to sales
                        $sent_to_sales = get_post_meta($version->page_id, '_wishlist_sent_version', true);
                        $is_sent = !empty($sent_to_sales) && $sent_to_sales == $version->version_id;
                ?>
                <div id="version-content-<?php echo esc_attr($version->version_id); ?>" class="version-content" style="display:none;">
                    <div class="version-details-content">
                        <div class="version-header">
                            <h3 class="version-title"><?php echo sprintf('Version #%s Details', esc_html($version->version_number)); ?></h3>
                            <?php if ($is_sent): ?>
                                <div class="version-status sent">
                                    <span class="dashicons dashicons-yes-alt"></span> Sent to Sales
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="version-info-container">
                            <div class="version-primary-info">
                                <table class="widefat">
                                    <tr>
                                        <th>Version Number</th>
                                        <td><?php echo esc_html($version->version_number); ?></td>
                                    </tr>
                                    <?php if (!empty($version->version_name)): ?>
                                    <tr>
                                        <th>Version Name</th>
                                        <td><?php echo esc_html($version->version_name); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>Created Date</th>
                                        <td><?php 
                                            // Set timezone to UTC+8
                                            $timezone = new DateTimeZone('Asia/Shanghai');
                                            $date = new DateTime($version->created_at);
                                            $date->setTimezone($timezone);
                                            echo $date->format('d/m/Y H:i'); 
                                        ?></td>
                                    </tr>
                                    <?php if ($post): ?>
                                    <tr>
                                        <th>Page Title</th>
                                        <td>
                                            <a href="<?php echo get_edit_post_link($version->page_id); ?>" target="_blank">
                                                <?php echo esc_html($post->post_title); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($sales_name)): ?>
                                    <tr>
                                        <th>Sales Name</th>
                                        <td><?php echo esc_html($sales_name); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            
                            <div class="version-secondary-info">
                                <?php if ($is_sent): ?>
                                <div class="version-sent-info">
                                    <h4>Send Details</h4>
                                    <div class="sent-info-content">
                                        <p class="sent-time"><strong>Sent by email on</strong> <?php 
                                            // Set timezone to UTC+8
                                            $timezone = new DateTimeZone('Asia/Shanghai');
                                            $date = new DateTime($version->created_at);
                                            $date->setTimezone($timezone);
                                            echo $date->format('d/m/Y H:i'); 
                                        ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($version->notes)): ?>
                                <div class="version-notes">
                                    <h4>Notes</h4>
                                    <div class="notes-content">
                                        <?php echo wpautop(esc_html($version->notes)); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php 
                        $wishlist_data = array();
                        $product_count = 0;
                        
                        try {
                            // Parse wishlist data first
                            $wishlist_data = maybe_unserialize($version->wishlist_data);
                            
                            // If it's a string, try to parse as JSON
                            if (is_string($wishlist_data)) {
                                if (strpos($wishlist_data, '[') === 0 || strpos($wishlist_data, '{') === 0) {
                                    $json_data = json_decode($wishlist_data, true);
                                    if (json_last_error() === JSON_ERROR_NONE && !empty($json_data)) {
                                        $wishlist_data = $json_data;
                                    }
                                }
                            }
                            
                            // Ensure data is in valid format
                            if (!isset($wishlist_data) || $wishlist_data === false) {
                                $wishlist_data = array();
                            }
                            
                            // Count products after data is properly parsed
                            if (is_array($wishlist_data) && !empty($wishlist_data)) {
                                $product_count = count($wishlist_data);
                            }
                            
                        } catch (Exception $e) {
                            // Catch exception but don't interrupt processing
                            $error_message = $e->getMessage();
                            $wishlist_data = array();
                            $product_count = 0;
                        }
                        ?>
                        <div class="wishlist-content">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <h4 style="margin: 0;">Wishlist Content</h4>
                                <span class="product-count" style="font-size: 13px; color: #666;">Selected <?php echo intval($product_count); ?> products</span>
                            </div>
                            <?php 
                            // Display wishlist content
                            if (empty($wishlist_data)): ?>
                            
                                <p class="no-data">No wishlist data available.</p>
                                
                            <?php elseif (is_array($wishlist_data) || is_object($wishlist_data)): ?>
                            
                                <div class="wishlist-items-table-container">
                                    <table class="widefat wishlist-items-table">
                                        <thead>
                                            <tr>
                                                <th>NO.</th>
                                                <th>SKU</th>
                                                <th>Product Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        $counter = 1;
                                        foreach ($wishlist_data as $key => $product_id): 
                                            // Initialize variables
                                            $sku = '-';
                                            $product_name = '-';
                                            
                                            try {
                                                // Process product ID
                                                if (is_array($product_id) && isset($product_id['id'])) {
                                                    $product_id = $product_id['id'];
                                                } elseif (is_object($product_id) && isset($product_id->id)) {
                                                    $product_id = $product_id->id;
                                                } elseif (is_object($product_id)) {
                                                    $product_id = '[Object]';
                                                }
                                                
                                                // Try to get product information from WooCommerce
                                                if (function_exists('wc_get_product') && is_numeric($product_id)) {
                                                    $product = wc_get_product((int)$product_id);
                                                    if ($product) {
                                                        $sku = $product->get_sku();
                                                        if (empty($sku)) $sku = '-';
                                                        $product_name = $product->get_name();
                                                    }
                                                }
                                                
                                                // If still no product information, try to get from posts table
                                                if (empty($product_name) || $product_name === '-') {
                                                    $post_info = get_post($product_id);
                                                    if ($post_info) {
                                                        $product_name = $post_info->post_title;
                                                        // Try to get SKU, may be custom field
                                                        $meta_sku = get_post_meta($product_id, '_sku', true);
                                                        if (!empty($meta_sku)) {
                                                            $sku = $meta_sku;
                                                        }
                                                    }
                                                }
                                                
                                                // If still no product name, display ID
                                                if (empty($product_name) || $product_name === '-') {
                                                    $product_name = 'Product #' . $product_id;
                                                }
                                            } catch (Exception $e) {
                                                // Exception handling
                                                $sku = '-';
                                                $product_name = '[Error: ' . $e->getMessage() . ']';
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo esc_html($counter++); ?></td>
                                                <td><?php echo esc_html($sku); ?></td>
                                                <td><?php echo esc_html($product_name); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                            <?php else: ?>
                            
                                <div class="wishlist-raw-data">
                                    <p>Raw Wishlist Data:</p>
                                    <pre><?php echo is_string($wishlist_data) ? esc_html($wishlist_data) : esc_html(print_r($wishlist_data, true)); ?></pre>
                                </div>
                                
                            <?php endif; ?>
                        </div>
                        
                        <div class="version-modal-footer">
                            <?php if ($is_sent): ?>
                            <div class="version-actions">
                                <button type="button" class="button button-secondary resend-version" data-version="<?php echo esc_attr($version->version_id); ?>" data-page="<?php echo esc_attr($version->page_id); ?>">重发此版本</button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="no-versions-found">
                    <p>No version data available</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Wishlist Version Modal Styles - Reference product selector modal styles - Enhanced display */
.ucp-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 999999;
    background-color: transparent;
    overflow: hidden;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.ucp-modal.show {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.ucp-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.ucp-modal-container {
    position: absolute;        
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: 95vw !important;
    max-width: 1200px;
    background-color: #fff;
    border-radius: 4px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.ucp-modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e5e5e5;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    position: relative;
}

.ucp-header-right {
    display: flex;
    align-items: center;
}

button.ucp-modal-close {
    background: transparent;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

button.ucp-modal-close:hover {
    background-color: #f0f0f0;
    color: #000;
}

button.ucp-modal-close .dashicons {
    font-size: 20px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ucp-modal-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
    padding: 0;
    line-height: 1.4;
}

.ucp-modal-content-wrapper {
    flex: 1;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    height: calc(100% - 60px); /* 只需要减去头部的高度 */
}

.ucp-modal-content {
    padding: 20px !important;
    height: 100% !important;
    width: 100% !important;
    overflow-y: auto !important;
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    margin: 0px !important;
    max-height: 100% !important;
    max-width: 100% !important;
}

/* 已移除模态框底部 */

/* 版本内容样式 */
.version-content {
    display: none;
    width: 100%;
    height: 100%;
    flex: 1;
}

.version-details-content {
    padding: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.version-info-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    width: 100%;
}

.version-primary-info, 
.version-secondary-info {
    flex: 1;
    min-width: 300px;
}

.wishlist-content {
    margin-top: 20px;
    width: 80%;
    margin: 0 auto;
}

.wishlist-items-table-container {
    width: 100%;
    overflow-x: auto;
}

/* 加载指示器 */
.loading-indicator {
    text-align: center;
    padding: 30px;
}

.spin {
    animation: spin 2s infinite linear;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#version-modal .ucp-modal-body {
    padding: 15px 0;
}

/* Legacy modal styles - 保留向后兼容性 */
.ucp-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
}

/* 动态模态框版本愿望清单样式 */
.ucp-modal .version-info-container:not(.ucp-modal *) {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.ucp-modal .version-primary-info:not(.ucp-modal *) {
    flex: 2;
    min-width: 300px;
    margin-right: 20px;
}

.ucp-modal .version-secondary-info:not(.ucp-modal *) {
    flex: 1;
    min-width: 250px;
}

.ucp-modal .version-notes:not(.ucp-modal *) {
    background-color: #f9f9f9;
    padding: 10px 15px;
    border-radius: 4px;
    border-left: 4px solid #ddd;
}

.ucp-modal .wishlist-items-table:not(.ucp-modal *) {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

.ucp-modal .wishlist-items-table th:not(.ucp-modal *) {
    text-align: left;
    background-color: #f0f0f0;
    padding: 8px 12px;
    border: 1px solid #ddd;
    font-weight: bold;
}

.ucp-modal .wishlist-items-table td:not(.ucp-modal *) {
    padding: 8px 12px;
    border: 1px solid #ddd;
    vertical-align: top;
}

.ucp-modal .version-meta:not(.ucp-modal *) {
    margin-bottom: 15px;
}

.ucp-modal .version-status.sent:not(.ucp-modal *) {
    display: inline-block;
    color: #46b450;
    background-color: #ecf7ed;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 13px;
}

.ucp-modal .item-properties:not(.ucp-modal *) {
    font-size: 13px;
    line-height: 1.5;
}

.ucp-modal .loading-info:not(.ucp-modal *),
.ucp-modal-body .loading-info {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 4px;
    margin-bottom: 15px;
}

.ucp-modal .error-content:not(.ucp-modal *),
.ucp-modal-body .error-content {
    padding: 15px;
    background-color: #fbeaea;
    border-left: 4px solid #dc3232;
    margin-bottom: 15px;
}

.ucp-modal .debug-container:not(.ucp-modal *),
.ucp-modal-body .debug-container {
    border: 1px dashed #ccc;
    margin-top: 30px;
    padding: 10px;
    background-color: #f8f8f8;
    font-family: monospace;
    font-size: 12px;
}

.ucp-modal.ucp-fullscreen-modal .ucp-modal-close {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

body.has-version-modal {
    overflow: hidden;
}

.ucp-modal-content {
    position: relative;
    background-color: #fefefe;
    margin: 10vh auto; /* Increase top margin to vertically center */
    padding: 0;
    border: 1px solid #888;
    width: 80%; /* Reduce width */
    max-width: 800px; /* Reduce maximum width */
    border-radius: 5px;
    max-height: 80vh; /* Reduce maximum height */
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Add shadow effect for better visibility */
}

.ucp-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
    background-color: #f5f5f5;
}

.ucp-modal-title {
    margin: 0;
    font-size: 1.4em;
    color: #23282d;
}

.ucp-modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
    max-height: calc(90vh - 70px);
}

.ucp-modal-close {
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    padding: 0 10px;
    line-height: 1;
    height: 34px;
}

/* 直接版本列表样式 */
.version-list-direct {
    margin: 0;
    padding: 0;
}

.version-item {
    margin: 0;
    padding: 0;
}

.version-item:nth-child(odd) {
    background-color: #f9f9f9;
}

.version-item:nth-child(even) {
    background-color: #ffffff;
}

.version-item a {
    color: #0073aa;
    text-decoration: none;
    display: block;
    padding: 8px 5px;
    font-size: 13px;
    margin: 0;
}

.version-item a:hover {
    color: #00a0d2;
    background-color: #f0f0f0;
}

.version-separator {
    display: none;
}

.sent-mark {
    color: green;
    font-weight: bold;
}

.version-dropdown {
    position: relative;
}

.toggle-versions {
    display: inline-block;
    padding: 5px 0;
    color: #0073aa;
    text-decoration: none;
}

.toggle-versions:hover {
    color: #00a0d2;
}

.version-list {
    margin: 10px 0 0 0;
    padding: 0;
    list-style: none;
    background-color: #fff;
}

.version-list.hidden {
    display: none;
}

.version-list li {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}

.version-list li:last-child {
    border-bottom: none;
}
</style>

<script>
jQuery(document).ready(function($) {
    console.log('Page loaded, initializing modals and version links...');
    function formatDate(dateString) {
        if (!dateString) return '';
        var date = new Date(dateString);
        return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear();
    }
    // Toggle version dropdown
    $('.toggle-versions').click(function(e) {
        e.preventDefault();
        $(this).next('.version-list').toggleClass('hidden');
    });
    
    // Hide version lists when clicking outside
    $(document).click(function(e) {
        if (!$(e.target).closest('.version-dropdown').length) {
            $('.version-list').addClass('hidden');
        }
    });
    
    $('.versions-list').after('<button id="debug-ajax" style="margin-top: 10px; margin-right: 10px;" class="button">Debug AJAX Request</button><button id="debug-modal" style="margin-top: 10px;" class="button">Debug Modal</button>');
    
    $('#debug-ajax').click(function(e) {
        e.preventDefault();
        var testVersionId = $('.view-version').first().data('id');
        if (!testVersionId) {
            alert('No available version ID found');
            return;
        }
        
        alert('Testing AJAX request for version ID: ' + testVersionId);
        
        // Show new modal
        $('#ucp-wishlist-version-modal').addClass('show');
        $('body').addClass('ucp-modal-open');
        $('#wishlist-version-content').html('<div class="loading-indicator"><span class="dashicons dashicons-image-rotate spin"></span><p>Loading version details...</p></div><pre id="ajax-debug" style="margin-top:20px;"></pre>');
    });
    
    $('#debug-modal').click(function(e) {
        e.preventDefault();
        console.log('Debug modal button clicked');
        
        // Check version links
        var $versions = $('.view-version');
        console.log('Number of version links:', $versions.length);
        if ($versions.length > 0) {
            var firstVersionId = $versions.first().data('id');
            console.log('First version link ID:', firstVersionId);
        }
        
        // Check modal status
        var $modal = $('#ucp-wishlist-version-modal');
        console.log('Modal element exists:', $modal.length > 0 ? 'Yes' : 'No');
        
        // Check version content elements
        var versionContentCount = $('.version-content').length;
        console.log('Number of version content elements:', versionContentCount);
        
        // Force show modal
        alert('Will attempt to force open the modal');
        $modal.addClass('show');
        $modal.css({
            'display': 'block',
            'opacity': 1,
            'visibility': 'visible'
        });
        $('body').addClass('ucp-modal-open');
        
        // 直接测试AJAX
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'ucp_get_wishlist_version',
                version_id: testVersionId,
                security: '<?php echo wp_create_nonce('ucp_wishlist_nonce'); ?>'
            },
            success: function(response) {
                console.log('AJAX response successful:', response);
                
                // Add debug information
                $('#ajax-debug').append('<p>Response status: <strong>' + (response.success ? 'Success' : 'Failed') + '</strong></p>');
                $('#ajax-debug').append('<p>Response time: ' + new Date().toLocaleTimeString() + '</p>');
                
                if (typeof response === 'object') {
                    $('#ajax-debug').append('<p>Response object structure:</p>');
                    $('#ajax-debug').append('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                }
                
                // 检查响应是否有效
                if (response.success && response.data && response.data.html) {
                    // 显示HTML内容
                    $('#wishlist-version-content').html(response.data.html);
                    $('#ajax-debug').append('<p>Response successful, loading HTML content</p>');
                    $('#ajax-debug').append('<p>Received HTML length: ' + response.data.html.length + ' characters</p>');
                    
                    // Register close button event
                    $('.close-version-modal').on('click', function() {
                        $('#ucp-wishlist-version-modal').removeClass('show');
                        $('body').removeClass('ucp-modal-open');
                    });
                } else {
                    var errorMsg = 'Invalid response format';
                    if (response.data && response.data.message) {
                        errorMsg = response.data.message;
                    }
                    $('#wishlist-version-content').html('<div class="error-content"><p>Error: ' + errorMsg + '</p></div>');
                    $('#ajax-debug').append('<p>Response error: ' + errorMsg + '</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#ajax-debug').append('<p>Error: ' + error + '</p>');
                $('#wishlist-version-content').html(
                    '<div class="error-content">' + 
                    '<p class="error">加载版本详情错误: ' + error + '</p>' +
                    '<p>Status: ' + status + '</p>' +
                    '<pre>' + xhr.responseText + '</pre>' +
                    '</div>'
                );
            }
        });
    });
    
    // View version details - No AJAX version - Use event delegation
    $(document).on('click', '.view-version', function(e) {
        e.preventDefault();
        
        var versionId = $(this).data('id');
        console.log('Version link clicked, version ID:', versionId);
        
        // Show modal
        var $versionModal = $('#ucp-wishlist-version-modal');
        console.log('Modal element exists:', $versionModal.length > 0 ? 'Yes' : 'No');
        $versionModal.addClass('show');
        $('body').addClass('ucp-modal-open');
        
        // Show corresponding version content
        $('.version-content').hide();
        var $targetContent = $('#version-content-' + versionId);
        console.log('Version content element exists:', $targetContent.length > 0 ? 'Yes' : 'No');
        $targetContent.show();
        
        // Set modal to be scrollable
        $('.ucp-modal-content-wrapper').css('overflow-y', 'auto');
        $('.ucp-modal-content-wrapper').css('max-height', '70vh');
        
        // Bind resend button event (if exists)
        $('.resend-version').off('click').on('click', function(e) {
            e.preventDefault();
            var versionId = $(this).data('version');
            var pageId = $(this).data('page');
            
            if (confirm('Version #' + versionId + ' will be resent, are you sure?')) {
                // Resend logic will be handled here
                alert('Feature not implemented yet');
            }
        });
    });
    
    // Close modal - Click close button
    $(document).on('click', '.ucp-modal-close', function() {
        console.log('Click close button');
        $('#ucp-wishlist-version-modal').removeClass('show');
        console.log('Close modal');
        setTimeout(function() {
            $('body').removeClass('ucp-modal-open');
        }, 300);
    });
    
    // Escape key to close modal
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#ucp-wishlist-version-modal').hasClass('show')) {
            console.log('Close modal via Escape key');
            $('#ucp-wishlist-version-modal').removeClass('show');
            setTimeout(function() {
                $('body').removeClass('ucp-modal-open');
            }, 300);
        }
    });
    
    // Click outside to close modal
    $(document).on('click', '.ucp-modal-overlay', function(e) {
        if ($(e.target).hasClass('ucp-modal-overlay')) {
            $('#ucp-wishlist-version-modal').removeClass('show');
            setTimeout(function() {
                $('body').removeClass('ucp-modal-open');
            }, 300);
        }
    });
    
    // Mark as sent to sales
    $(document).on('click', '.send-to-sales', function(e) {
        e.preventDefault();
        var versionId = $(this).data('version');
        var pageId = $(this).data('page');
        var $button = $(this);
        
        // Disable button to prevent multiple clicks
        $button.prop('disabled', true).html('<span class="dashicons dashicons-update spinning"></span> Processing...');
        
        console.log('Marking version as sent:', versionId, 'Page ID:', pageId);
        
        // Send AJAX request
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'ucp_mark_version_sent',
                version_id: versionId,
                page_id: pageId,
                nonce: '<?php echo wp_create_nonce("ucp_mark_sent"); ?>'
            },
            success: function(response) {
                console.log('AJAX response:', response);
                
                if (response.success) {
                    // Close modal
                    $('#version-modal').hide();
                    
                    // Show success message
                    alert('Success: ' + response.data.message);
                    
                    // Refresh page to display updated status
                    location.reload();
                } else {
                    var errorMsg = 'Marking failed';
                    if (response.data && response.data.message) {
                        errorMsg = response.data.message;
                    }
                    
                    // Enable button and show error
                    $button.prop('disabled', false).html('<span class="dashicons dashicons-email-alt"></span> Mark as sent to sales');
                    alert('Error: ' + errorMsg);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                
                // Enable button and show error
                $button.prop('disabled', false).html('<span class="dashicons dashicons-email-alt"></span> Mark as sent to sales');
                alert('Error: ' + error + '\n\n' + xhr.responseText);
            }
        });
    });
});
</script>

<!-- Use global styles to ensure all modals (including those created dynamically by JS) have correct styling -->
<style>
/* Global version modal styles */
.ucp-modal .version-info-container,
#version-modal .version-info-container {
    display: flex !important;
    flex-wrap: wrap !important;
    margin-bottom: 20px !important;
}

.ucp-modal .version-primary-info,
#version-modal .version-primary-info {
    flex: 2 !important;
    min-width: 300px !important;
    margin-right: 20px !important;
}

.ucp-modal .version-secondary-info,
#version-modal .version-secondary-info {
    flex: 1 !important;
    min-width: 250px !important;
}

.ucp-modal .version-notes,
#version-modal .version-notes {
    background-color: #f9f9f9 !important;
    padding: 10px 15px !important;
    border-radius: 4px !important;
    border-left: 4px solid #ddd !important;
}

.ucp-modal .wishlist-items-table,
#version-modal .wishlist-items-table {
    width: 100% !important;
    border-collapse: collapse !important;
    margin-bottom: 20px !important;
}

.ucp-modal .wishlist-items-table th,
#version-modal .wishlist-items-table th {
    text-align: left !important;
    background-color: #f0f0f0 !important;
    padding: 8px 12px !important;
    border: 1px solid #ddd !important;
    font-weight: bold !important;
}

.ucp-modal .wishlist-items-table td,
#version-modal .wishlist-items-table td {
    padding: 8px 12px !important;
    border: 1px solid #ddd !important;
    vertical-align: top !important;
}

.ucp-modal .loading-info,
#version-modal .loading-info,
.ucp-modal-body .loading-info {
    padding: 20px !important;
    background-color: #f9f9f9 !important;
    border-radius: 4px !important;
    margin-bottom: 15px !important;
}

.ucp-modal .error-content,
#version-modal .error-content,
.ucp-modal-body .error-content {
    padding: 15px !important;
    background-color: #fbeaea !important;
    border-left: 4px solid #dc3232 !important;
    margin-bottom: 15px !important;
}
</style>

<?php
// Add catch block to handle any errors at the end of the file
} catch (Exception $e) {
    echo '<div class="error notice">
        <p><strong>Error:</strong> ' . esc_html($e->getMessage()) . '</p>
        <p>Location: ' . esc_html($e->getFile()) . ' on line ' . esc_html($e->getLine()) . '</p>
        <pre>' . esc_html($e->getTraceAsString()) . '</pre>
    </div>';
}
