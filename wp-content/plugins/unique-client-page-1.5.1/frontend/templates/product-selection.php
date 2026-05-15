<?php
/**
 * Product Selection Template
 * 
 * Used to display product list and filtering functionality
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

// Query parameters
$args = array(
    'post_type' => 'product',
    'posts_per_page' => $limit
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

<div class="ucp-product-selection">
    <!-- Filter area -->
    <div class="ucp-filter-area">
        <h3><?php _e('Filter Products', 'unique-client-page'); ?></h3>
        
        <form class="ucp-filter-form">
            <!-- Category filter -->
            <div class="ucp-filter-category">
                <label for="ucp-category"><?php _e('By Category', 'unique-client-page'); ?></label>
                <select id="ucp-category" name="category">
                    <option value=""><?php _e('All Categories', 'unique-client-page'); ?></option>
                    <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($category_slug, $category->slug); ?>>
                        <?php echo esc_html($category->name); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Sort options -->
            <div class="ucp-filter-sort">
                <label for="ucp-orderby"><?php _e('Sort By', 'unique-client-page'); ?></label>
                <select id="ucp-orderby" name="orderby">
                    <option value="date"><?php _e('Newest', 'unique-client-page'); ?></option>
                    <option value="price"><?php _e('Price: Low to High', 'unique-client-page'); ?></option>
                    <option value="price-desc"><?php _e('Price: High to Low', 'unique-client-page'); ?></option>
                    <option value="popularity"><?php _e('Popularity', 'unique-client-page'); ?></option>
                </select>
            </div>
            
            <!-- Filter buttons -->
            <div class="ucp-filter-buttons">
                <button type="submit" class="button ucp-filter-button"><?php _e('Apply Filter', 'unique-client-page'); ?></button>
                <button type="reset" class="button ucp-reset-button"><?php _e('Reset', 'unique-client-page'); ?></button>
            </div>
        </form>
    </div>
    
    <!-- Product display area -->
    <div class="ucp-product-area">
        <div class="ucp-products woocommerce columns-<?php echo esc_attr($columns); ?>">
            <ul class="products columns-<?php echo esc_attr($columns); ?>">
                <?php
                if ($products->have_posts()) :
                    while ($products->have_posts()) : $products->the_post();
                        global $product;
                        ?>
                        <li <?php wc_product_class('', $product); ?>>
                            <div class="ucp-product-inner">
                                <?php
                                // Product image
                                woocommerce_template_loop_product_thumbnail();
                                
                                // Product title
                                woocommerce_template_loop_product_title();
                                
                                // Product price
                                woocommerce_template_loop_price();
                                
                                // Add to cart button
                                woocommerce_template_loop_add_to_cart();
                                
                                // TI Wishlist button
                                if (class_exists('TInvWL_Public_AddToWishlist')) {
                                    echo do_shortcode('[ti_wishlists_addtowishlist]');
                                }
                                ?>
                            </div>
                        </li>
                        <?php
                    endwhile;
                else :
                    echo '<p>' . __('No products found.', 'unique-client-page') . '</p>';
                endif;
                wp_reset_postdata();
                ?>
            </ul>
        </div>
        
        <!-- Pagination -->
        <?php if ($products->max_num_pages > 1) : ?>
        <div class="ucp-pagination">
            <div class="ucp-load-more">
                <button class="button ucp-load-more-button" 
                    data-page="1" 
                    data-max="<?php echo esc_attr($products->max_num_pages); ?>"
                    <?php if (!empty($product_ids)) : ?>
                    data-product-ids="<?php echo esc_attr($product_ids); ?>"
                    <?php endif; ?>
                >
                    <?php _e('Load More', 'unique-client-page'); ?>
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
