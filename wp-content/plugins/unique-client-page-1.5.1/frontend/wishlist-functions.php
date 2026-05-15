<?php
/**
 * Wishlist specific functions
 */

/**
 * Get wishlist status for page initialization
 * Used to update the UI when the page loads
 */
function ucp_get_wishlist_status() {
    // Debug information
    error_log('ucp_get_wishlist_status called');
    
    // Verify nonce
    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'ucp-ajax-nonce')) {
        error_log('Invalid nonce in wishlist status request');
        wp_send_json_error(array('message' => 'Invalid security token'));
        wp_die();
    }
    
    // Get current user ID
    $user_id = get_current_user_id();
    
    // For non-logged in users, return empty result
    if ($user_id === 0) {
        wp_send_json_success(array(
            'count' => 0,
            'items' => array()
        ));
        wp_die();
    }
    
    // Get page ID if provided
    $page_id = isset($_REQUEST['page_id']) ? intval($_REQUEST['page_id']) : 0;
    
    // If no page ID provided, try to get from current page
    if ($page_id <= 0) {
        global $post;
        $page_id = $post ? $post->ID : 0;
        // If still no page ID, use default product page
        if ($page_id <= 0) {
            $page_id = 93; // Default product page ID
        }
    }
    
    // Build wishlist key
    $wishlist_key = '_ucp_wishlist_' . $page_id;
    
    // Get user's wishlist for this page
    $wishlist = get_user_meta($user_id, $wishlist_key, true);
    if (!is_array($wishlist)) {
        $wishlist = array();
    }
    
    // Debug output
    error_log('ucp_get_wishlist_status for user ' . $user_id . ' on page ' . $page_id);
    error_log('Wishlist items found: ' . count($wishlist));
    
    // Prepare wishlist items data
    $items = array();
    foreach ($wishlist as $product_id) {
        // 获取产品信息
        $product = get_post($product_id);
        
        if ($product) {
            // 获取产品图片
            $image_url = get_the_post_thumbnail_url($product_id, 'thumbnail');
            if (!$image_url) {
                $image_url = plugins_url('/unique-client-page/assets/images/placeholder.jpg');
            }
            
            // 获取产品价格（如果使用WooCommerce）
            $price = '';
            if (function_exists('wc_get_product')) {
                $wc_product = wc_get_product($product_id);
                if ($wc_product) {
                    $price = $wc_product->get_price_html();
                }
            }
            
            // 添加产品到数组
            $items[] = array(
                'id' => $product_id,  // 使用前端期望的id字段名称
                'product_id' => $product_id,
                'page_id' => $page_id,
                'name' => $product->post_title,
                'image' => $image_url,
                'price' => $price
            );
        }
    }
    
    // Debug output
    error_log('Returning wishlist items with full data: ' . json_encode($items));
    
    // Return wishlist data
    wp_send_json_success(array(
        'count' => count($wishlist),
        'items' => $items
    ));
    
    wp_die();
}

// Add AJAX hooks
// 注释掉以避免与 UCP_AJAX_Handler 类中的方法冲突
// add_action('wp_ajax_ucp_get_wishlist_status', 'ucp_get_wishlist_status');
// add_action('wp_ajax_nopriv_ucp_get_wishlist_status', 'ucp_get_wishlist_status');
