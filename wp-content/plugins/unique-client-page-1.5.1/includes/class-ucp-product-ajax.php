<?php
/**
 * UCP Product AJAX Handler
 *
 * Handles AJAX requests for product data and wishlist operations
 *
 * @package Unique_Client_Page
 * @subpackage Unique_Client_Page/includes
 * @author Your Company
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class UCP_Product_Ajax
 *
 * Handles all AJAX requests related to product data and wishlist operations
 */
class UCP_Product_Ajax {

    /**
     * Initialize the class and set up hooks
     */
    public function __construct() {
        // Products AJAX actions
        add_action('wp_ajax_ucp_get_product_details', array($this, 'get_product_details'));
        add_action('wp_ajax_nopriv_ucp_get_product_details', array($this, 'get_product_details'));
        
        // Wishlist AJAX actions
        add_action('wp_ajax_ucp_add_to_wishlist', array($this, 'add_to_wishlist'));
        add_action('wp_ajax_ucp_remove_from_wishlist', array($this, 'remove_from_wishlist'));
        
        // Wishlist actions for logged in users only
        add_action('wp_ajax_ucp_get_wishlist', array($this, 'get_wishlist'));
    }
    
    /**
     * Get product details for modal display
     */
    public function get_product_details() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp-product-nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Check if product ID is provided
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error(array(
                'message' => __('Product ID is missing', 'unique-client-page')
            ));
        }
        
        // Get product ID and validate
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array(
                'message' => __('Product not found', 'unique-client-page')
            ));
        }
        
        // Get page ID for wishlist functionality
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
        
        // Generate wishlist button HTML using plugin's standard format
        $nonce = wp_create_nonce('ucp-wishlist-nonce');
        
        // Check if product is in wishlist (simplified check for modal)
        $in_wishlist = false; // Default to false for modal context
        
        $wishlist_button_html = '<div class="ucp-wishlist-buttons">';
        $wishlist_button_html .= '<a href="javascript:void(0);" class="ucp-add-to-wishlist-btn ucp-btn" ';
        $wishlist_button_html .= 'id="wishlist-btn-' . esc_attr($product_id) . '" ';
        $wishlist_button_html .= 'data-product-id="' . esc_attr($product_id) . '" ';
        $wishlist_button_html .= 'data-page-id="' . esc_attr($page_id) . '" ';
        $wishlist_button_html .= 'data-action="' . ($in_wishlist ? 'remove' : 'add') . '" ';
        $wishlist_button_html .= 'data-nonce="' . esc_attr($nonce) . '">';
        $wishlist_button_html .= $in_wishlist ? '<i class="fas fa-heart"></i> Remove from Wishlist' : '<i class="far fa-heart"></i> Add to Wishlist';
        $wishlist_button_html .= '</a>';
        $wishlist_button_html .= '</div>';
        
        // Build gallery (main image + gallery images)
        // - gallery: 'large' size (good for thumbs/previews)
        // - gallery_full: 'full' size (for main image display)
        $gallery_urls = array();
        $gallery_full_urls = array();
        $gallery_srcset_full = array();
        $main_image_id = $product->get_image_id();
        $main_url_large = '';
        $main_url_full  = '';
        if ($main_image_id) {
            $main_url_large = wp_get_attachment_image_url($main_image_id, 'large');
            $main_url_full  = wp_get_attachment_image_url($main_image_id, 'full');
            // Obtain main image full srcset first so we can align arrays correctly
            $main_srcset_full = function_exists('wp_get_attachment_image_srcset') ? wp_get_attachment_image_srcset($main_image_id, 'full') : '';
            if ($main_url_large) { $gallery_urls[] = esc_url($main_url_large); }
            if ($main_url_full)  {
                $gallery_full_urls[] = esc_url($main_url_full);
                // Ensure gallery_srcset_full[0] corresponds to the main full image
                $gallery_srcset_full[] = $main_srcset_full ? $main_srcset_full : '';
            }
        }
        $gallery_ids = method_exists($product, 'get_gallery_image_ids') ? $product->get_gallery_image_ids() : array();
        if (!empty($gallery_ids) && is_array($gallery_ids)) {
            foreach ($gallery_ids as $img_id) {
                $url_large = wp_get_attachment_image_url($img_id, 'large');
                $url_full  = wp_get_attachment_image_url($img_id, 'full');
                if ($url_large) { $gallery_urls[] = esc_url($url_large); }
                if ($url_full)  { $gallery_full_urls[] = esc_url($url_full); }
                $srcset_full = function_exists('wp_get_attachment_image_srcset') ? wp_get_attachment_image_srcset($img_id, 'full') : '';
                $gallery_srcset_full[] = $srcset_full ? $srcset_full : '';
            }
        }
        // If no large URL could be obtained yet, fallback by parsing HTML image src
        if (empty($gallery_urls)) {
            $img_html = $product->get_image('large');
            if (!empty($img_html) && preg_match('/src\s*=\s*"([^"]+)"/i', $img_html, $m)) {
                $fallback_url = esc_url($m[1]);
                if (!empty($fallback_url)) {
                    $gallery_urls[] = $fallback_url;
                    if (empty($main_url_large)) {
                        $main_url_large = $fallback_url;
                    }
                }
            }
        }
        // Ensure gallery_full has at least one URL
        if (empty($gallery_full_urls) && $main_url_full) {
            $gallery_full_urls[] = esc_url($main_url_full);
        }
        // Align srcset array length with full urls to ensure index mapping is correct
        $full_count = count($gallery_full_urls);
        $ss_count = count($gallery_srcset_full);
        if ($ss_count < $full_count) {
            for ($i = $ss_count; $i < $full_count; $i++) {
                // Pad missing srcset entries with empty strings to keep indices aligned
                $gallery_srcset_full[$i] = '';
            }
        } elseif ($ss_count > $full_count) {
            // Trim any extra srcset entries beyond the number of full URLs
            $gallery_srcset_full = array_slice($gallery_srcset_full, 0, $full_count);
        }

        // Get full Elementor content for modal display
        $html = '';
        try {
            // Check if modal_view parameter is set
            $is_modal_view = isset($_POST['modal_view']) && $_POST['modal_view'] === 'true';
            
            if ($is_modal_view) {
                // Always generate fallback accordion structure
                ob_start();
                ?>
                <div class="ucp-product-detail-modal-content">
                    <div class="product-summary">
                        <h1 class="product-title"><?php echo esc_html($product->get_name()); ?></h1>
                    </div>
                    
                    <div class="elementor-widget-container">
                        <div class="ucp-product-sections">
                            <div class="ucp-product-section">
                                <h3>Product Details</h3>
                                <div class="ucp-product-description">
                                    <?php 
                                    $description = $product->get_description() ? $product->get_description() : $product->get_short_description();
                                    if (!$description) {
                                        $description = '<p>This is a premium jewelry piece crafted with exceptional attention to detail.</p>';
                                    }
                                    echo wp_kses_post($description); 
                                    ?>
                                </div>
                            </div>
                            
                            <div class="ucp-product-section">
                                <h3>Reference</h3>
                                <div class="ucp-product-meta">
                                    <?php if ($product->get_sku()) : ?>
                                        <p><strong>SKU:</strong> <?php echo esc_html($product->get_sku()); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($product->get_weight()) : ?>
                                        <p><strong>Weight:</strong> <?php echo esc_html($product->get_weight()); ?>g</p>
                                    <?php endif; ?>
                                    
                                    <?php if ($product->get_dimensions()) : ?>
                                        <p><strong>Dimensions:</strong> <?php echo esc_html($product->get_dimensions()); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Get product attributes
                                    $attributes = $product->get_attributes();
                                    if (!empty($attributes)) {
                                        foreach ($attributes as $attribute) {
                                            if ($attribute->get_visible()) {
                                                echo '<p><strong>' . esc_html(wc_attribute_label($attribute->get_name())) . ':</strong> ';
                                                $values = array();
                                                if ($attribute->is_taxonomy()) {
                                                    $attribute_taxonomy = $attribute->get_taxonomy_object();
                                                    $attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                                                    foreach ($attribute_values as $attribute_value) {
                                                        $values[] = esc_html($attribute_value);
                                                    }
                                                } else {
                                                    $values = array_map('trim', explode('|', $attribute->get_options()));
                                                }
                                                echo implode(', ', $values) . '</p>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="ucp-product-section">
                                <h3>Quotation</h3>
                                    <div class="ucp-quotation-form">
                                        <p>All jewellery pieces are available in the finest white gold, yellow gold, rose gold, and platinum, with purity options of 18K (750) and 14K (585). Select your preferred metal(s) and purity at checkout. Our sales team will promptly respond with quotations.</p>
                                        
                                    </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ucp-wishlist-inline"><?php echo $wishlist_button_html; ?></div>
                </div>
                <?php
                $html = ob_get_clean();
            } else {
                // Minimal product summary HTML (for backward compatibility)
                ob_start();
                ?>
                <div class="ucp-product-summary">
                    <h2 class="product_title entry-title"><?php echo esc_html($product->get_name()); ?></h2>
                    <?php if ($product->get_sku()) : ?>
                        <div class="product_meta"><span class="sku_wrapper">SKU: <span class="sku"><?php echo esc_html($product->get_sku()); ?></span></span></div>
                    <?php endif; ?>
                    <div class="price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                    <div class="woocommerce-product-details__short-description">
                        <?php echo wp_kses_post($product->get_description() ? $product->get_description() : $product->get_short_description()); ?>
                    </div>
                    <div class="ucp-wishlist-inline"><?php echo $wishlist_button_html; ?></div>
                </div>
                <?php
                $html = ob_get_clean();
            }
        } catch (Exception $e) {
            // Fallback HTML on error
            $html = '<div class="ucp-product-error">Error loading product content: ' . esc_html($e->getMessage()) . '</div>';
        }

        // Prepare product data
        $product_data = array(
            'id'          => $product->get_id(),
            'name'        => $product->get_name(),
            'sku'         => $product->get_sku(),
            'price_html'  => $product->get_price_html(),
            'description' => $product->get_description() ? $product->get_description() : $product->get_short_description(),
            'image'       => $product->get_image('large'),
            'image_url_large' => $main_url_large ? esc_url($main_url_large) : '',
            'image_url_full'  => $main_url_full ? esc_url($main_url_full) : '',
            'image_srcset_full' => isset($main_srcset_full) && $main_srcset_full ? $main_srcset_full : '',
            'image_sizes'      => '100vw',
            'gallery'         => $gallery_urls,
            'gallery_full'    => $gallery_full_urls,
            'gallery_srcset_full' => $gallery_srcset_full,
            'wishlist_button' => $wishlist_button_html,
            'page_id'     => $page_id,
            'html'        => $html,
        );

        wp_send_json_success($product_data);
    }
    
    /**
     * Add product to wishlist
     */
    public function add_to_wishlist() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp-wishlist-nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Check required fields
        if (!isset($_POST['product_id']) || !isset($_POST['page_id'])) {
            wp_send_json_error(array(
                'message' => __('Missing required fields', 'unique-client-page')
            ));
        }
        
        // Get and validate parameters
        $product_id = intval($_POST['product_id']);
        $page_id = intval($_POST['page_id']);
        
        // Get current user ID
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(array(
                'message' => __('You must be logged in to add items to wishlist', 'unique-client-page')
            ));
        }
        
        // Get product data
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array(
                'message' => __('Product not found', 'unique-client-page')
            ));
        }
        
        // Get existing wishlist data
        $wishlist_key = 'ucp_wishlist_' . $page_id;
        $wishlist = get_user_meta($user_id, $wishlist_key, true);
        
        if (empty($wishlist)) {
            $wishlist = array();
        } else {
            $wishlist = maybe_unserialize($wishlist);
        }
        
        // Check if product is already in wishlist
        foreach ($wishlist as $item) {
            if (isset($item['product_id']) && $item['product_id'] == $product_id) {
                wp_send_json_success(array(
                    'message' => __('Product is already in your wishlist', 'unique-client-page')
                ));
                return;
            }
        }
        
        // Add product to wishlist
        $wishlist[] = array(
            'product_id' => $product_id,
            'date_added' => current_time('mysql'),
            'quantity'   => 1
        );
        
        // Update user meta
        $updated = update_user_meta($user_id, $wishlist_key, $wishlist);
        
        if ($updated) {
            wp_send_json_success(array(
                'message' => __('Product added to wishlist', 'unique-client-page')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Failed to add product to wishlist', 'unique-client-page')
            ));
        }
    }
    
    /**
     * Remove product from wishlist
     */
    public function remove_from_wishlist() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp-wishlist-nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Check required fields
        if (!isset($_POST['product_id']) || !isset($_POST['page_id'])) {
            wp_send_json_error(array(
                'message' => __('Missing required fields', 'unique-client-page')
            ));
        }
        
        // Get and validate parameters
        $product_id = intval($_POST['product_id']);
        $page_id = intval($_POST['page_id']);
        
        // Get current user ID
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(array(
                'message' => __('You must be logged in to remove items from wishlist', 'unique-client-page')
            ));
        }
        
        // Get existing wishlist data
        $wishlist_key = 'ucp_wishlist_' . $page_id;
        $wishlist = get_user_meta($user_id, $wishlist_key, true);
        
        if (empty($wishlist)) {
            wp_send_json_error(array(
                'message' => __('Wishlist is empty', 'unique-client-page')
            ));
            return;
        }
        
        $wishlist = maybe_unserialize($wishlist);
        $found = false;
        $updated_wishlist = array();
        
        // Remove product from wishlist
        foreach ($wishlist as $item) {
            if (isset($item['product_id']) && $item['product_id'] == $product_id) {
                $found = true;
                continue;
            }
            $updated_wishlist[] = $item;
        }
        
        if (!$found) {
            wp_send_json_error(array(
                'message' => __('Product not found in wishlist', 'unique-client-page')
            ));
            return;
        }
        
        // Update user meta
        $updated = update_user_meta($user_id, $wishlist_key, $updated_wishlist);
        
        if ($updated) {
            wp_send_json_success(array(
                'message' => __('Product removed from wishlist', 'unique-client-page')
            ));
        } else {
            wp_send_json_error(array(
                'message' => __('Failed to remove product from wishlist', 'unique-client-page')
            ));
        }
    }
    
    /**
     * Get user wishlist
     */
    public function get_wishlist() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ucp-wishlist-nonce')) {
            wp_send_json_error(array(
                'message' => __('Security check failed', 'unique-client-page')
            ));
        }
        
        // Check required fields
        if (!isset($_POST['page_id'])) {
            wp_send_json_error(array(
                'message' => __('Page ID is missing', 'unique-client-page')
            ));
        }
        
        // Get and validate parameters
        $page_id = intval($_POST['page_id']);
        
        // Get current user ID
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error(array(
                'message' => __('You must be logged in to view wishlist', 'unique-client-page')
            ));
        }
        
        // Get wishlist data
        $wishlist_key = 'ucp_wishlist_' . $page_id;
        $wishlist = get_user_meta($user_id, $wishlist_key, true);
        
        if (empty($wishlist)) {
            wp_send_json_success(array(
                'items' => array(),
                'message' => __('Wishlist is empty', 'unique-client-page')
            ));
            return;
        }
        
        $wishlist = maybe_unserialize($wishlist);
        $items = array();
        
        // Process wishlist items
        foreach ($wishlist as $item) {
            if (!isset($item['product_id'])) {
                continue;
            }
            
            $product = wc_get_product($item['product_id']);
            
            if (!$product) {
                continue;
            }
            
            $items[] = array(
                'id'         => $product->get_id(),
                'name'       => $product->get_name(),
                'sku'        => $product->get_sku(),
                'price_html' => $product->get_price_html(),
                'image'      => $product->get_image('thumbnail'),
                'date_added' => isset($item['date_added']) ? $item['date_added'] : '',
                'quantity'   => isset($item['quantity']) ? $item['quantity'] : 1
            );
        }
        
        wp_send_json_success(array(
            'items' => $items,
            'count' => count($items)
        ));
    }
}

// Initialize the class
// NOTE: This is now initialized in unique-client-page.php within ucp_plugin_init().
// To avoid duplicate hook registration, we comment it out here. If needed, uncomment the line below.
// new UCP_Product_Ajax();
