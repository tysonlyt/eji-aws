/**
 * Fixed wishlist status loading function
 * This file contains the corrected implementation of loadWishlistStatus function
 * to properly update all types of wishlist buttons, including .ucp-wishlist-toggle
 */

// Load wishlist status function - fixed implementation
var loadWishlistStatus = function() {
    console.log('Loading wishlist status on page load');
    
    // First check if user is logged in
    var isUserLoggedIn = typeof ucpIsUserLoggedIn !== 'undefined' && ucpIsUserLoggedIn === '1';
    console.log('User logged in status:', isUserLoggedIn);
    
    // For non-logged in users, try to get wishlist from cookie
    if (!isUserLoggedIn) {
        console.log('User is not logged in, checking cookie for wishlist');
        var wishlistCookie = getCookie('ucp_guest_wishlist');
        
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
                        console.warn('Second parse attempt failed:', parseErr2);
                        // Try decoding then parsing
                        wishlistItems = JSON.parse(decodeURIComponent(wishlistCookie));
                    }
                }
                
                console.log('Found wishlist items in cookie:', wishlistItems);
                
                // Ensure wishlistItems is an array
                if (typeof wishlistItems === 'string') {
                    try {
                        wishlistItems = JSON.parse(wishlistItems);
                    } catch (e) {
                        // If not valid JSON, try treating as comma-separated list
                        wishlistItems = wishlistItems.split(',').map(function(item) {
                            return parseInt(item.trim(), 10);
                        });
                    }
                }
                
                // Update UI state
                if (Array.isArray(wishlistItems) && wishlistItems.length > 0) {
                    // Output detailed debug info
                    console.log('Wishlist items details:', wishlistItems.map(function(id) {
                        return { id: id, type: typeof id };
                    }));
                    
                    // Update counters - ensuring case-sensitive selectors
                    $('.wishlist-count, .ucp-wishlist-count, #wishlist-count, #wishlist_count, .wishlist_count').text(wishlistItems.length);
                    console.log('Updated wishlist count from cookie:', wishlistItems.length);
                    
                    // Update all button states
                    wishlistItems.forEach(function(productId) {
                        // Ensure productId is a number
                        productId = parseInt(productId, 10);
                        if (!isNaN(productId)) {
                            updateWishlistButtonState(productId, true);
                        }
                    });
                    
                    // Update sessionStorage for consistency
                    try {
                        sessionStorage.setItem('ucp_wishlist_count', wishlistItems.length);
                    } catch (e) {
                        console.warn('Could not update sessionStorage:', e);
                    }
                    
                    return; // No need to continue with server request
                }
            } catch (e) {
                console.error('Error parsing wishlist cookie:', e);
            }
        } else {
            console.log('No wishlist cookie found for guest user');
            // Ensure counters show 0
            $('.wishlist-count, #wishlist-count').text('0');
        }
    }
    
    // Following is the original logic for logged-in users
    // Get the current page ID from various sources
    var currentPageId = null;
    
    // First try global variable
    if (typeof ucpCurrentPageId !== 'undefined' && ucpCurrentPageId > 0) {
        currentPageId = ucpCurrentPageId;
        console.log('Using global ucpCurrentPageId:', currentPageId);
    }
    // Try URL parameters
    else {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('page_id')) {
            currentPageId = urlParams.get('page_id');
            console.log('Using page_id from URL:', currentPageId);
        }
    }
    
    // If still not found, use hidden field or local storage
    if (!currentPageId || currentPageId === '0' || currentPageId === 0) {
        if ($('#current-page-id').length > 0) {
            currentPageId = $('#current-page-id').val();
            console.log('Using page_id from hidden field:', currentPageId);
        } else {
            // Try to get from local storage as last resort
            try {
                var storedPageId = localStorage.getItem('ucp_last_page_id');
                if (storedPageId) {
                    currentPageId = parseInt(storedPageId);
                    console.log('Using page_id from local storage:', currentPageId);
                }
            } catch(e) {
                console.warn('Cannot access localStorage:', e);
            }
        }
    }
    
    // If still no valid page ID, show error and return
    if (!currentPageId || currentPageId === '0' || currentPageId === 0) {
        console.error('Cannot determine current page ID');
        $('.wishlist-count').text('?');
        return;
    }
    
    console.log('Final page_id for wishlist request:', currentPageId);
    
    // Get current wishlist status from server
    console.log('Sending AJAX request for wishlist status');
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
            console.log('Wishlist status loaded:', response);
            
            // Detailed debug of the response structure
            if (response.data) {
                console.log('Response data details:', {
                    'count': response.data.count,
                    'items_length': response.data.items ? response.data.items.length : 0,
                    'has_debug_info': response.data.debug_info ? true : false
                });
            }
            
            if (response.success && response.data) {
                // Update wishlist count
                if (response.data.count !== undefined) {
                    // Store count in a variable to ensure consistency
                    var wishlistCount = parseInt(response.data.count) || 0;
                    
                    // Update all count elements on the page
                    $('.wishlist-count').text(wishlistCount);
                    $('#wishlist-count').text(wishlistCount);
                    console.log('Updated wishlist count to:', wishlistCount);
                    
                    // Store count in sessionStorage for backup
                    try {
                        sessionStorage.setItem('ucp_wishlist_count', wishlistCount);
                        console.log('Saved wishlist count to sessionStorage:', wishlistCount);
                    } catch (e) {
                        console.warn('Unable to save wishlist count to sessionStorage:', e);
                    }
                }
                
                // Update product button states
                if (response.data.items && response.data.items.length) {
                    console.log('Updating buttons for', response.data.items.length, 'products');
                    
                    // Update button state for all products in wishlist
                    $.each(response.data.items, function(index, item) {
                        // Use product_id field as the product ID (matching server response structure)
                        var productId = item.product_id || item;
                        console.log('Updating button state for product ID:', productId);
                        
                        // Use a comprehensive selector to update ALL wishlist buttons
                        $('.ucp-wishlist-btn[data-product-id="' + productId + '"], ' + 
                          '.ucp-wishlist-toggle[data-product-id="' + productId + '"], ' + 
                          '.ucp-btn[id^="wishlist-btn-' + productId + '"], ' + 
                          'button[data-product-id="' + productId + '"], ' + 
                          'a[data-product-id="' + productId + '"]').each(function() {
                            
                            $(this).html('<i class="fas fa-heart"></i> Remove from Wishlist');
                            $(this).attr('data-action', 'remove');
                            // Clear cache to avoid jQuery caching issues
                            $(this).removeData('action');
                            $(this).data('action', 'remove');
                            console.log('Updated button for product ID:', productId, '- element:', this);
                        });
                        
                        // Also update with the general updateWishlistButtonState function
                        updateWishlistButtonState(productId, true);
                    });
                }
            } else {
                console.warn('Invalid wishlist response or no wishlist data');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading wishlist status:', error);
            console.log('XHR object:', xhr);
        }
    });
};
