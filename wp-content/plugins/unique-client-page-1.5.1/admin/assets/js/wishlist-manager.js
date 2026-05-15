/**
 * Wishlist Version Management JavaScript
 *
 * Handles all frontend interactions for wishlist version management
 */
jQuery(document).ready(function($) {
    'use strict';

    // Initialize wishlist version manager
    const WishlistManager = {
        init: function() {
            this.bindEvents();
            this.initModals();
            console.log('[UCP] Wishlist Manager initialized');
        },

        bindEvents: function() {
            // View version button
            $('.view-version').on('click', function(e) {
                e.preventDefault();
                const versionId = $(this).data('version-id');
                const pageId = $(this).data('page-id');
                WishlistManager.loadVersionDetails(versionId, pageId);
            });

            // Set as current version button
            $(document).on('click', '.set-as-current', function(e) {
                e.preventDefault();
                const versionId = $(this).data('version-id');
                const pageId = $(this).data('page-id');
                WishlistManager.setAsCurrentVersion(versionId, pageId);
            });

            // Modal close button
            $('.ucp-modal-close').on('click', function() {
                $('.ucp-modal').hide();
            });

            // Close modal when clicking on backdrop
            $('.ucp-modal-backdrop').on('click', function() {
                $('.ucp-modal').hide();
            });
        },

        initModals: function() {
            // Check if UCPModal is available
            if (typeof UCPModal !== 'undefined') {
                console.log('[UCP] Using UCPModal system');
                // UCPModal is already initialized externally
            } else {
                console.log('[UCP] Using backup modal implementation');
                // Simple backup implementation
                window.UCPModal = {
                    open: function(id) {
                        $('#' + id).addClass('show');
                        $('body').addClass('has-version-modal');
                    },
                    close: function(id) {
                        $('#' + id).removeClass('show');
                        setTimeout(function() {
                            $('body').removeClass('has-version-modal');
                        }, 300);
                    }
                };
            }
        },

        loadVersionDetails: function(versionId, pageId) {
            // Show loading indicator
            $('.ucp-version-details').hide();
            $('.ucp-loading').show();
            
            // Show modal
            UCPModal.open('ucp-version-modal');
            
            // Make AJAX request to get version details
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ucp_get_wishlist_version',
                    version_id: versionId,
                    page_id: pageId,
                    nonce: ucp_admin_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        WishlistManager.renderVersionDetails(response.data, versionId, pageId);
                    } else {
                        alert(response.data.message || 'Error loading version details');
                    }
                },
                error: function() {
                    alert('Server error while loading version details');
                },
                complete: function() {
                    $('.ucp-loading').hide();
                }
            });
        },

        renderVersionDetails: function(data, versionId, pageId) {
            // Update version information
            $('.version-number').text(data.version_number);
            $('.version-name').text(data.version_name);
            $('.version-date').text(data.created_at);
            
            // Update products list
            const $productsList = $('.ucp-version-products');
            $productsList.empty();
            
            if (data.products && data.products.length > 0) {
                const $ul = $('<ul class="product-items"></ul>');
                $.each(data.products, function(i, product) {
                    $ul.append('<li>' + 
                        '<strong>' + product.name + '</strong>' +
                        (product.sku ? ' (SKU: ' + product.sku + ')' : '') +
                        '</li>');
                });
                $productsList.append($ul);
            } else {
                $productsList.append('<p>No products in this wishlist version.</p>');
            }
            
            // Add set as current button if not current version
            if (!data.is_current) {
                $productsList.append(
                    '<p><button type="button" class="button button-primary set-as-current" ' +
                    'data-version-id="' + versionId + '" ' +
                    'data-page-id="' + pageId + '">' +
                    'Set As Current Version</button></p>'
                );
            } else {
                $productsList.append('<p><em>This is the current active version.</em></p>');
            }
            
            // Show details section
            $('.ucp-version-details').show();
        },

        setAsCurrentVersion: function(versionId, pageId) {
            if (!confirm('Are you sure you want to set this as the current wishlist version?')) {
                return;
            }
            
            // Disable button and show loading state
            const $button = $('.set-as-current[data-version-id="' + versionId + '"]');
            $button.prop('disabled', true).text('Setting as current...');
            
            // Make AJAX request to set as current version
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ucp_set_current_wishlist_version',
                    version_id: versionId,
                    page_id: pageId,
                    nonce: ucp_admin_vars.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Successfully set as current version!');
                        
                        // Reload the page to show updated status
                        window.location.reload();
                    } else {
                        alert(response.data.message || 'Error setting as current version');
                        $button.prop('disabled', false).text('Set As Current Version');
                    }
                },
                error: function() {
                    alert('Server error while setting current version');
                    $button.prop('disabled', false).text('Set As Current Version');
                }
            });
        }
    };

    // Initialize the manager
    WishlistManager.init();
});
