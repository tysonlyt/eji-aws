<?php
/**
 * Send Wishlist Button Fix
 * 
 * This file fixes the issue where clicking the "Send Wishlist to Sales" button closes the page
 * by injecting JavaScript to prevent event bubbling
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add fix script to page footer
 */
function ucp_fix_send_wishlist_button() {
    if (is_page()) {
        // 获取当前页面ID
        $current_page_id = get_the_ID();
        ?>
        <script type="text/javascript">
        /* 定义AJAX URL和Nonce变量 */
        var ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        var nonce = '<?php echo wp_create_nonce('ucp-ajax-nonce'); ?>';
        var pageId = <?php echo intval($current_page_id); ?>;
        
        jQuery(document).ready(function($) {
            // Use MutationObserver to monitor DOM changes and ensure fixes are applied after dynamic button loading
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    // Find all added Send wishlist buttons
                    if ($("#send-wishlist-btn").length > 0) {
                        // Remove original click event handlers
                        $("#send-wishlist-btn").off('click');
                        
                        // Add new event handler with prevention of bubbling and default behavior
                        $("#send-wishlist-btn").on('click', function(e) {
                            // Prevent event bubbling and default behavior
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Show loading state
                            var originalText = $(this).text();
                            $(this).text('Sending...').addClass('disabled').prop('disabled', true);
                            
                            // Collect product IDs from wishlist
                            var productIds = [];
                            $('.wishlist-table tbody tr').each(function() {
                                var productId = $(this).data('product-id');
                                console.log('Found product ID:', productId);
                                if (productId) {
                                    productIds.push(productId);
                                }
                            });
                            console.log('Collected product IDs:', productIds);
                            
                            // Send wishlist via AJAX
                            $.ajax({
                                url: ajaxUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    action: 'ucp_send_wishlist_email',
                                    nonce: nonce,
                                    page_id: pageId,
                                    'product_ids[]': productIds // Use product_ids[] format to send arrays
                                },
                                traditional: true, // Enable traditional array serialization
                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                success: function(response) {
                                    // Debug server response
                                    console.log('Send wishlist success response:', response);
                                    
                                    // Restore button state
                                    $('#send-wishlist-btn').text(originalText).removeClass('disabled').prop('disabled', false);
                                    
                                    if (response && response.success) {
                                        // Convert button to success message
                                        var $btn = $('#send-wishlist-btn');
                                        
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
                                                'padding': '10px 15px',
                                                'border-radius': '4px'
                                            })
                                            .text('Wishlist sent successfully!');
                                            
                                        // After 5 seconds, change button text to "Resend Wishlist to Sales"
                                        setTimeout(function() {
                                            $btn.text('Resend Wishlist to Sales')
                                                .removeAttr('style')
                                                .removeClass('success-message')
                                                .addClass('ucp-btn-lg');
                                        }, 5000);
                                    } else {
                                        // Show error message
                                        var errorMessage = (response && response.data && response.data.message) ? 
                                            response.data.message : 'Unknown error';
                                        
                                        // 创建错误消息容器
                                        if ($('#wishlist-error-message').length === 0) {
                                            $('.wishlist-actions').append(
                                                '<div id="wishlist-error-message" class="wishlist-error" style="color: red; margin-top: 10px;">' +
                                                '<p>Error sending wishlist: ' + errorMessage + '</p>' +
                                                '</div>'
                                            );
                                        } else {
                                            $('#wishlist-error-message').html('<p>Error sending wishlist: ' + errorMessage + '</p>');
                                        }
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Send wishlist AJAX error:', error);
                                    // 恢复按钮状态
                                    $('#send-wishlist-btn').text(originalText).removeClass('disabled').prop('disabled', false);
                                    
                                    // 创建错误消息容器
                                    if ($('#wishlist-error-message').length === 0) {
                                        $('.wishlist-actions').append(
                                            '<div id="wishlist-error-message" class="wishlist-error" style="color: red; margin-top: 10px;">' +
                                            '<p>Error sending wishlist: ' + error + '</p>' +
                                            '</div>'
                                        );
                                    } else {
                                        $('#wishlist-error-message').html('<p>Error sending wishlist: ' + error + '</p>');
                                    }
                                }
                            });
                            
                            // Return false to prevent default behavior
                            return false;
                        });
                        
                        // Target found and fix applied, disconnect observer
                        observer.disconnect();
                    }
                });
            });
            
            // Configure observation options
            var config = { childList: true, subtree: true };
            
            // Start observing on document.body
            observer.observe(document.body, config);
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'ucp_fix_send_wishlist_button', 100);
