<?php
/**
 * AJAX Handler for Wishlist Management
 *
 * Handles AJAX requests for wishlist version viewing and management
 *
 * @package Unique_Client_Page
 * @since 1.3.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register AJAX handlers for wishlist version management
 */
function ucp_register_wishlist_ajax_handlers() {
    // Deprecated: centralized in admin handler to maintain nonce/permissions consistency
    // add_action('wp_ajax_ucp_get_wishlist_version', 'ucp_ajax_get_wishlist_version');
    // add_action('wp_ajax_nopriv_ucp_get_wishlist_version', 'ucp_ajax_get_wishlist_version');
}
// add_action('init', 'ucp_register_wishlist_ajax_handlers');

/**
 * AJAX handler to get wishlist version details
 */
function ucp_ajax_get_wishlist_version() {
    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp_view_version')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'unique-client-page')));
    }
    
    // Get version ID and page ID
    $version_id = isset($_POST['version_id']) ? intval($_POST['version_id']) : 0;
    $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
    
    if (!$version_id || !$page_id) {
        wp_send_json_error(array('message' => __('Missing required parameters.', 'unique-client-page')));
    }
    
    // Get version data from database
    global $wpdb;
    $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
    
    $version = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $version_table WHERE version_id = %d AND page_id = %d",
        $version_id, $page_id
    ));
    
    if (!$version) {
        wp_send_json_error(array('message' => __('Version not found.', 'unique-client-page')));
    }
    
    // Parse wishlist data
    $wishlist_data = [];
    if (!empty($version->wishlist_data)) {
        $wishlist_data = json_decode($version->wishlist_data, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Try unserialize if JSON decode fails
            $wishlist_data = maybe_unserialize($version->wishlist_data);
            if (!is_array($wishlist_data)) {
                $wishlist_data = [];
            }
        }
    }
    
    // Get creator name
    $created_by = get_user_by('id', $version->created_by);
    $creator_name = $created_by ? $created_by->display_name : __('Unknown', 'unique-client-page');
    
    // Start output buffering to capture HTML
    ob_start();
    ?>
    <div class="version-details">
        <h3><?php echo esc_html($version->version_name); ?></h3>
        
        <div class="version-meta">
            <p><strong><?php _e('Created:', 'unique-client-page'); ?></strong> <?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($version->created_at)); ?></p>
            <p><strong><?php _e('Created By:', 'unique-client-page'); ?></strong> <?php echo esc_html($creator_name); ?></p>
            <?php if (!empty($version->notes)): ?>
                <p><strong><?php _e('Notes:', 'unique-client-page'); ?></strong> <?php echo esc_html($version->notes); ?></p>
            <?php endif; ?>
        </div>
        
        <hr>
        
        <h4><?php _e('Products', 'unique-client-page'); ?></h4>
        
        <?php if (empty($wishlist_data)): ?>
            <p><?php _e('No products in this wishlist version.', 'unique-client-page'); ?></p>
        <?php else: ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php _e('SKU', 'unique-client-page'); ?></th>
                        <th><?php _e('Product', 'unique-client-page'); ?></th>
                        <th><?php _e('Price', 'unique-client-page'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishlist_data as $product_id): ?>
                        <?php 
                            $product = wc_get_product($product_id);
                            if (!$product) continue;
                        ?>
                        <tr>
                            <td><?php echo esc_html($product->get_sku() ?: 'N/A'); ?></td>
                            <td>
                                <a href="<?php echo admin_url('post.php?post=' . $product_id . '&action=edit'); ?>" target="_blank">
                                    <?php echo esc_html($product->get_name()); ?>
                                </a>
                            </td>
                            <td><?php echo $product->get_price_html(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
    
    // Get output buffer content
    $html = ob_get_clean();
    
    // Return success response with HTML
    wp_send_json_success(array(
        'html' => $html,
        'version' => $version,
    ));
}
