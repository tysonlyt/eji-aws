/**
 * Product Details Modal
 * Handles showing product details in a modal popup when clicking the product image
 */

// Global error handling function (if not already defined)
if (typeof window.ucpHandleError !== 'function') {
    window.ucpHandleError = function(error, context) {
        if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
            console.error('UCP Error:', context || '', error);
        }
        if (typeof jQuery !== 'undefined') {
            jQuery('.ucp-product-detail-modal-loading').html('<p class="ucp-error">Error occurred: ' + error.message + '</p><button class="ucp-product-detail-modal-retry">Retry</button>');
            
            // Bind retry button event
            jQuery('.ucp-product-detail-modal-retry').on('click', function() {
                window.location.reload();
            });
        }
        return false;
    };
}

// Global openProductModal function
window.openProductModal = function(productId, productTitle) {
    try {
        console.log('Global openProductModal called:', productId, productTitle);
        
        // Parameter validation
        if (!productId) {
            console.error('Invalid product ID provided to openProductModal');
            return false;
        }

        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not available');
            return false;
        }

        const $ = jQuery;

        // Create modal if it doesn't exist
        if ($('#ucp-product-detail-modal').length === 0) {
            console.log('Creating modal with dedicated CSS classes');
            const modalHTML = `
                <div id="ucp-product-detail-modal" class="ucp-product-detail-modal">
                    <div class="ucp-product-detail-modal-container">
                        <div class="ucp-product-detail-modal-header">
                            <h3 class="ucp-product-detail-modal-title">Product Details</h3>
                            <button class="ucp-product-detail-modal-close" style="position: absolute; right: 50px; top: 10px;">&times;</button>
                        </div>
                        <div class="ucp-product-detail-modal-body">
                            <div class="ucp-product-detail-modal-loading">
                                <span class="ucp-spinner"></span>
                                <p>Loading product details...</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('body').append(modalHTML);
            
            // Bind close button event - simplified for immediate closing
            $(document).on('click', '.ucp-product-detail-modal-close', function(e) {
                e.stopPropagation();
                const $modal = $('#ucp-product-detail-modal');
                $modal.removeClass('show').css('display', 'none');
                $('body').removeClass('ucp-modal-open'); // Re-enable body scroll
                return false;
            });
            
            // Close when clicking outside the modal - simplified for immediate closing
            $(document).on('click', '.ucp-product-detail-modal', function(e) {
                if (!$(e.target).closest('.ucp-product-detail-modal-container').length) {
                    e.preventDefault();
                    e.stopPropagation();
                    const $modal = $(this);
                    $modal.removeClass('show').css('display', 'none');
                    $('body').removeClass('ucp-modal-open'); // Re-enable body scroll and background interaction
                    return false;
                }
            });
        }

        // Show modal with animation
        const $modal = $('#ucp-product-detail-modal');
        $modal.removeClass('hide');
        $modal.css('display', 'flex').addClass('show');
        $('body').addClass('ucp-modal-open'); // Prevent body scroll and background interaction
        
        // Force modal display with direct style setting to override any CSS conflicts
        $modal[0].style.cssText = 
            'position: fixed !important; ' +
            'top: 0 !important; ' +
            'left: 0 !important; ' +
            'width: 100vw !important; ' +
            'height: 100vh !important; ' +
            'z-index: 999999 !important; ' +
            'background-color: rgba(0, 0, 0, 0.7) !important; ' +
            'display: flex !important; ' +
            'align-items: center !important; ' +
            'justify-content: center !important; ' +
            'opacity: 1 !important; ' +
            'visibility: visible !important; ' +
            'pointer-events: all !important;';
        
        // Ensure container is also properly styled for fullscreen
        const $container = $modal.find('.ucp-product-detail-modal-container');
        if ($container.length) {
            $container[0].style.cssText =
                'position: relative !important; ' +
                'background: white !important; ' +
                'border-radius: 0 !important; ' +
                'max-width: none !important; ' +
                'width: 100% !important; ' +
                'height: 100% !important; ' +
                'max-height: none !important; ' +
                'overflow: hidden !important; ' +
                'transform: translateY(0) !important; ' +
                'box-shadow: none !important;';
        }
        
        setTimeout(() => {
            $modal.addClass('show');
        }, 100);

        // Load product details
        if (typeof window.loadProductDetailsModal === 'function') {
            window.loadProductDetailsModal(productId, productTitle);
        } else {
            console.error('loadProductDetailsModal function not available');
            // Fallback method
            $modal.find('.ucp-product-detail-modal-body').html(`
                <div class="ucp-error">
                    <p>Error: Could not load product details. Please try again later.</p>
                    <button class="button" onclick="location.reload()">Reload Page</button>
                </div>`);
        }

        return true;
    } catch (error) {
        console.error('Error in openProductModal:', error);
        if (typeof window.ucpHandleError === 'function') {
            window.ucpHandleError(error, 'openProductModal');
        }
        return false;
    }
};

// Self-executing function to encapsulate the code
(function($) {
    'use strict';
    
    // Ensure global accessibility
    window.ucpProductDetails = {};

    // Utility: strip WP size suffix like -300x300 before extension to guess the full-size URL
    function stripSizeSuffix(url) {
        try {
            if (!url || typeof url !== 'string') return url;
            // Replace -WxH right before the extension, e.g., image-300x300.jpg -> image.jpg
            return url.replace(/-\d+x\d+(?=\.(?:jpg|jpeg|png|webp|gif)\b)/i, '');
        } catch (_) {
            return url;
        }
    }


    // Header offset helpers removed: modal is now true fullscreen without header offset.
    
    // Check if required parameters are available
    function checkParams() {
        if (typeof ucp_params === 'undefined') {
            if (window.console && window.console.error) {
                console.error('UCP parameters not available!');
            }
            return false;
        }
        
        if (!ucp_params.ajax_url || !ucp_params.nonce) {
            if (ucp_params.debug && window.console && window.console.error) {
                console.error('Required AJAX parameters missing!');
            }
            return false;
        }
        
        return true;
    }

    // Create modal HTML if it doesn't exist
    function createModal() {
        // Check if modal already exists
        if ($('#ucp-product-detail-modal').length > 0) {
            // Prevent propagation from modal content
            $(document).on('click', '.ucp-product-detail-modal-content', function(e) {
                e.stopPropagation();
            });
            return;
        }
    }

    // Document ready
    $(function() {
        // Check parameters
        checkParams();
        
        // Create modal on page load
        createModal();
        
        // Use event delegation for all product images, including dynamically loaded ones
        $(document).on('click', '.ucp-product-image', function(e) {
            handleProductImageClick(e, $(this));
        });
        
        // Handle product image clicks
        function handleProductImageClick(e, clickedElement) {
            e.preventDefault();
            e.stopPropagation();
            
            const $card = clickedElement.closest('.ucp-product-card');
            
            if ($card.length === 0) {
                if (ucp_params && ucp_params.debug) {
                    console.error('Could not find parent .ucp-product-card element!');
                }
                return;
            }
            
            const productId = $card.data('product-id');
            if (!productId) {
                if (ucp_params && ucp_params.debug) {
                    console.error('No product-id data attribute found on card!');
                }
                return;
            }
            
            // Get product title for modal
            let productTitle = '';
            const $titleElement = $card.find('.ucp-product-title');
            if ($titleElement.length > 0) {
                productTitle = $titleElement.text().trim();
            }
            
            loadProductDetailsModal(productId, productTitle);
        }
        
        // Print modal HTML to verify it exists
        setTimeout(function() {
            console.log('Modal element exists:', $('#ucp-product-detail-modal').length > 0);
        }, 1000);
        
        // Close modal with ESC key - simplified for immediate closing
        $(document).keydown(function(e) {
            if (e.key === "Escape") {
                $('.ucp-product-detail-modal').removeClass('show').css('display', 'none');
                $('body').removeClass('ucp-modal-open');
            }
        });
    });

    /**
     * Load product details into modal via AJAX
     */
    // Ensure global accessibility
    window.loadProductDetailsModal = function(productId, productTitle) {
        try {
            // Parameter validation
            if (!productId) {
                throw new Error('Invalid product ID');
            }
            
            if (typeof ucp_params === 'undefined' || !ucp_params.ajax_url) {
                throw new Error('Missing AJAX parameters');
            }
    
            // Get modal elements
            const $modal = $('#ucp-product-detail-modal');
            const $modalBody = $modal.find('.ucp-product-detail-modal-body');
    
            if ($modal.length === 0) {
                throw new Error('Modal element not found');
            }
    
            // Show loading state
            $modalBody.html(`
                <div class="ucp-product-detail-modal-loading">
                    <span class="ucp-spinner"></span>
                    <p>Loading product details...</p>
                </div>
            `);
            
            // Define retry mechanism
            let ajaxAttempt = 0;
            const maxRetries = 2; // Maximum retry attempts
            
            // Execute AJAX request function
            function performAjaxRequest() {
                ajaxAttempt++;
                
                // Initialize AJAX callback
                $.ajax({
                    url: ucp_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'ucp_get_product_details',
                        product_id: productId,
                        page_id: ucp_params.page_id || 0,
                        // Use dedicated product nonce, fallback to legacy nonce for compatibility
                        nonce: (typeof ucp_params !== 'undefined' && (ucp_params.product_nonce || ucp_params.nonce)) ? (ucp_params.product_nonce || ucp_params.nonce) : '',
                        modal_view: 'true',
                        attempt: ajaxAttempt  // For debugging
                    },
                    timeout: 15000, // 15 seconds timeout
                    success: function(response) {
                        console.log('AJAX Response received:', response);
                        console.log('Response type:', typeof response);
                        console.log('Response.success:', response.success);
                        console.log('Response.data exists:', !!response.data);
                        console.log('Sent nonce:', ucp_params.nonce);
                        console.log('AJAX data sent:', {
                            action: 'ucp_get_product_details',
                            product_id: productId,
                            nonce: ucp_params.nonce,
                            modal_view: 'true',
                            attempt: ajaxAttempt
                        });
                        if (!response.success && response.data) {
                            console.log('Error message:', response.data.message || response.data);
                        }
                        
                        if (response.success && response.data) {
                            // Build base container with new dedicated classes
                            const modalHTML = `
                                <div class="ucp-product-detail-modal-content">
                                    <div class="ucp-product-detail-modal-left">
                                        <div class="ucp-product-detail-modal-image-container"></div>
                                    </div>
                                    <div class="ucp-product-detail-modal-right">
                                        <div class="ucp-product-detail-modal-details"></div>
                                    </div>
                                </div>
                            `;
                            $modalBody.html(modalHTML);
                            
                            // 強制應用 CSS 樣式 - 解決樣式載入問題
                            const $content = $modalBody.find('.ucp-product-detail-modal-content');
                            const $left = $modalBody.find('.ucp-product-detail-modal-left');
                            const $right = $modalBody.find('.ucp-product-detail-modal-right');
                            
                            // 應用關鍵佈局樣式
                            $content.css({
                                'display': 'flex',
                                'flex-direction': 'row',
                                'width': '100%',
                                'max-width': '100%',
                                'max-height': '80vh',
                                'gap': '30px',
                                'padding': '0 50px 0 50px',
                                'box-sizing': 'border-box',
                                'flex': '1',
                                'overflow': 'hidden'
                            });
                            // 強化與 CSS 一致的優先級
                            try {
                                $content[0].style.setProperty('max-height', '80vh', 'important');
                                $content[0].style.setProperty('overflow', 'hidden', 'important');
                            } catch(_) {}
                            
                            $left.css({
                                'flex': '0 0 50%',
                                'max-width': '50%',
                                'display': 'flex',
                                'align-items': 'center',
                                'justify-content': 'center',
                                'padding': '0',
                                'height': '100%'
                            });
                            
                            $right.css({
                                'flex': '0 0 50%',
                                'max-width': '50%',
                                'overflow-y': 'auto !important',
                                'overflow-x': 'hidden !important',
                                'padding': '20px',
                                'height': '80vh',
                                'scrollbar-width': 'none',
                                '-ms-overflow-style': 'none'
                            });
                            
                            // 強制設定右欄為唯一滾動區域
                            $right[0].style.setProperty('overflow-y', 'auto', 'important');
                            $right[0].style.setProperty('overflow-x', 'hidden', 'important');
                            
                            // 隱藏 WebKit 瀏覽器的 scrollbar
                            $right.get(0).style.setProperty('scrollbar-width', 'none', 'important');
                            $right.get(0).style.setProperty('-ms-overflow-style', 'none', 'important');
                            
                            // 修復雙重滾動問題 - 移除所有外層滾動
                            // 1. Modal Body 不滾動
                            $modalBody.css({
                                'overflow': 'hidden !important',
                                'display': 'flex',
                                'flex-direction': 'column',
                                'height': '100%',
                                'max-height': 'none'
                            });
                            
                            // 2. Modal 本身完全不滾動
                            $modal.css({
                                'overflow': 'hidden !important',
                                'height': '100vh !important',
                                'max-height': '100vh !important'
                            });
                            
                            // 3. Content 容器不滾動
                            $content.css({
                                'overflow': 'hidden !important',
                                'height': '100%',
                                'max-height': 'none'
                            });
                            
                            // 4. 強制設定 inline styles 確保優先級
                            $modal[0].style.setProperty('overflow', 'hidden', 'important');
                            $modalBody[0].style.setProperty('overflow', 'hidden', 'important');
                            $content[0].style.setProperty('overflow', 'hidden', 'important');
                            
                            // 移除右欄內子元素的額外滾動層
                            $right.find('*').each(function() {
                                const computed = window.getComputedStyle(this);
                                if (computed.overflowY === 'auto' || computed.overflowY === 'scroll') {
                                    $(this).css('overflow-y', 'visible');
                                }
                                if (computed.overflowX === 'auto' || computed.overflowX === 'scroll') {
                                    $(this).css('overflow-x', 'visible');
                                }
                            });

                            // Left: unified gallery rendering
                            const $imgWrap = $modalBody.find('.ucp-product-detail-modal-image-container');
                            // Thumbnails prefer 'large' size; main image prefers 'full' when available
                            let gallery = Array.isArray(response.data.gallery) ? response.data.gallery.filter(Boolean) : [];
                            let galleryFull = Array.isArray(response.data.gallery_full) ? response.data.gallery_full.filter(Boolean) : [];
                            let galleryFullSrcset = Array.isArray(response.data.gallery_srcset_full) ? response.data.gallery_srcset_full : [];

                            // Build fallbacks
                            if (gallery.length === 0 && response.data.image_url_large) {
                                gallery = [response.data.image_url_large];
                            }
                            if (galleryFull.length === 0 && response.data.image_url_full) {
                                galleryFull = [response.data.image_url_full];
                            }
                            if (gallery.length === 0 && galleryFull.length > 0) {
                                // If only full-size exists, use it also for thumbs
                                gallery = galleryFull.slice(0);
                            }
                            if (gallery.length === 0 && galleryFull.length === 0) {
                                // Try extract from HTML
                                if (response.data.image) {
                                    const m = /src\s*=\s*"([^\"]+)"/i.exec(response.data.image);
                                    if (m && m[1]) {
                                        gallery = [m[1]];
                                    }
                                }
                            }
                            if (gallery.length === 0 && galleryFull.length === 0) {
                                // Final fallback: use card image
                                try {
                                    const fallback = loadProductImage(productId);
                                    if (fallback) gallery = [fallback];
                                } catch (imgError) {}
                            }

                            // Determine initial main image (strongly prefer explicit full URL)
                            let main = (response.data.image_url_full || galleryFull[0] || gallery[0] || '');
                            // If we still got a sized variant, try to infer full by stripping -WxH
                            if (main && /-\d+x\d+\.(jpg|jpeg|png|webp|gif)$/i.test(main)) {
                                main = stripSizeSuffix(main);
                            }
                            const thumbs = gallery.slice(0);
                            // 總是顯示縮圖區域，即使只有一張圖片
                            const hideThumbs = '';
                            const galleryHTML = `
                                <div class="ucp-gallery-container">
                                    <div class="ucp-gallery-thumbnails" ${hideThumbs}>
                                        ${thumbs.map((u, i) => `
                                            <div class="ucp-gallery-thumbnail ${i===0?'active':''}" data-index="${i}">
                                                <img src="${u}" alt="thumb ${i+1}">
                                            </div>
                                        `).join('')}
                                    </div>
                                    <div class="ucp-gallery-main-image">
                                        <img src="${main || ''}" class="ucp-product-detail-modal-img ucp-main-img" alt="${productTitle || response.data.name || 'Product'}">
                                    </div>
                                </div>`;
                            $imgWrap.html(galleryHTML);
                            
                            // 強制應用 Gallery CSS 樣式
                            const $galleryContainer = $imgWrap.find('.ucp-gallery-container');
                            const $thumbnails = $imgWrap.find('.ucp-gallery-thumbnails');
                            const $mainImage = $imgWrap.find('.ucp-gallery-main-image');
                            
                            $galleryContainer.css({
                                'display': 'flex',
                                'flex-direction': 'row',
                                'width': '100%',
                                'height': '100%',
                                'gap': '20px'
                            });
                            
                            $thumbnails.css({
                                'display': 'flex',
                                'flex-direction': 'column',
                                'gap': '12px',
                                'padding': '20px 15px',
                                'overflow-y': 'auto',
                                'width': '120px',
                                'justify-content': 'flex-start',
                                'background': 'rgba(248, 249, 250, 0.95)',
                                'border-right': '1px solid #e0e0e0',
                                'align-items': 'center',
                                'flex-shrink': '0',
                                'scrollbar-width': 'none',
                                '-ms-overflow-style': 'none'
                            });
                            
                            // 隱藏縮圖容器的 scrollbar
                            $thumbnails.get(0).style.setProperty('scrollbar-width', 'none', 'important');
                            $thumbnails.get(0).style.setProperty('-ms-overflow-style', 'none', 'important');
                            
                            $mainImage.css({
                                'flex': '1',
                                'display': 'flex',
                                'align-items': 'center',
                                'justify-content': 'center',
                                'width': '100%',
                                'height': '100%',
                                'margin-bottom': '0'
                            });
                            
                            // 應用縮圖樣式
                            $imgWrap.find('.ucp-gallery-thumbnail').css({
                                'flex-shrink': '0',
                                'width': '90px',
                                'height': '90px',
                                'border': '3px solid transparent',
                                'border-radius': '8px',
                                'cursor': 'pointer',
                                'overflow': 'hidden',
                                'background': 'white',
                                'box-shadow': '0 2px 8px rgba(0,0,0,0.1)',
                                'transition': 'all 0.2s ease'
                            });
                            
                            $imgWrap.find('.ucp-gallery-thumbnail img').css({
                                'width': '100%',
                                'height': '100%',
                                'object-fit': 'cover'
                            });
                            
                            // 設定第一個縮圖為 active
                            $imgWrap.find('.ucp-gallery-thumbnail:first').addClass('active').css({
                                'border-color': '#007cba'
                            });

                            // Apply srcset/sizes to main image for responsive behavior
                            const $mainImg = $imgWrap.find('.ucp-main-img');
                            const initialSrcset = (Array.isArray(galleryFullSrcset) && galleryFullSrcset.length > 0) ? (galleryFullSrcset[0] || '') : (response.data.image_srcset_full || '');
                            if (initialSrcset) {
                                $mainImg.attr('srcset', initialSrcset);
                                $mainImg.attr('sizes', response.data.image_sizes || '(max-width: 2000px) 100vw, 2000px');
                            } else {
                                // Avoid stale small srcset when we only have a guessed full URL
                                $mainImg.removeAttr('srcset');
                                $mainImg.removeAttr('sizes');
                            }

                            // Bind thumbnail switching
                            $imgWrap.off('click.ucpThumb').on('click.ucpThumb', '.ucp-gallery-thumbnail', function() {
                                const idx = parseInt(this.getAttribute('data-index'), 10) || 0;
                                let chosen = (galleryFull[idx] || gallery[idx] || '');
                                // If first image and we have explicit full, use that explicitly
                                if ((!chosen || idx === 0) && response.data.image_url_full) {
                                    chosen = response.data.image_url_full;
                                }
                                if (chosen && /-\d+x\d+\.(jpg|jpeg|png|webp|gif)$/i.test(chosen)) {
                                    chosen = stripSizeSuffix(chosen);
                                }
                                const $img = $imgWrap.find('.ucp-main-img');
                                $img.attr('src', chosen);
                                
                                // Update active thumbnail
                                $imgWrap.find('.ucp-gallery-thumbnail').removeClass('active').css('border-color', 'transparent');
                                $(this).addClass('active').css('border-color', '#007cba');
                                
                                // Update srcset if available; otherwise clear to avoid picking small sizes
                                const ss = (galleryFullSrcset[idx] || '');
                                if (ss) {
                                    $img.attr('srcset', ss);
                                    $img.attr('sizes', response.data.image_sizes || '(max-width: 2000px) 100vw, 2000px');
                                } else {
                                    $img.removeAttr('srcset');
                                    $img.removeAttr('sizes');
                                }
                            });

                            // Right: modal details content
                            const $details = $modalBody.find('.ucp-product-detail-modal-details');
                            console.log('Found .ucp-product-detail-modal-details container:', $details.length > 0);
                            
                            // 設定 details 容器高度為 80vh
                            $details.css('height', '80vh');
                            
                            if (response.data.html) {
                                $details.html(response.data.html);
                                console.log('Inserted HTML content, length:', response.data.html.length);
                                
                                // Bind wishlist button click events for dynamically loaded content
                                $details.find('.ucp-add-to-wishlist-btn').off('click.ucp-modal').on('click.ucp-modal', function(e) {
                                    e.preventDefault();
                                    const $button = $(this);
                                    const productId = $button.data('product-id');
                                    const pageId = $button.data('page-id');
                                    const action = $button.data('action') || 'add';
                                    
                                    // AJAX request for wishlist
                                    $.ajax({
                                        url: ucp_params.ajax_url,
                                        type: 'POST',
                                        data: {
                                            action: 'ucp_update_wishlist',
                                            product_id: productId,
                                            page_id: pageId,
                                            wishlist_action: action,
                                            nonce: ucp_params.nonce
                                        },
                                        beforeSend: function() {
                                            $button.addClass('loading');
                                        },
                                        success: function(response) {
                                            if (response.success) {
                                                // Toggle button state
                                                if (action === 'add') {
                                                    $button.data('action', 'remove');
                                                    $button.html('<i class="fas fa-heart"></i> Remove from Wishlist');
                                                } else {
                                                    $button.data('action', 'add');
                                                    $button.html('<i class="far fa-heart"></i> Add to Wishlist');
                                                }
                                            } else {
                                                alert(response.data.message || 'Error updating wishlist');
                                            }
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Wishlist AJAX error:', error);
                                            alert('Error updating wishlist. Please try again.');
                                        },
                                        complete: function() {
                                            $button.removeClass('loading');
                                        }
                                    });
                                });

                                // Deduplicate SKU within right panel (keep the first only)
                                try {
                                    const $rightScope = $modalBody.find('.ucp-right').first().length ? $modalBody.find('.ucp-right').first() : $details;
                                    const $skus = $rightScope.find('.product_meta .sku_wrapper');
                                    if ($skus.length > 1) {
                                        console.log('Removing duplicate SKUs:', $skus.length - 1);
                                        $skus.slice(1).remove();
                                    }
                                } catch (e) { 
                                    console.error('Error deduplicating SKUs:', e);
                                }
                            } else {
                                console.log('No HTML content, using fallback markup');
                                // Minimal fallback markup
                                const skuHTML = response.data.sku ? `<div class="product_meta"><span class="sku_wrapper">SKU: <span class="sku">${response.data.sku}</span></span></div>` : '';
                                const priceHTML = response.data.price_html ? `<div class="price">${response.data.price_html}</div>` : '';
                                const descHTML = response.data.description ? `<div class="woocommerce-product-details__short-description">${response.data.description}</div>` : '';
                                const wishlist = response.data.wishlist_button || '';
                                $details.html(`${priceHTML}${skuHTML}${descHTML}<div class="ucp-wishlist-inline">${wishlist}</div>`);
                            }

                            console.log('Modal content updated successfully');
                            
                            // 同步Modal內的願望清單按鈕狀態
                            setTimeout(() => {
                                syncModalWishlistButtons(productId);
                            }, 100);
                        } else {
                            throw new Error('Invalid AJAX response');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (ucp_params && ucp_params.debug) {
                            console.error('AJAX error:', status, error);
                        }
                        
                        // Implement automatic retry
                        if ((status === 'timeout' || xhr.status === 0 || xhr.status >= 500) && ajaxAttempt <= maxRetries) {
                            // Show retry message
                            $modalBody.find('.ucp-product-detail-modal-loading').html(`
                                <p>Connection error, reconnecting... (Attempt ${ajaxAttempt}/${maxRetries + 1})</p>
                                <span class="ucp-spinner"></span>
                            `);
                            
                            // Delayed retry
                            setTimeout(function() {
                                performAjaxRequest();
                            }, 2000);
                            return;
                        }
                        
                        // Show error after all retries fail
                        const errorMessage = status === 'timeout' ? 'Connection timeout' : 'Loading failed';
                        $modalBody.html(`
                            <div class="ucp-product-detail-modal-error">
                                <p>Failed to load product details: ${errorMessage}</p>
                                <button class="ucp-product-detail-modal-retry" data-product-id="${productId}">Retry</button>
                            </div>
                        `);
                        
                        // Bind retry button
                        $('.ucp-product-detail-modal-retry').on('click', function() {
                            const pid = $(this).data('product-id');
                            ajaxAttempt = 0; // Reset counter
                            loadProductDetailsModal(pid, productTitle);
                        });
                    }
                });
            }
            
            // Start first AJAX request
            performAjaxRequest();
            
        } catch (error) {
            // Catch higher level function errors
            if (ucp_params && ucp_params.debug) {
                console.error('Product details loading error:', error);
            }
            
            const $modal = $('#ucp-product-detail-modal');
            const $modalBody = $modal.find('.ucp-product-detail-modal-body');
            
            if ($modalBody.length) {
                $modalBody.html(`
                    <div class="ucp-product-detail-modal-error">
                        <p>Error loading product details: ${error.message}</p>
                        <button class="ucp-product-detail-modal-close">Close</button>
                    </div>
                `);
            }
        }
        
        // Return an object indicating this interface has been called
        return {
            productId: productId,
            title: productTitle,
            timestamp: new Date().getTime()
        };
    }
    
    /**
     * Get product image HTML with error handling
     * @param {number|string} productId - The product ID to load image for
     * @returns {string} - Image source URL or fallback
     */
    function loadProductImage(productId) {
        try {
            // Parameter validation
            if (!productId) {
                if (ucp_params && ucp_params.debug) {
                    console.warn('UCP: Invalid product ID for image loading');
                }
                return getPlaceholderImage();
            }
            
            // Find product card and image
            const $productCard = $(`.ucp-product-card[data-product-id="${productId}"]`);
            const $existingImage = $productCard.find('img');
            
            // Return image source if found
            if ($existingImage.length > 0) {
                const imgSrc = $existingImage.attr('src');
                if (imgSrc && imgSrc.length > 0) {
                    return imgSrc;
                }
            }
            
            // Try alternative methods to find the image
            const alternativeSrc = findAlternativeImage(productId);
            if (alternativeSrc) {
                return alternativeSrc;
            }
            
            // Fallback to placeholder
            return getPlaceholderImage();
        } catch (error) {
            if (ucp_params && ucp_params.debug) {
                console.error('UCP: Error loading product image:', error);
            }
            return getPlaceholderImage();
        }
    }
    
    /**
     * Find alternative image sources when primary method fails
     * @param {number|string} productId - The product ID to find image for
     * @returns {string|null} - Alternative image source or null
     */
    function findAlternativeImage(productId) {
        try {
            // Try to find image in other containers
            const $anyProductImage = $(`.ucp-product-image[data-product-id="${productId}"] img`);
            if ($anyProductImage.length > 0) {
                return $anyProductImage.attr('src') || null;
            }
            
            // Try to find in list view
            const $listImage = $(`.ucp-product-list-item[data-product-id="${productId}"] img`);
            if ($listImage.length > 0) {
                return $listImage.attr('src') || null;
            }
            
            return null;
        } catch (error) {
            if (ucp_params && ucp_params.debug) {
                console.warn('UCP: Error finding alternative image:', error);
            }
            return null;
        }
    }
    
    /**
     * Get placeholder image with fallbacks
     * @returns {string} - Placeholder image URL
     */
    function getPlaceholderImage() {
        // Try to get placeholder from params
        if (typeof ucp_params !== 'undefined' && ucp_params.placeholder_img) {
            return ucp_params.placeholder_img;
        }
        
        // Default empty image
        return '';
    }
    
    /**
     * Legacy function for backwards compatibility with error handling
     * @param {number|string} productId - Product ID to load
     * @param {jQuery} $container - Container to load content into
     * @returns {boolean} - Success status
     */
    function loadProductDetails(productId, $container) {
        try {
            // Parameter validation
            if (!productId) {
                throw new Error('Invalid product ID');
            }
            
            if (!$container || $container.length === 0) {
                throw new Error('Invalid container element');
            }
            
            // If possible, redirect to modal view
            if (typeof openProductModal === 'function') {
                // Get product title from DOM if possible
                let productTitle = '';
                try {
                    const $productCard = $(`.ucp-product-card[data-product-id="${productId}"]`);
                    productTitle = $productCard.find('.ucp-product-title').text() || '';
                } catch (e) {
                    if (ucp_params && ucp_params.debug) {
                    console.warn('UCP: Cannot get product title:', e);
                }
                }
                
                // Try to show modal instead
                $container.html(`
                    <div class="ucp-product-legacy-container">
                        <p>This product can now be viewed in a popup</p>
                        <button class="ucp-open-modal-btn">View Product Details</button>
                    </div>
                `);
                
                // Bind button click
                $container.find('.ucp-open-modal-btn').on('click', function() {
                    openProductModal(productId, productTitle);
                });
                
                return true;
            }
            
            // Fallback to legacy behavior
            $container.html(`
                <div class="ucp-product-legacy-container">
                    <p>Product details loading method has been updated, please refresh the page and try again</p>
                </div>
            `);
            
            return true;
        } catch (error) {
            console.error('UCP: Legacy product loading error:', error);
            
            // Show friendly error message
            if ($container && $container.length > 0) {
                $container.html(`
                    <div class="ucp-product-error">
                        <p>Error loading product details: ${error.message}</p>
                        <button class="ucp-retry-legacy" data-product-id="${productId}">Retry</button>
                    </div>
                `);
                
                // Bind retry button
                $container.find('.ucp-retry-legacy').on('click', function() {
                    const pid = $(this).data('product-id');
                    loadProductDetails(pid, $container);
                });
            }
            
            return false;
        }
    }

    // Modal內願望清單按鈕狀態同步功能
    function syncModalWishlistButtons(productId) {
        if (typeof jQuery === 'undefined') return;
        
        const $ = jQuery;
        
        // 檢測AJAX URL
        let ajaxUrl;
        if (window.ajaxurl) {
            ajaxUrl = window.ajaxurl;
        } else {
            // 動態計算 WordPress 安裝路徑
            var wpPath = window.location.pathname.split('/wp-content')[0];
            ajaxUrl = window.location.origin + wpPath + '/wp-admin/admin-ajax.php';
        }
        
        const pageId = window.ucp_page_id || $('body').data('page-id') || 87;
        const nonce = window.ucp_ajax_nonce || '';
        
        // 獲取當前願望清單狀態
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'ucp_get_wishlist',
                page_id: pageId,
                nonce: nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    const items = response.data.items || [];
                    const wishlistProductIds = items.map(item => parseInt(item.id || item.product_id));
                    const isInWishlist = wishlistProductIds.includes(parseInt(productId));
                    
                    // 更新Modal內的願望清單按鈕
                    const modal = $('#ucp-product-detail-modal');
                    if (modal.length > 0) {
                        const modalButtons = modal.find('.ucp-add-to-wishlist-btn[data-product-id="' + productId + '"]');
                        
                        modalButtons.each(function() {
                            const $button = $(this);
                            
                            if (isInWishlist) {
                                $button.attr('data-action', 'remove');
                                $button.text('Remove from Wishlist');
                                $button.addClass('in-wishlist');
                            } else {
                                $button.attr('data-action', 'add');
                                $button.text('Add to Wishlist');
                                $button.removeClass('in-wishlist');
                            }
                        });
                        
                        if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                            console.log('Modal按鈕狀態已同步 - 產品ID:', productId, '狀態:', isInWishlist ? 'IN' : 'NOT IN', 'wishlist');
                        }
                    }
                }
            },
            error: function() {
                if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                    console.log('Modal按鈕狀態同步失敗 - 產品ID:', productId);
                }
            }
        });
    }
    
    // 產品卡片狀態同步功能
    function syncWishlistStates() {
        if (typeof jQuery === 'undefined') return;
        
        const $ = jQuery;
        
        // 檢測AJAX URL
        let ajaxUrl;
        if (window.ajaxurl) {
            ajaxUrl = window.ajaxurl;
        } else {
            // 動態計算 WordPress 安裝路徑
            var wpPath = window.location.pathname.split('/wp-content')[0];
            ajaxUrl = window.location.origin + wpPath + '/wp-admin/admin-ajax.php';
        }
        
        const pageId = window.ucp_page_id || $('body').data('page-id') || 87;
        const nonce = window.ucp_ajax_nonce || '';
        
        // 獲取當前願望清單狀態
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
                action: 'ucp_get_wishlist',
                page_id: pageId,
                nonce: nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    const items = response.data.items || [];
                    const wishlistProductIds = items.map(item => parseInt(item.id || item.product_id));
                    
                    // 更新產品卡片狀態
                    $('.ucp-product-card[data-product-id]').each(function() {
                        const productId = parseInt($(this).attr('data-product-id'));
                        const isInWishlist = wishlistProductIds.includes(productId);
                        
                        if (isInWishlist) {
                            $(this).attr('data-action', 'remove');
                            $(this).addClass('in-wishlist');
                        } else {
                            $(this).attr('data-action', 'add');
                            $(this).removeClass('in-wishlist');
                        }
                    });
                    
                    // 更新願望清單按鈕狀態
                    $('.ucp-add-to-wishlist-btn[data-product-id]').each(function() {
                        const productId = parseInt($(this).attr('data-product-id'));
                        const isInWishlist = wishlistProductIds.includes(productId);
                        
                        if (isInWishlist) {
                            $(this).attr('data-action', 'remove');
                            $(this).text('Remove from Wishlist');
                            $(this).addClass('in-wishlist');
                        } else {
                            $(this).attr('data-action', 'add');
                            $(this).text('Add to Wishlist');
                            $(this).removeClass('in-wishlist');
                        }
                    });
                    
                    // 更新願望清單計數器
                    $('.wishlist-count').text(wishlistProductIds.length);
                    
                    if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                        console.log('願望清單狀態同步完成:', wishlistProductIds.length, '個產品');
                    }
                }
            },
            error: function() {
                if (typeof ucp_params !== 'undefined' && ucp_params.debug) {
                    console.log('願望清單狀態同步失敗');
                }
            }
        });
    }
    
    // 將同步函數暴露到全局
    window.syncWishlistStates = syncWishlistStates;
    
    // 頁面載入時執行同步
    $(document).ready(function() {
        setTimeout(syncWishlistStates, 500);
    });
    
    // 將同步函數暴露到全局，供其他地方調用
    window.syncModalWishlistButtons = syncModalWishlistButtons;

})(jQuery);
