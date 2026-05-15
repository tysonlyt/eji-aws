<?php
/**
 * Template Name: Unique Client Product Page
 * 
 * Custom Product Page Template
 *
 * @package Unique_Client_Page
 * @since 1.0.0
 */

get_header();

// Add forced responsive CSS
echo '<style>
/* Forced responsive grid layout */
.ucp-products-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) !important;
    gap: 20px !important;
}

/* Adjust column width based on data-columns attribute */
.ucp-products-grid[data-columns="2"] { grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)) !important; }
.ucp-products-grid[data-columns="3"] { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)) !important; }
.ucp-products-grid[data-columns="4"] { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) !important; }
.ucp-products-grid[data-columns="5"] { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) !important; }
.ucp-products-grid[data-columns="6"] { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)) !important; }

/* Responsive adjustments */
@media (max-width: 768px) {
    .ucp-products-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 480px) {
    .ucp-products-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>';


// Get current page ID
$page_id = get_the_ID();

// Extract product IDs and settings from page content
$page_content = get_post_field('post_content', $page_id);
$product_ids = array();
$per_page = 12; // Default value
$columns = 4;   // Default value

// Extract product IDs
$id_pattern = '/\[unique_client_products[^\]]*ids="([^"]+)"/i';
if (preg_match($id_pattern, $page_content, $matches)) {
    $product_ids = array_filter(array_map('trim', explode(',', $matches[1])));
    // Always sync to metadata
    update_post_meta($page_id, '_client_products', $product_ids);
} else {
    // If not found in content, try to get from metadata
    $saved_ids = get_post_meta($page_id, '_client_products', true);
    if (!empty($saved_ids)) {
        $product_ids = $saved_ids;
    }
}

// Extract per_page setting
$per_page_pattern = '/\[unique_client_products[^\]]*per_page="([^"]+)"/i';
if (preg_match($per_page_pattern, $page_content, $matches)) {
    $per_page = intval($matches[1]);
}

// Extract columns setting
$columns_pattern = '/\[unique_client_products[^\]]*columns="([^"]+)"/i';
if (preg_match($columns_pattern, $page_content, $matches)) {
    $columns = intval($matches[1]);
}

// Save settings to metadata for future use
update_post_meta($page_id, '_client_products_per_page', $per_page);
update_post_meta($page_id, '_client_products_columns', $columns);

// Convert product IDs to integers if not empty
$product_ids_clean = array();
$total_products = 0;

if (!empty($product_ids)) {
    $product_ids_clean = array_map('intval', $product_ids);
    $total_products = count($product_ids_clean);
    // No pagination - all products shown on one page
}

// Initialize WooCommerce product manager
$product_page = new UCP_Product_Page();

// Check if products exist for this client page
$has_products = !empty($product_ids);

// Get wishlist if user is logged in
$wishlist = array();
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    // Get current page's wishlist
    $wishlist = get_user_meta($user_id, '_ucp_wishlist', true);
    if (!is_array($wishlist)) {
        $wishlist = array();
    }
}

?>

<div class="ucp-page-container" data-page-id="<?php echo esc_attr($page_id); ?>">
    <div class="ucp-content-wrapper">
        <?php if ($has_products) : ?>
            <header class="ucp-header">
                <div class="ucp-header-inner">
                    <div class="ucp-title-area">
                        <h1 class="ucp-title"><?php the_title(); ?></h1>
                        <?php if (has_excerpt()) : ?>
                            <div class="ucp-description"><?php echo get_the_excerpt(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="ucp-header-actions">
                        
                        <!-- Wishlist Button -->
                        <button id="view-wishlist-btn" class="ucp-btn">
                            <span class="dashicons dashicons-heart"></span> <?php _e('My Wishlist', 'unique-client-page'); ?>
                            <span class="wishlist-count">
                                <?php 
                                    $user_id = get_current_user_id();
                                    $wishlist_key = '_ucp_wishlist_' . $page_id;
                                    $wishlist = is_user_logged_in() ? get_user_meta($user_id, $wishlist_key, true) : array();
                                    if (!is_array($wishlist)) $wishlist = array();
                                    echo count($wishlist); 
                                ?>
                            </span>
                        </button>

                        <div class="ucp-header-right">
                            <!-- Salesperson and sales email information, with gray background -->
                            <?php  
                            $sales_person = get_post_meta($page_id, '_ucp_sale_name', true);
                            $sales_email = get_post_meta($page_id, '_ucp_sale_email', true);
                            if (!empty($sales_person) || !empty($sales_email)) : ?>
                                <div class="ucp-sales-info">
                                    <?php if (!empty($sales_person)) : ?>
                                        <div class="ucp-sales-person">
                                            <span class="info-label"><?php _e('Salesperson:', 'unique-client-page'); ?></span>
                                            <span class="info-value"><?php echo esc_html($sales_person); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($sales_email)) : ?>
                                        <div class="ucp-sales-email">
                                            <span class="info-label"><?php _e('Sales Email:', 'unique-client-page'); ?></span>
                                            <a href="mailto:<?php echo esc_attr($sales_email); ?>" class="info-value">
                                                <?php echo esc_html($sales_email); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Page indicator removed - all products shown on one page -->
                    </div>
                </div>
            </header>

            <div class="ucp-main-content">
                <?php 
                // 顯示頁面內容（排除shortcode）
                $content_without_shortcode = preg_replace('/\[unique_client_products[^\]]*\]/i', '', $page_content);
                if (!empty(trim($content_without_shortcode))) {
                    echo '<div class="ucp-page-content" style="margin-bottom: 30px;">';
                    echo apply_filters('the_content', $content_without_shortcode);
                    echo '</div>';
                }
                ?>
                <div class="ucp-products-container">
                    <div class="ucp-products columns-<?php echo esc_attr($columns); ?>" data-columns="<?php echo esc_attr($columns); ?>" data-all-product-ids="<?php echo esc_attr(implode(',', $product_ids_clean)); ?>">
                        <div class="ucp-products-grid" data-columns="<?php echo esc_attr($columns); ?>">
                            <?php
                             // Product display section
                            if (!empty($product_ids)) {
                                // Prepare product query - show all products
                                $product_query = array(
                                    'post_type' => 'product',
                                    'posts_per_page' => -1, // Show all products
                                    'post__in' => $product_ids_clean
                                );
                                
                                $products = new WP_Query($product_query);
                                
                                if ($products->have_posts()) {
                                    // Pagination removed - all products shown on single page
                                    while ($products->have_posts()) {
                                        $products->the_post();
                                        global $product;
                                        
                                        if (!$product || !is_a($product, 'WC_Product')) {
                                            continue;
                                        }
                                        
                                        $product_id = $product->get_id();
                                        
                                        // Use shared function to render product card
                                        echo ucp_render_product_card($product_id, $page_id);
                                    }
                                    
                                    wp_reset_postdata();
                                } else {
                                    echo '<div class="ucp-no-products">' . __('No products found.', 'unique-client-page') . '</div>';
                                }
                            } else {
                                echo '<div class="ucp-no-products">' . __('No products assigned to this page.', 'unique-client-page') . '</div>';
                            }
                            ?>
                        </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="ucp-no-content">
                <h2><?php _e('No products assigned', 'unique-client-page'); ?></h2>
                <p><?php _e('There are no products assigned to this client page.', 'unique-client-page'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Wishlist modal -->
<div id="wishlist-modal" class="ucp-modal">
    <div class="ucp-modal-content">
        <div class="ucp-modal-header">
            <h2><?php _e('Your Wishlist', 'unique-client-page'); ?></h2>
            <button id="close-wishlist-modal" class="ucp-btn ucp-btn-sm">&times;</button>
        </div>
        <div class="ucp-modal-body">
            <div id="wishlist-container" class="wishlist-items"></div>
        </div>
    </div>
</div>

<!-- Wishlist handling functions -->
<script>
// 設置 WordPress AJAX 參數供外部 JS 使用
window.ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
window.ucp_ajax_nonce = '<?php echo wp_create_nonce("ucp-ajax-nonce"); ?>';
window.ucp_page_id = <?php echo $page_id; ?>;

console.log('WordPress AJAX 參數已設置:', {
    ajaxurl: window.ajaxurl,
    nonce: window.ucp_ajax_nonce,
    page_id: window.ucp_page_id
});

// External JS is used to handle wishlist modal operations
// This script only handles direct wishlist operations

// Main wishlist function - 修復按鈕阻塞問題
function handleWishlist(btn) {
    try {
        // 防止重複點擊 - 添加處理中狀態
        var $btn = jQuery(btn);
        if ($btn.hasClass('processing')) {
            return false;
        }
        
        // Get data attributes from button
        var productId = btn.getAttribute('data-product-id');
        var pageId = btn.getAttribute('data-page-id');
        var action = btn.getAttribute('data-action');
        
        // If missing required data, return
        if (!productId || !pageId || !action) {
            alert('Missing required parameters');
            return;
        }
        
        // 設置處理中狀態 - 只針對當前按鈕
        $btn.addClass('processing');
        var originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Send AJAX request
        jQuery.post('<?php echo admin_url("admin-ajax.php"); ?>',
            {
                action: 'ucp_wishlist_handler',
                product_id: productId,
                page_id: pageId,
                wishlist_action: action,
                nonce: '<?php echo wp_create_nonce('ucp-ajax-nonce'); ?>'
            },
            function(res) {
                try {
                    if (res.success) {
                        // Update button text
                        try {
                            btn.innerHTML = (action === 'add') ? '<i class="fas fa-heart"></i> Remove from Wishlist' : '<i class="far fa-heart"></i> Add to Wishlist';
                        } catch (btnErr) {
                            console.error('Update button state error', btnErr);
                        }
                        
                        // If operation was successful, refresh the current button's data-action attribute
                        try {
                            btn.setAttribute('data-action', (action === 'add') ? 'remove' : 'add');
                        } catch (attrErr) {
                            console.warn('Update button attribute error', attrErr);
                        }
                        
                        // Handle modal updates if product was removed from wishlist
                        if (action === 'remove') {
                            // If viewing in modal window, remove the product item from the list
                            try {
                                jQuery('#wishlist-item-'+productId).fadeOut(300, function() {
                                    jQuery(this).remove();
                                    
                                    // Check if now empty
                                    if (jQuery('#wishlist-container .wishlist-item').length === 0) {
                                        jQuery('#wishlist-container').html('<p>Your wishlist is empty.</p>');
                                    }
                                });
                            } catch (removeErr) {
                                console.warn('Error removing item from wishlist modal', removeErr);
                            }
                        }
                        
                        // Safely access wishlist length to prevent undefined errors
                        var wishlistLength = 0;
                        if (res.data && res.data.wishlist && Array.isArray(res.data.wishlist)) {
                            wishlistLength = res.data.wishlist.length;
                        } else if (res.data && typeof res.data.count !== 'undefined') {
                            wishlistLength = res.data.count;
                        }
                        
                        // Update wishlist count
                        updateWishlistCount(wishlistLength);
                        
                    } else {
                        // Handle error - request succeeded, but returned error
                        try {
                            btn.innerHTML = (action === 'add') ? '<i class="far fa-heart"></i> Add to Wishlist' : '<i class="fas fa-heart"></i> Remove from Wishlist';
                        } catch (textErr) {
                            console.warn('Unable to reset button text', textErr);
                        }
                        alert(res.data.message || 'Unknown error occurred');
                    }
                } catch (e) {
                    try {
                        btn.innerHTML = originalText;
                    } catch (textErr) {
                        console.warn('Unable to reset button text', textErr);
                    }
                    alert('Error processing response');
                }
                
                // 移除處理中狀態
                $btn.removeClass('processing');
            }
        ).fail(function(e) {
            console.error('Wishlist operation error', e);
            btn.innerHTML = originalText;
            $btn.removeClass('processing');
            alert('Connection error, please try again');
        });
    } catch (e) {
        console.error(e);
        // 確保在錯誤情況下也移除處理中狀態
        if (typeof $btn !== 'undefined') {
            $btn.removeClass('processing');
            btn.innerHTML = originalText || btn.innerHTML;
        }
        alert('Error processing request');
    }
}

// Update wishlist count display
function updateWishlistCount(count) {
    var countElem = document.querySelector('.wishlist-count');
    
    if (countElem) {
        // Convert count to number and set as text content
        count = parseInt(count || 0);
        countElem.textContent = count;
        
        // If count is 0, hide the count indicator
        if (parseInt(countElem.textContent) === 0) {
            countElem.style.display = 'none';
        } else {
            countElem.style.display = 'inline-block';
        }
    }
}

// After document load, bind events
jQuery(document).ready(function(jQuery) {
    // console.log('Binding wishlist button events');
    
    // Bind click event to all wishlist buttons - 修復按鈕選擇器
    jQuery(document).on('click', '.ucp-add-to-wishlist-btn, .ucp-btn[id^="wishlist-btn-"]', function(e) {
        e.preventDefault();
        
        // 防止重複點擊 - 檢查按鈕是否正在處理中
        if (jQuery(this).hasClass('processing')) {
            return false;
        }
        
        // console.log('Wishlist button clicked');
        handleWishlist(this);
    });
    
    // Bind click event to modal close button
    jQuery('#close-wishlist-modal').on('click', function(e) {
        e.preventDefault();
        closeWishlistModal();
    });
    
    // Close modal when clicking on the background (outside modal content)
    jQuery('.ucp-modal').on('click', function(e) {
        if (e.target === this) {
            closeWishlistModal();
        }
    });
    
    // Use custom events that will be handled by external JS
    // Close modal when pressing ESC key
    jQuery(document).keydown(function(e) {
        if (e.keyCode === 27) { // ESC key
            // Trigger close event for external JS to handle
            jQuery(document).trigger('ucp_close_wishlist_modal');
        }
    });
    
    // 確保按鈕狀態重置的函數
    function resetWishlistButton() {
        jQuery('#view-wishlist-btn').prop('disabled', false).removeClass('loading');
    }
    
    // 實現模態框功能
    (function($) {
        // 確保按鈕存在於DOM中
        function initWishlistButton() {
            const $btn = $('#view-wishlist-btn');
            if ($btn.length) {
                // 先移除可能存在的舊事件處理器
                $btn.off('click.wishlist');
                // 添加新的事件處理器
                $btn.on('click.wishlist', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('My Wishlist button clicked - event triggered');
        
        // 獲取當前願望清單計數
        var currentCount = jQuery('.wishlist-count:first').text().trim() || '0';
        currentCount = parseInt(currentCount) || 0;
        
        // 移除已存在的模態框
        jQuery('#wishlist-modal').remove();
        
        // 設置按鈕狀態
        var $btn = jQuery(this);
        $btn.prop('disabled', true).addClass('loading');
        
        // 確保最終會重置按鈕狀態
        setTimeout(resetWishlistButton, 10000); // 10秒後自動重置，防止卡住
        
        // Create modal HTML
        var modalHtml = `
            <div id="wishlist-modal" class="ucp-modal ucp-fullscreen-modal">
                <div class="ucp-modal-container" style="width:100%; height:100vh; display:flex; flex-direction:column;">
                    <div class="ucp-modal-header" style="margin:0; padding:15px; border-bottom:1px solid #eee;">
                        <h2>My Wishlist (<span id="wishlist-count">${currentCount}</span>)</h2>
                        <button id="close-wishlist-modal" class="ucp-btn ucp-btn-sm">Close</button>
                    </div>
                    <div id="wishlist-container" style="flex:1; overflow:auto; padding:20px; min-height:70vh;">
                        <div class="loading-indicator"><span class="spinner"></span><p>Loading wishlist...</p></div>
                    </div>
                </div>
            </div>
        `;
        
        // Add to page
        jQuery('body').append(modalHtml);
        
        // 顯示模態框 + 強制樣式與層級
        var $m = jQuery('#wishlist-modal');
        $m.addClass('active').css({
            position: 'fixed',
            inset: 0,
            display: 'flex',
            'align-items': 'stretch',
            'justify-content': 'stretch',
            background: 'rgba(0,0,0,.6)',
            'z-index': 999999,
            opacity: 1,
            visibility: 'visible'
        });
        
        var $c = $m.find('.ucp-modal-container');
        $c.css({
            position: 'relative',
            width: '100%',
            height: '100vh',
            background: '#fff',
            overflow: 'hidden'
        });
        
        jQuery('body').addClass('ucp-modal-open');
        
        // 背景點擊關閉
        $m.off('click.ucp').on('click.ucp', function(e){
            if (e.target === this) {
                closeWishlistModal($m);
            }
        });
        
        // 綁定關閉按鈕事件
        jQuery('#close-wishlist-modal').off('click.ucp').on('click.ucp', function() {
            closeWishlistModal($m);
        });
        
        // ESC 關閉
        jQuery(document).off('keydown.ucp').on('keydown.ucp', function(e){
            if (e.key === 'Escape') {
                closeWishlistModal($m);
            }
        });
        
        // 關閉模態框的通用函數
        function closeWishlistModal(modal) {
            modal.remove();
            jQuery('body').removeClass('ucp-modal-open');
            jQuery(document).off('keydown.ucp');
            resetWishlistButton(); // 重置按鈕狀態
        }
        
        // Load wishlist data via AJAX
        var pageId = <?php echo $page_id; ?>;
        var ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';
        var nonce = '<?php echo wp_create_nonce("ucp-ajax-nonce"); ?>'; // Keep consistent with the name in check_ajax_referer
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ucp_get_wishlist',
                nonce: nonce,
                page_id: pageId
            },
            success: function(response) {
                // console.log('Successfully retrieved wishlist data');
                
                if (response.success && response.data) {
                    // Debug output showing complete data structure
                    console.log('Wishlist data details:', response.data);
                    
                    // Render wishlist items
                    var wishlistItems = response.data.items || [];
                    console.log('WishlistItems array:', wishlistItems);
                    var wishlistCount = wishlistItems.length;
                    
                    // Update displayed count
                    jQuery('#wishlist-count').text(wishlistCount);
                    
                    if (wishlistCount > 0) {
                        // Create table to display wishlist items
                        var tableHtml = '<table class="wishlist-table"><thead><tr>' +
                            '<th class="wishlist-table-row-number">NO.</th><th class="wishlist-table-sku">SKU#</th><th class="wishlist-table-image">Product Image</th><th class="wishlist-table-product">Product Name</th><th class="wishlist-table-action">Action</th>' +
                            '</tr></thead><tbody>';
                            
                        // Add debug code to view the specific structure of each item
                        // console.log('WishlistItems structure');
                        
                        // Add each item
                        jQuery.each(wishlistItems, function(index, item) {
                            // Debug each item
                            console.log('Processing item ' + index + ':', item);
                            
                            // Ensure using the correct product_id field
                            var productId = item.product_id || item.id || '';
                            console.log('Product ID for item ' + index + ':', productId);
                            
                            tableHtml += '<tr id="wishlist-item-' + productId + '" data-product-id="' + productId + '">' +
                                '<td class="row-number">' + (index + 1) + '</td>' +
                                '<td class="sku">' + (item.sku || 'N/A') + '</td>' +
                                '<td class="product-image"><img src="' + item.image + '" alt="' + item.name + '" style="width:50px;height:auto;"></td>' +
                                '<td class="product-name">' + item.name + '</td>' +
                                '<td class="action"><button class="ucp-btn ucp-btn-sm remove-from-wishlist" data-product-id="' + productId + '">Remove</button></td>' +
                                '</tr>';
                        });
                        
                        tableHtml += '</tbody></table>';
                        
                        // Set button text based on send status
                        var buttonText = response.data.wishlist_sent ? 'Resend Wishlist to Sales' : 'Send Wishlist to Sales';
                        // console.log('Wishlist sent status');
                        if (response.data.wishlist_last_sent) {
                            // console.log('Last sent date');
                        }
                        
                        // Add send wishlist button
                        tableHtml += '<div class="wishlist-actions" style="margin-top:20px;">' +
                                  '<button id="send-wishlist-btn" class="ucp-btn ucp-btn-lg">' + buttonText + '</button>' +
                                  '</div>';
                                  
                        jQuery('#wishlist-content').html(tableHtml);
                        
                        // Bind Send Wishlist button event using delegation
                        jQuery(document).on('click', '#send-wishlist-btn', function() {
                            // Show loading state
                            var originalText = jQuery(this).text();
                            jQuery(this).text('Sending...').addClass('disabled').prop('disabled', true);
                            
                            // Collect product IDs from wishlist
                            var productIds = [];
                            jQuery('.wishlist-table tbody tr').each(function() {
                                var productId = jQuery(this).find('.remove-from-wishlist').data('product-id');
                                console.log('Found product ID:', productId);
                                if (productId) {
                                    productIds.push(productId);
                                }
                            });
                            console.log('Collected product IDs:', productIds);
                            
                            // Send wishlist via AJAX
                            jQuery.ajax({
                                url: ajaxUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'ucp_send_wishlist_email',
                                    nonce: nonce,
                                    page_id: pageId,
                                    'product_ids[]': productIds // Use product_ids[] format to send array
                                },
                                traditional: true, // Enable traditional serialization for arrays
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                success: function(response) {
                                    // Debug server response
                                    // console.log('Send wishlist success response');
                                    
                                    // Restore button state
                                    jQuery('#send-wishlist-btn').text(originalText).removeClass('disabled').prop('disabled', false);
                                    
                                    if (response && response.success) {
                                        // Transform button into success message
                                        var $btn = jQuery('#send-wishlist-btn');
                                        
                                        // Save original style information
                                        var originalStyles = {
                                            'text': originalText,
                                            'class': $btn.attr('class')
                                        };
                                        
                                        // Change button style and text to success message
                                        $btn.removeClass('ucp-btn-lg')
                                            .addClass('success-message')
                                            .css({
                                                'background-color': '#dff0d8',
                                                'color': '#3c763d',
                                                'border-color': '#d6e9c6',
                                                'cursor': 'default'
                                            })
                                            .text('Your wishlist has been successfully sent!');
                                        
                                        // After 5 seconds, change button text to "Resend Wishlist to Sales"
                                        setTimeout(function() {
                                            $btn.text('Resend Wishlist to Sales')
                                               .attr('class', originalStyles.class)
                                               .css({
                                                   'background-color': '',
                                                   'color': '',
                                                   'border-color': '',
                                                   'cursor': ''
                                               });
                                        }, 5000);
                                    } else {
                                        // Show error message
                                        var errorMessage = '<div class="wishlist-message error" style="margin-top:15px;padding:10px;background-color:#f2dede;color:#a94442;border-radius:4px;">' +
                                                        jQuery('#wishlist-content').html('<p>Error loading wishlist: ' + (response.data ? response.data.message : 'Unknown error') + '</p>'); +
                                                        '</div>';
                                        jQuery('.wishlist-actions').after(errorMessage);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Restore button state
                                    jQuery('#send-wishlist-btn').text(originalText).removeClass('disabled').prop('disabled', false);
                                    
                                    // Show error message
                                    var errorMessage = '<div class="wishlist-message error" style="margin-top:15px;padding:10px;background-color:#f2dede;color:#a94442;border-radius:4px;">' +
                                                    '<p>Error sending wishlist: ' + error + '</p>' +
                                                    '</div>';
                                    jQuery('.wishlist-actions').after(errorMessage);
                                }
                            });
                        });
                        
                        // Bind remove button events
                        jQuery('.remove-from-wishlist').on('click', function() {
                            var productId = jQuery(this).data('product-id');
                            var row = jQuery(this).closest('tr');
                            
                            jQuery.ajax({
                                url: ajaxUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'ucp_wishlist_handler',
                                    nonce: nonce,
                                    product_id: productId,
                                    page_id: pageId,
                                    wishlist_action: 'remove'
                                },
                                success: function(res) {
                                    // console.log('Remove wishlist item response');
                                    
                                    if (res.success) {
                                        // Get count returned from server
                                        var serverCount = 0;
                                        if (res.data && typeof res.data.count !== 'undefined') {
                                            serverCount = res.data.count;
                                            // console.log('Using count returned from server');
                                        }
                                        
                                        // Update count display on page
                                        jQuery('.wishlist-count').text(serverCount);
                                        jQuery('#wishlist-count').text(serverCount);
                                        jQuery('.ucp-wishlist-count').text(serverCount);
                                        jQuery('[data-wishlist-count]').text(serverCount);
                                        
                                        // Special handling for count in top button
                                        jQuery('#view-wishlist-btn span.wishlist-count').text(serverCount);
                                        
                                        // Then fade out and remove the row
                                        row.fadeOut(300, function() {
                                            jQuery(this).remove();
                                            
                                            // Check if wishlist is empty
                                            if (serverCount === 0) {
                                                jQuery('#wishlist-content').html('<p>Your wishlist is empty.</p>');
                                            }
                                        });
                                    } else {
                                        alert('Error removing item from wishlist');
                                    }
                                }
                            });
                        });
                    } else {
                        jQuery('#wishlist-content').html('<p>Your wishlist is empty.</p>');
                    }
                } else {
                    jQuery('#wishlist-content').html('<p>Error loading wishlist data.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to load wishlist data:', error);
                jQuery('#wishlist-content').html('<p>Error loading wishlist. Please try again.</p>');
                resetWishlistButton(); // 出錯時重置按鈕狀態
            }
        });
            });
            console.log('Wishlist button event handler attached');
        } else {
            // 如果按鈕還不存在，等待一下再試
            setTimeout(initWishlistButton, 100);
        }
    }

    // 當DOM完全載入後初始化
    $(document).ready(function() {
        initWishlistButton();
    });

    // 監聽動態內容加載完成事件（如果有使用AJAX加載內容）
    $(document).ajaxStop(function() {
        initWishlistButton();
    });

    // 綁定測試按鈕事件
    jQuery('#test-wishlist-btn').on('click', function(e) {
        e.preventDefault();
        testAddToWishlist();
    });
    
    // Bind click event to database test button
    jQuery('#test-db-btn').on('click', function(e) {
        e.preventDefault();
        testDatabaseAccess();
    });
    
    // Initialize wishlist
    // console.log('Wishlist module initialization complete');
});

// Wishlist modal functions have been moved to external JS file
// All modal window handling is done in the external ucp-scripts.js file

// Test function: directly add product to wishlist
function testAddToWishlist() {
    var btn = jQuery('#test-wishlist-btn')[0];
    btn.textContent = 'Adding...';
    
    // Create virtual button element
    var testBtn = document.createElement('a');
    testBtn.setAttribute('data-product-id', '123');
    testBtn.setAttribute('data-page-id', '<?php echo $page_id; ?>');
    testBtn.setAttribute('data-action', 'add');
    
    // Create wishlist button data correctly
    jQuery(testBtn).addClass('wishlist-btn');
    jQuery(testBtn).attr('id', 'wishlist-btn-123');
    
    // Use standard function to handle
    handleWishlist(testBtn);
    
    // Restore test button text
    setTimeout(function() {
        btn.textContent = 'Test Add to Wishlist';
    }, 2000);
}

// Test function: directly access PHP storage element
function testDatabaseAccess() {
    jQuery.post('<?php echo admin_url("admin-ajax.php"); ?>',
        {
            action: 'ucp_test_database',
            nonce: '<?php echo wp_create_nonce('ucp-ajax-nonce'); ?>'
        },
        function(response) {
            alert(response.success ? 'Database test successful: ' + response.data.message : 'Test failed: ' + response.data.message);
        }
    ).fail(function() {
        alert('Connection error during test');
    });
}
</script>

<?php
// All styles have been moved to their respective CSS files:
// - core.css
// - product-grid.css
// - modals.css
// - wishlist-styles.css

// Enqueue the necessary styles
wp_enqueue_style('ucp-core');
wp_enqueue_style('ucp-product-grid');
wp_enqueue_style('ucp-modals');
wp_enqueue_style('ucp-wishlist');
?>

<?php get_footer(); ?>

<!-- Store current page ID in a hidden field -->
<input type="hidden" id="current-page-id" value="<?php echo get_the_ID(); ?>">

<script>
// Pass current page ID to JavaScript
var ucpCurrentPageId = <?php echo get_the_ID(); ?>;

// Store page ID in local storage as backup
if (ucpCurrentPageId) {
    try {
        localStorage.setItem('ucp_last_page_id', ucpCurrentPageId);
    } catch (e) {
        console.warn('Unable to store page ID in local storage:', e);
    }
}
</script>
