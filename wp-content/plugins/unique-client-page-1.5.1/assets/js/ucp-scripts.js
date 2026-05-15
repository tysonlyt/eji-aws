/**
 * Unique Client Page - Main JavaScript
 * Version: 1.0
 * 
 * This file contains all the client-side functionality for the Unique Client Page plugin,
 * including the wishlist functionality, modal handling, and UI interactions.
 */

// Wrap everything in jQuery to prevent $ conflicts
jQuery(document).ready(function($) {
    'use strict';

    /**
     * Global Variables
     */
    var ucpAjaxUrl = (typeof ucp_params !== 'undefined' && ucp_params.ajax_url) ? ucp_params.ajax_url : '/wp-admin/admin-ajax.php';
    var ucpNonce = (typeof ucp_params !== 'undefined' && ucp_params.nonce) ? ucp_params.nonce : '';
    var currentPageId = (typeof ucp_params !== 'undefined' && ucp_params.page_id) ? ucp_params.page_id : 0;
    
    // Global flags to track modal operations and prevent conflicts
    var ucpModalOperationInProgress = false;
    var isClosingModal = false;

    // Global debug object for tracking variables and events
    window.UCP_DEBUG = {
        variables: {},
        clickCount: 0,
        eventPath: []
    };
    
    /**
     * Helper Functions
     */
    
    // Helper function for debugging Ajax parameters
    function debugAjaxParams(actionName, params) {
        console.log('===== Ajax Debug: ' + actionName + ' =====');
        console.log('Parameters:', params);
        
        // Check if required global variables exist
        console.log('Global variables check:', {
            'ucpAjaxUrl exists': typeof ucpAjaxUrl !== 'undefined',
            'ucpAjaxUrl value': typeof ucpAjaxUrl !== 'undefined' ? ucpAjaxUrl : 'undefined',
            'ucpNonce exists': typeof ucpNonce !== 'undefined',
            'ucpNonce value': typeof ucpNonce !== 'undefined' ? ucpNonce : 'undefined'
        });
        
        console.log('Actual Ajax URL:', ucpAjaxUrl);
        console.log('===== End Ajax Debug =====');
    }

    // Helper function: Get cookie value by name
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Helper function: Remove PHP added slashes
    function stripslashes(str) {
        return (str + '').replace(/\\(.?)/g, function (s, n1) {
            switch (n1) {
                case '\\': return '\\';
                case '0': return '\u0000';
                case '': return '';
                default: return n1;
            }
        });
    }
    
    // Helper function: Update wishlist button state
    function updateWishlistButtonState(productId, inWishlist) {
        console.log('Updating button state for product ID:', productId, 'inWishlist:', inWishlist);
        
        // Use comprehensive selector to match all possible wishlist buttons
        $('.ucp-wishlist-btn[data-product-id="' + productId + '"], ' + 
          'button[data-product-id="' + productId + '"], ' + 
          'a[data-product-id="' + productId + '"], ' + 
          '.ucp-wishlist-toggle[data-product-id="' + productId + '"], ' + 
          '#wishlist-btn-' + productId + ', ' + 
          '.ucp-btn[data-product-id="' + productId + '"]').each(function() {
            var $btn = $(this);
            
            if (inWishlist) {
                $btn.html('<i class="fas fa-heart"></i> Remove from Wishlist');
                $btn.attr('data-action', 'remove');
            } else {
                $btn.html('<i class="far fa-heart"></i> Add to Wishlist');
                $btn.attr('data-action', 'add');
            }
            
            // Clear jQuery data cache
            $btn.removeData('action');
            $btn.data('action', $btn.attr('data-action'));
            
            console.log('Button updated - product:', productId, 'action:', $btn.attr('data-action'), 'button:', $btn[0]);
        });
    }

    /**
     * Wishlist Functions
     */
    
    // Load wishlist status
    function loadWishlistStatus() {
        console.log('Loading wishlist status on page load');
        
        // First check if user is logged in
        var isUserLoggedIn = typeof ucpIsUserLoggedIn !== 'undefined' && ucpIsUserLoggedIn === '1';
        console.log('User logged in status:', isUserLoggedIn);
        
        // For non-logged in users, try to get wishlist from cookie
        if (!isUserLoggedIn) {
            console.log('User is not logged in, checking cookie for wishlist');
            // Unified: prefer per-page cookie, fallback to legacy cookie name
            var perPageCookieName = 'ucp_guest_wishlist_' + currentPageId;
            var wishlistCookie = getCookie(perPageCookieName) || getCookie('ucp_guest_wishlist');
            
            if (wishlistCookie) {
                try {
                    // Try different parsing methods
                    var wishlistItems;
                    try {
                        wishlistItems = JSON.parse(stripslashes(wishlistCookie));
                    } catch (parseErr) {
                        console.warn('First parse attempt failed:', parseErr);
                        try {
                            // Try parsing raw cookie
                            wishlistItems = JSON.parse(wishlistCookie);
                        } catch (parseErr2) {
                            console.error('Failed to parse wishlist cookie:', parseErr2);
                            return;
                        }
                    }
                    
                    console.log('Parsed wishlist items from cookie:', wishlistItems);
                    
                    // Update button states
                    if (Array.isArray(wishlistItems) && wishlistItems.length) {
                        wishlistItems.forEach(function(item) {
                            var productId = typeof item === 'object' ? item.product_id : item;
                            updateWishlistButtonState(productId, true);
                        });
                        
                        // Update wishlist count
                        $('.wishlist-count').text(wishlistItems.length);
                    }
                } catch (e) {
                    console.error('Error processing wishlist cookie:', e);
                }
            } else {
                console.log('No wishlist cookie found');
            }
            
            return; // Stop here for non-logged in users
        }
        
        // Collect all product IDs on the page
        console.log('Collecting product IDs from page');
        var productIds = [];
        
        // Collect from various possible data-product-id attributes
        $('[data-product-id]').each(function() {
            var productId = $(this).data('product-id') || $(this).attr('data-product-id');
            if (productId && !isNaN(parseInt(productId))) {
                if (productIds.indexOf(productId) === -1) {
                    productIds.push(productId);
                }
            }
        });
        
        console.log('Found ' + productIds.length + ' unique product IDs on page');
        console.log('Product IDs:', productIds);
        
        // If we have a specific page ID, use it
        var pageId = currentPageId || 0;
        
        // If we still don't have a page ID, try to get it from a button
        if (!pageId) {
            var $pageButton = $('[data-page-id]').first();
            if ($pageButton.length) {
                pageId = $pageButton.data('page-id') || $pageButton.attr('data-page-id');
                console.log('Found page ID from button:', pageId);
            }
        }
        
        // Prepare AJAX request
        var ajaxData = {
            action: 'ucp_get_wishlist',
            nonce: ucpNonce,
            page_id: pageId
        };
        
        // Debug output
        debugAjaxParams('loadWishlistStatus', ajaxData);
        
        // Make AJAX request
        $.ajax({
            url: ucpAjaxUrl,
            type: 'POST',
            dataType: 'json',
            data: ajaxData,
            success: function(response) {
                console.log('Wishlist status response:', response);
                
                if (response.success && response.data) {
                    // Update wishlist count
                    if (response.data.count !== undefined) {
                        $('.wishlist-count, #wishlist-count').text(response.data.count);
                        console.log('Updated wishlist count to', response.data.count);
                    }
                    
                    // Update button states
                    if (response.data.items && response.data.items.length) {
                        console.log('Found ' + response.data.items.length + ' items in wishlist');
                        
                        // Update all wishlist buttons for each product
                        $.each(response.data.items, function(index, item) {
                            var productId = item.product_id || item.id || item;
                            console.log('Updating buttons for product ID:', productId);
                            
                            updateWishlistButtonState(productId, true);
                        });
                    } else {
                        console.log('No wishlist items found in response');
                    }
                } else {
                    console.warn('Invalid wishlist response:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Wishlist status request error:', error);
                console.log('XHR status:', status);
                console.log('XHR object:', xhr);
            }
        });
    }
    
    /**
     * Modal Functions
     */
    
    // Open wishlist modal
    function openWishlistModal() {
        if (ucpModalOperationInProgress) {
            console.log('Modal operation already in progress. Ignoring request.');
            return;
        }
        
        ucpModalOperationInProgress = true;
        
        console.log('Opening wishlist modal');
        
        // Make AJAX request to get wishlist items
        $.ajax({
            url: ucpAjaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ucp_get_wishlist',
                nonce: ucpNonce,
                page_id: currentPageId
            },
            success: function(response) {
                console.log('Get wishlist for modal response:', response);
                
                if (response.success && response.data) {
                    // Create modal HTML
                    var modalHtml = '<div class="ucp-modal" id="wishlist-modal">';
                    modalHtml += '<div class="ucp-modal-content">';
                    modalHtml += '<span class="ucp-modal-close" id="close-wishlist-modal">&times;</span>';
                    modalHtml += '<h2>Your Wishlist</h2>';
                    modalHtml += '<div id="wishlist-container">';
                    
                    if (response.data.items && response.data.items.length > 0) {
                        modalHtml += '<table>';
                        modalHtml += '<thead><tr><th>Product</th><th>Price</th><th>Action</th></tr></thead>';
                        modalHtml += '<tbody>';
                        
                        $.each(response.data.items, function(index, item) {
                            var itemId = item.id || item.product_id || index;
                            modalHtml += '<tr data-product-id="' + itemId + '">';
                            modalHtml += '<td>';
                            if (item.image) {
                                modalHtml += '<img src="' + item.image + '" width="50" alt="' + item.name + '" />';
                            }
                            modalHtml += item.name + '</td>';
                            modalHtml += '<td>' + (item.price || '') + '</td>';
                            modalHtml += '<td><button class="ucp-wishlist-remove" data-product-id="' + itemId + '">Remove</button></td>';
                            modalHtml += '</tr>';
                        });
                        
                        modalHtml += '</tbody></table>';
                        
                        // Add email form if there are items
                        modalHtml += '<div class="wishlist-email-form">';
                        modalHtml += '<h3>Email your wishlist</h3>';
                        modalHtml += '<form id="email-wishlist-form">';
                        modalHtml += '<div class="form-group"><label for="customer-name">Your Name</label><input type="text" id="customer-name" name="customer_name" required></div>';
                        modalHtml += '<div class="form-group"><label for="customer-email">Your Email</label><input type="email" id="customer-email" name="customer_email" required></div>';
                        modalHtml += '<div class="form-group"><label for="customer-phone">Your Phone (optional)</label><input type="tel" id="customer-phone" name="customer_phone"></div>';
                        modalHtml += '<div class="form-group"><label for="customer-message">Message (optional)</label><textarea id="customer-message" name="customer_message"></textarea></div>';
                        modalHtml += '<button type="submit" class="ucp-btn">Send Wishlist</button>';
                        modalHtml += '</form></div>';
                    } else {
                        modalHtml += '<p>Your wishlist is empty.</p>';
                    }
                    
                    modalHtml += '</div></div></div>';
                    
                    // Add modal to page
                    $('body').append(modalHtml);
                    
                    // Show modal
                    $('#wishlist-modal').fadeIn(300);
                    
                    // Bind remove buttons
                    bindWishlistRemoveButtons();
                    
                    // Bind email form
                    bindEmailWishlistForm();
                } else {
                    console.error('Failed to get wishlist for modal:', response);
                }
                
                ucpModalOperationInProgress = false;
            },
            error: function(xhr, status, error) {
                console.error('Error getting wishlist for modal:', error);
                ucpModalOperationInProgress = false;
            }
        });
    }
    
    // Close wishlist modal
    function closeWishlistModal() {
        if (isClosingModal) {
            return;
        }
        
        isClosingModal = true;
        console.log('Closing wishlist modal');
        
        $('#wishlist-modal').fadeOut(300, function() {
            $(this).remove();
            isClosingModal = false;
        });
    }
    
    // Bind email wishlist form
    function bindEmailWishlistForm() {
        $('#email-wishlist-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $submitBtn = $form.find('button[type="submit"]');
            var originalBtnText = $submitBtn.text();
            
            // 调试: 打印表单元素是否存在
            console.log('Form elements exist check:', {
                'customer-name exists': $('#customer-name').length > 0,
                'customer-email exists': $('#customer-email').length > 0,
                'form exists': $form.length > 0
            });
            
            // 调试: 打印实际DOM元素
            console.log('Form DOM elements:', {
                'form': $form[0],
                'name-input': document.getElementById('customer-name'),
                'email-input': document.getElementById('customer-email')
            });
            
            // 调试: 直接从DOM获取值
            var nameInputValue = document.getElementById('customer-name') ? document.getElementById('customer-name').value : 'Not found';
            var emailInputValue = document.getElementById('customer-email') ? document.getElementById('customer-email').value : 'Not found';
            
            console.log('Direct DOM values:', {
                'name-direct': nameInputValue,
                'email-direct': emailInputValue
            });
            
            // Get form values directly from DOM elements
            var customerName = $('#customer-name').val();
            var customerEmail = $('#customer-email').val();
            var customerPhone = $('#customer-phone').val() || '';
            var customerMessage = $('#customer-message').val() || '';
            
            console.log('Form data collected:', {
                name: customerName,
                email: customerEmail,
                phone: customerPhone,
                message: customerMessage
            });
            
            // Enhanced email validation
            if (!customerEmail || customerEmail.indexOf('@') === -1 || customerEmail.indexOf('.') === -1) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Validate form
            if (!customerName) {
                alert('Please enter your name');
                return;
            }
            
            // Collect product IDs from wishlist modal
            var productIds = [];
            $('#wishlist-container table tbody tr').each(function() {
                var productId = $(this).data('product-id');
                if (productId) {
                    productIds.push(productId);
                }
            });
            
            console.log('Found product IDs in wishlist:', productIds);
            
            // Make sure we have products to send
            if (productIds.length === 0) {
                alert('Your wishlist is empty. Please add products to your wishlist first.');
                return;
            }
            
            // Show loading state
            $submitBtn.text('Sending...').prop('disabled', true);
            
            // Prepare AJAX request with explicit data format
            var ajaxData = {
                action: 'ucp_send_wishlist_email',
                nonce: ucpNonce,
                customer_name: customerName,
                customer_email: customerEmail,
                customer_phone: customerPhone,
                customer_message: customerMessage,
                page_id: currentPageId,
                product_ids: productIds
            };
            
            // Detailed debug output
            console.log('===== Email Wishlist Form Submission =====');
            console.log('Form data:', ajaxData);
            console.log('AJAX URL:', ucpAjaxUrl);
            console.log('Nonce:', ucpNonce);
            console.log('Product IDs:', productIds);
            debugAjaxParams('sendWishlistEmail', ajaxData);
            
            // Make AJAX request
            $.ajax({
                url: ucpAjaxUrl,
                type: 'POST',
                dataType: 'json',
                data: ajaxData,
                success: function(response) {
                    console.log('Email wishlist response:', response);
                    
                    if (response.success) {
                        alert('Your wishlist has been sent successfully!');
                        $form[0].reset();
                        closeWishlistModal();
                    } else {
                        alert('Failed to send wishlist: ' + (response.data && response.data.message || 'Unknown error'));
                    }
                    
                    $submitBtn.text(originalBtnText).prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error('Email wishlist request error:', error);
                    alert('An error occurred while sending your wishlist. Please try again.');
                    $submitBtn.text(originalBtnText).prop('disabled', false);
                }
            });
        });
    }
    
    // Bind wishlist remove buttons
    function bindWishlistRemoveButtons() {
        $('.ucp-wishlist-remove').on('click', function() {
            var $btn = $(this);
            var productId = $btn.data('product-id');
            var itemRow = $btn.closest('tr');
            
            console.log('Remove from wishlist clicked for product ID:', productId);
            
            // Make AJAX request
            $.ajax({
                url: ucpAjaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ucp_update_wishlist',
                    nonce: ucpNonce,
                    product_id: productId,
                    page_id: currentPageId,
                    wishlist_action: 'remove'
                },
                success: function(response) {
                    console.log('Remove from wishlist response:', response);
                    
                    if (response.success) {
                        // Update wishlist count
                        if (response.data && response.data.count !== undefined) {
                            $('.wishlist-count, #wishlist-count').text(response.data.count);
                            console.log('Updated all wishlist counts to:', response.data.count);
                        }
                        
                        // Remove item row with animation
                        itemRow.fadeOut(300, function() {
                            $(this).remove();
                            
                            // If no items left, show empty message
                            if ($('#wishlist-container table tbody tr').length === 0) {
                                $('#wishlist-container').html('<p>Your wishlist is empty.</p>');
                            }
                            
                            // Update displayed count in list
                            $('#wishlist-count').text($('#wishlist-container table tbody tr').length);
                        });
                        
                        // Update all wishlist buttons for this product
                        updateWishlistButtonState(productId, false);
                    } else {
                        console.error('Error response:', response);
                        alert('Remove failed: ' + (response.data && response.data.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', status, error);
                    console.log('XHR object:', xhr);
                    alert('Remove failed: ' + error);
                }
            });
        });
    }
    
    /**
     * Event Handlers
     */
    
    // Handle wishlist button clicks
    $(document).on('click', '.ucp-wishlist-btn, [data-product-id][data-action]', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var productId = $btn.data('product-id') || $btn.attr('data-product-id');
        var pageId = $btn.data('page-id') || $btn.attr('data-page-id') || currentPageId;
        var action = $btn.data('action') || $btn.attr('data-action');
        
        if (!productId) {
            console.error('No product ID found for wishlist button');
            return;
        }
        
        if (!action) {
            console.error('No action specified for wishlist button');
            return;
        }
        
        console.log('Wishlist button clicked:', {
            productId: productId,
            pageId: pageId,
            action: action
        });
        
        // Prepare AJAX request data
        var ajaxData = {
            action: 'ucp_update_wishlist',
            nonce: ucpNonce,
            product_id: productId,
            page_id: pageId,
            wishlist_action: action
        };
        
        // Debug output
        debugAjaxParams('handleWishlistButton', ajaxData);
        
        // Make AJAX request
        $.ajax({
            url: ucpAjaxUrl,
            type: 'POST',
            dataType: 'json',
            data: ajaxData,
            success: function(response) {
                console.log('Wishlist action response:', response);
                
                if (response.success) {
                    // Update button state based on unified response format
                    var inWishlistRaw = response.data && response.data.in_wishlist;
                    var inWishlist = false;
                    if (typeof inWishlistRaw === 'object' && inWishlistRaw !== null) {
                        inWishlist = !!inWishlistRaw[productId];
                    } else {
                        inWishlist = !!inWishlistRaw;
                    }
                    updateWishlistButtonState(productId, inWishlist);
                    
                    // Update wishlist count
                    if (response.data && response.data.count !== undefined) {
                        $('.wishlist-count, #wishlist-count').text(response.data.count);
                    }
                    
                    // Show success message
                    if (response.data && response.data.message) {
                        // Optional: display message to user
                        console.log('Wishlist action message:', response.data.message);
                    }
                } else {
                    console.error('Wishlist action failed:', response);
                    // Optional: display error message to user
                }
            },
            error: function(xhr, status, error) {
                console.error('Wishlist action request error:', error);
                console.log('XHR status:', status);
                console.log('XHR object:', xhr);
            }
        });
    });
    
    // Register custom event listeners for wishlist modal actions
    $(document).on('ucp_open_wishlist_modal', function() {
        console.log('Custom event received: ucp_open_wishlist_modal');
        openWishlistModal();
    });
    
    $(document).on('ucp_close_wishlist_modal', function() {
        console.log('Custom event received: ucp_close_wishlist_modal');
        closeWishlistModal();
    });
    
    // Close button and outside click events
    $(document).on('click', '#close-wishlist-modal, .ucp-modal', function(e) {
        // Only close if clicking directly on modal background or close button
        if ($(e.target).is('#close-wishlist-modal') || $(e.target).hasClass('ucp-modal')) {
            closeWishlistModal();
            return false;
        }
    });
    
    // Close modal with ESC key
    $(document).keyup(function(e) {
        if (e.key === 'Escape') {
            closeWishlistModal();
        }
    });
    
    /**
     * Initialization
     */
    
    // Function to initialize all wishlist functionality
    function initWishlist() {
        console.log('Initializing wishlist functionality');
        
        // Load wishlist status to update buttons and count
        loadWishlistStatus();
        
        // Add debug logging on page load
        $(window).on('load', function() {
            console.log('========== UCP Page Load Complete, Starting Debug ==========');
            
            // Check key variables
            UCP_DEBUG.variables = {
                'ucpAjaxUrl': typeof ucpAjaxUrl !== 'undefined' ? ucpAjaxUrl : 'undefined',
                'ucpNonce': typeof ucpNonce !== 'undefined' ? ucpNonce : 'undefined',
                'ucp_params': typeof ucp_params !== 'undefined' ? 'available' : 'undefined',
                'ajaxurl': typeof ajaxurl !== 'undefined' ? ajaxurl : 'undefined',
                'currentPageId': typeof currentPageId !== 'undefined' ? currentPageId : 'undefined'
            };
            
            console.log('UCP Key Variables Check:', UCP_DEBUG.variables);
        });
    }
    
    // Detect and handle possible conflicts with inline scripts
    if (typeof openWishlistModal !== 'undefined' && openWishlistModal !== null && openWishlistModal.toString().indexOf('function openWishlistModal()') !== -1) {
        console.warn('Detected inline wishlist modal functions. UCP Scripts will take precedence.');
        // Override any existing global functions to prevent conflicts
        window.openWishlistModal = null;
        window.closeWishlistModal = null;
    }
    
    // Make critical functions globally available
    window.openWishlistModal = openWishlistModal;
    window.closeWishlistModal = closeWishlistModal;
    window.updateWishlistButtonState = updateWishlistButtonState;
    
    // Initialize all functionality
    initWishlist();
    
    // Expose public API
    window.UCP = window.UCP || {};
    window.UCP.wishlist = {
        init: initWishlist,
        loadWishlistStatus: loadWishlistStatus,
        openModal: openWishlistModal,
        closeModal: closeWishlistModal,
        updateButtonState: updateWishlistButtonState
    };
});
