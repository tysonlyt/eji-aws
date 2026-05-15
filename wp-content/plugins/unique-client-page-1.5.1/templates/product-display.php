<?php
/**
 * Product Display Template
 * 
 * Used to display product list on the frontend
 *
 * @package Unique_Client_Page
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get categories and other parameters
$limit = absint($atts['limit']);
$columns = absint($atts['columns']);
$category_slug = sanitize_text_field($atts['category']);
$product_ids = isset($atts['ids']) ? $atts['ids'] : '';

// Get all product categories
$product_page = new UCP_Product_Page();
$categories = $product_page->get_product_categories();

// Query parameters - show all products
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1, // Show all products
);

// If product IDs are specified
if (!empty($product_ids)) {
    $ids_array = array_map('trim', explode(',', $product_ids));
    $args['post__in'] = $ids_array;
    $args['orderby'] = 'post__in'; // Maintain the specified order
}

// If category is specified
if (!empty($category_slug)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => $category_slug
        )
    );
}

// Get products
$products = $product_page->get_products($args);
?>

<div class="ucp-product-container" id="ucp-product-container">
    <!-- Ensure ID and class names match JavaScript expectations -->
    <div class="ucp-product-list" id="ucp-product-list" style="display: grid; grid-template-columns: repeat(<?php echo esc_attr($columns); ?>, 1fr); gap: 20px; margin-top: 20px;">
        <?php if ($products->have_posts()) : ?>
            <?php while ($products->have_posts()) : $products->the_post(); ?>
                <?php 
                global $product;
                if (!$product || !is_a($product, 'WC_Product')) {
                    continue;
                }
                
                $product_id = $product->get_id();
                $title = get_the_title();
                $price_html = $product->get_price_html();
                $image = get_the_post_thumbnail_url($product_id, 'woocommerce_thumbnail') ?: wc_placeholder_img_src();
                ?>
                
                <?php 
                // Get current Page ID for wishlist functionality
                $current_page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : get_the_ID();
                
                // Use shared function to render product card
                echo ucp_render_product_card($product_id, $current_page_id);
                ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="ucp-no-products"><?php _e('No products found', 'unique-client-page'); ?></div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination removed - all products shown on a single page -->

</div>
