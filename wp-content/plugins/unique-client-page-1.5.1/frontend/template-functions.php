<?php
/**
 * Template Functions
 * 
 * Contains all common functions related to template rendering
 *
 * @package Unique_Client_Page
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Render single product card
 *
 * @param int $product_id Product ID
 * @param int $current_page_id Current page ID (used for wishlist)
 * @return string Product card HTML
 */
function ucp_render_product_card($product_id, $current_page_id = 0) {
    // Get product object
    $product = wc_get_product($product_id);
    
    // If product does not exist or is not valid, return empty string
    if (!$product || !is_a($product, 'WC_Product')) {
        return ''; // Return empty string if product is invalid
    }
    
    // Get product thumbnail URL - Force smaller size thumbnail
    $image_id = $product->get_image_id();
    if ($image_id) {
        // Use specific thumbnail size for responsive design while maintaining proper dimensions
        $image_url = wp_get_attachment_image_src($image_id, array(300, 300))[0];
    } else {
        // For placeholder images, specify a smaller size version to avoid loading large 1200x1200 images
        $placeholder_url = wc_placeholder_img_src();
        // Force using smaller sized placeholder image
        $image_url = add_query_arg(array('w' => 300, 'h' => 300), $placeholder_url);
    }
    
    // Check if product is in page-specific wishlist
    $wishlist_key = '_ucp_wishlist_' . $current_page_id;
    $wishlist = get_user_meta(get_current_user_id(), $wishlist_key, true);
    if (!is_array($wishlist)) {
        $wishlist = [];
    }
    $in_wishlist = in_array($product_id, $wishlist);
    
    // Start output buffering
    ob_start();
    
    // Product card HTML
    ?>
    <div class="ucp-product-item" data-id="<?php echo esc_attr($product_id); ?>">
        <div class="ucp-product-card" data-product-id="<?php echo esc_attr($product_id); ?>">
            <div class="ucp-product-image" style="cursor: pointer; height: 0; padding-bottom: 100%; position: relative; overflow: hidden;" onclick="openProductModal(<?php echo esc_attr($product_id); ?>, '<?php echo esc_js($product->get_name()); ?>')">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" style="max-width: 90%; max-height: 90%; object-fit: contain;"> 
                </div>
            </div>
            <h3 class="ucp-product-title"><?php echo esc_html($product->get_name()); ?></h3>
        </div>
        
        <!-- Product detail container (initially hidden) -->
        <div class="ucp-product-detail" id="product-detail-<?php echo esc_attr($product_id); ?>" style="display: none; margin-top: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
            <div class="ucp-product-detail-content">
                <!-- Content will be loaded via AJAX -->
                <div class="ucp-loading" style="text-align: center; padding: 20px;">
                    <span class="ucp-spinner is-active" style="float: none; visibility: visible; margin: 0 auto;"></span>
                    <p>Loading product details...</p>
                </div>
            </div>
        </div>
        
        <!-- Wishlist button -->
        <a href="javascript:void(0);" class="ucp-add-to-wishlist-btn ucp-btn" id="wishlist-btn-<?php echo esc_attr($product_id); ?>" 
           data-product-id="<?php echo esc_attr($product_id); ?>" data-page-id="<?php echo esc_attr($current_page_id); ?>"
           data-action="<?php echo $in_wishlist ? 'remove' : 'add'; ?>">
            <?php echo $in_wishlist ? '<i class="fas fa-heart"></i> Remove from Wishlist' : '<i class="far fa-heart"></i> Add to Wishlist'; ?>
        </a>
    </div>
    <?php
    
    // Return HTML content
    return ob_get_clean();
}
