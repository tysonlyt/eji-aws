/**
 * Direct fix for modal close button issues
 * This file adds a global event handler to ensure close buttons work properly
 */

(function($) {
    'use strict';
    
    // Execute when document is ready
    $(document).ready(function() {
        // Remove any existing event handlers
        $(document).off('click.ucpDirectClose');
        
        // Add global click event handler for all close buttons
        $(document).on('click.ucpDirectClose', '.ucp-modal-close, .ucp-close-modal, .ucp-cancel-selection', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Find the nearest modal window
            const $modal = $(this).closest('.ucp-modal');
            if ($modal.length) {
                // Directly modify CSS to hide modal
                $modal.removeClass('show').addClass('closing').css('visibility', 'hidden');
                
                // Restore body style
                $('body').removeClass('ucp-modal-open').css('padding-right', '');
                
                // Remove modal after delay
                setTimeout(function() {
                    $modal.remove();
                }, 300);
            }
        });
        
        // Add ESC key close handler
        $(document).on('keydown.ucpDirectEsc', function(e) {
            if (e.key === 'Escape') {
                const $visibleModals = $('.ucp-modal.show');
                if ($visibleModals.length) {
                    $visibleModals.each(function() {
                        const $modal = $(this);
                        $modal.removeClass('show').addClass('closing').css('visibility', 'hidden');
                    });
                    
                    // Restore body style
                    $('body').removeClass('ucp-modal-open').css('padding-right', '');
                    
                    // Remove modals after delay
                    setTimeout(function() {
                        $visibleModals.remove();
                    }, 300);
                }
            }
        });
        
        // 願望清單按鈕處理
        function initWishlistButton() {
            const $btn = $('#view-wishlist-btn');
            if ($btn.length) {
                console.log('找到願望清單按鈕，綁定事件...');
                
                // 先移除可能存在的舊事件處理器
                $btn.off('click.wishlist');
                
                // 添加新的事件處理器
                $btn.on('click.wishlist', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('願望清單按鈕被點擊');
                    
                    // 獲取當前願望清單計數
                    var currentCount = $('.wishlist-count:first').text().trim() || '0';
                    currentCount = parseInt(currentCount) || 0;
                    
                    // 移除已存在的模態框
                    $('#wishlist-modal').remove();
                    
                    // 創建模態框HTML
                    var modalHtml = `
                        <div id="wishlist-modal" class="ucp-modal ucp-fullscreen-modal">
                            <div class="ucp-modal-container" style="width:100%; height:100vh; display:flex; flex-direction:column;">
                                <div class="ucp-modal-header" style="margin:0; padding:15px; border-bottom:1px solid #eee;">
                                    <h2>My Wishlist (<span id="wishlist-count">${currentCount}</span>)</h2>
                                    <button id="close-wishlist-modal" class="ucp-btn ucp-btn-sm ucp-modal-close">Close</button>
                                </div>
                                <div id="wishlist-container" style="flex:1; overflow:auto; padding:20px; min-height:70vh;">
                                    <div class="loading-indicator"><span class="spinner"></span><p>Loading wishlist...</p></div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // 添加到頁面
                    $('body').append(modalHtml);
                    
                    // 強制顯示樣式
                    var $m = $('#wishlist-modal');
                    $m.addClass('active show').css({
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
                    
                    $('body').addClass('ucp-modal-open');
                    
                    // 載入願望清單數據
                    loadWishlistData();
                    
                    console.log('願望清單模態框已打開');
                });
                
                console.log('願望清單按鈕事件已綁定');
                return true;
            } else {
                console.log('未找到願望清單按鈕');
                return false;
            }
        }
        
        // 載入願望清單數據的函數
        function loadWishlistData() {
            console.log('開始載入願望清單數據...');
            
            // 獲取頁面ID和AJAX參數
            var pageId = $('.ucp-page-container').data('page-id') || window.ucp_page_id;
            if (!pageId) {
                console.error('無法獲取頁面ID');
                $('#wishlist-container').html('<p>Error: Unable to get page ID.</p>');
                return;
            }
            
            // WordPress AJAX 參數 - 使用動態 URL
            var ajaxUrl = window.ajaxurl || (window.location.origin + window.location.pathname.split('/wp-content')[0] + '/wp-admin/admin-ajax.php');
            
            // 嘗試獲取真正的 WordPress nonce
            var nonce = window.ucp_ajax_nonce;
            if (!nonce || nonce.startsWith('test_nonce_')) {
                // 嘗試從頁面中的其他 nonce 欄位獲取
                nonce = $('input[name="_wpnonce"]').val() || 
                        $('input[name="nonce"]').val() || 
                        $('meta[name="_token"]').attr('content') ||
                        $('#_wpnonce').val();
                        
                if (!nonce) {
                    console.warn('無法獲取有效的 WordPress nonce，嘗試不使用 nonce');
                    nonce = '';
                }
            }
            
            console.log('AJAX 參數:', {
                url: ajaxUrl,
                pageId: pageId,
                nonce: nonce
            });
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ucp_get_wishlist',
                    page_id: pageId,
                    nonce: nonce
                },
                success: function(response) {
                    console.log('願望清單數據載入成功:', response);
                    
                    if (response.success && response.data) {
                        // 新的數據結構：response.data 包含 items 數組和 count
                        var wishlistData = response.data.items || response.data;
                        var wishlistCount = response.data.count || (Array.isArray(wishlistData) ? wishlistData.length : 0);
                        
                        // 更新計數
                        $('#wishlist-count').text(wishlistCount);
                        
                        if (wishlistCount > 0) {
                            // 創建表格顯示願望清單項目
                            var tableHtml = '<table class="wishlist-table"><thead><tr>' +
                                '<th class="wishlist-table-row-number">NO.</th>' +
                                '<th class="wishlist-table-sku">SKU#</th>' +
                                '<th class="wishlist-table-image">Product Image</th>' +
                                '<th class="wishlist-table-product">Product Name</th>' +
                                '<th class="wishlist-table-action">Action</th>' +
                                '</tr></thead><tbody>';
                            
                            wishlistData.forEach(function(item, index) {
                                console.log('Processing wishlist item:', item);
                                var productImage = item.image || item.image_url || '/wp-content/plugins/woocommerce/assets/images/placeholder.png';
                                var productName = item.name || item.product_name || 'Unknown Product';
                                var productSku = item.sku || 'N/A';
                                var productId = item.product_id || item.id;
                                console.log('Product ID extracted:', productId);
                                
                                tableHtml += '<tr data-product-id="' + productId + '">' +
                                    '<td class="wishlist-table-row-number">' + (index + 1) + '</td>' +
                                    '<td class="wishlist-table-sku">' + productSku + '</td>' +
                                    '<td class="wishlist-table-image"><img src="' + productImage + '" alt="' + productName + '" style="width:50px;height:50px;object-fit:cover;"></td>' +
                                    '<td class="wishlist-table-product">' + productName + '</td>' +
                                    '<td class="wishlist-table-action">' +
                                        '<button class="remove-from-wishlist ucp-btn ucp-btn-sm" data-product-id="' + productId + '">Remove</button>' +
                                    '</td>' +
                                '</tr>';
                            });
                            
                            tableHtml += '</tbody></table>';
                            
                            // 添加發送按鈕
                            tableHtml += '<div class="wishlist-actions" style="margin-top:20px; text-align:center;">' +
                                '<button id="send-wishlist-btn" class="ucp-btn ucp-btn-primary">Send Wishlist</button>' +
                                '</div>';
                            
                            $('#wishlist-container').html(tableHtml);
                            
                            // 綁定發送按鈕事件
                            $('#send-wishlist-btn').on('click', function() {
                                sendWishlistEmail();
                            });
                            
                        } else {
                            $('#wishlist-container').html('<p>Your wishlist is empty.</p>');
                        }
                    } else {
                        $('#wishlist-container').html('<p>Error loading wishlist data.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('載入願望清單數據失敗:', error);
                    $('#wishlist-container').html('<p>Error loading wishlist. Please try again.</p>');
                }
            });
        }
        
        // 從願望清單移除商品 - 修復按鈕狀態管理
        function removeFromWishlist(productId) {
            var pageId = $('.ucp-page-container').data('page-id');
            var ajaxUrl = window.ajaxurl || (window.location.origin + window.location.pathname.split('/wp-content')[0] + '/wp-admin/admin-ajax.php');
            
            // 嘗試從多個來源獲取 nonce
            var nonce = window.ucp_ajax_nonce || 
                       $('meta[name="ucp-ajax-nonce"]').attr('content') || 
                       $('#ucp-ajax-nonce').val() || 
                       $('input[name="ucp_ajax_nonce"]').val() || 
                       '';
            
            console.log('Remove function called with:', {
                productId: productId,
                pageId: pageId,
                ajaxUrl: ajaxUrl,
                nonce: nonce
            });
            
            // 獲取當前處理中的按鈕
            var $removeBtn = $('.remove-from-wishlist[data-product-id="' + productId + '"]');
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ucp_remove_from_wishlist',
                    product_id: productId,
                    page_id: pageId,
                    nonce: nonce
                },
                success: function(response) {
                    console.log('Remove AJAX 完整回應:', response);
                    if (response.success) {
                        console.log('商品已從願望清單移除');
                        
                        // 更新產品列表中對應按鈕的狀態
                        var $productButton = $('#wishlist-btn-' + productId);
                        if ($productButton.length) {
                            // 將按鈕狀態改為 "Add to Wishlist"
                            $productButton.attr('data-action', 'add')
                                          .html('<i class="far fa-heart"></i> Add to Wishlist')
                                          .removeClass('processing');
                            
                            console.log('已更新產品 ' + productId + ' 的按鈕狀態為 Add');
                        }
                        
                        // 重新載入願望清單數據
                        loadWishlistData();
                        
                        // 更新頁面上的願望清單計數
                        if (response.data && response.data.count !== undefined) {
                            $('.wishlist-count').text(response.data.count);
                        }
                    } else {
                        console.log('移除失敗，錯誤訊息:', response.data ? response.data.message : '未知錯誤');
                        alert('Failed to remove item from wishlist: ' + (response.data ? response.data.message : 'Unknown error'));
                    }
                },
                error: function() {
                    alert('Error removing item from wishlist.');
                },
                complete: function() {
                    // 無論成功或失敗都要移除處理中狀態
                    $removeBtn.removeClass('processing').prop('disabled', false).text('Remove');
                }
            });
        }
        
        // 發送願望清單郵件
        function sendWishlistEmail() {
            var pageId = $('.ucp-page-container').data('page-id');
            var ajaxUrl = window.ajaxurl || (window.location.origin + window.location.pathname.split('/wp-content')[0] + '/wp-admin/admin-ajax.php');
            
            // 收集當前願望清單中的產品 ID
            var productIds = [];
            $('.wishlist-table tbody tr').each(function() {
                // 嘗試多種方式獲取產品 ID
                var productId = $(this).data('product-id') || 
                               $(this).find('.remove-from-wishlist').data('product-id') ||
                               $(this).attr('data-product-id');
                console.log('Found product ID:', productId);
                if (productId) {
                    productIds.push(productId);
                }
            });
            
            console.log('Send Wishlist - 收集到的產品 IDs:', productIds);
            
            if (productIds.length === 0) {
                alert('No products in wishlist to send.');
                return;
            }
            
            // 顯示載入狀態
            var $btn = $('#send-wishlist-btn');
            var originalText = $btn.text();
            $btn.text('Sending...').addClass('disabled').prop('disabled', true);
            
            $.ajax({
                url: ajaxUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'ucp_send_wishlist_email',
                    page_id: pageId,
                    nonce: window.ucp_ajax_nonce || '',
                    'product_ids[]': productIds
                },
                success: function(response) {
                    if (response.success) {
                        alert('Wishlist sent successfully!');
                    } else {
                        alert('Failed to send wishlist: ' + (response.data || 'Unknown error'));
                    }
                    // 恢復按鈕狀態
                    $btn.text(originalText).removeClass('disabled').prop('disabled', false);
                },
                error: function() {
                    alert('Error sending wishlist.');
                    // 恢復按鈕狀態
                    $btn.text(originalText).removeClass('disabled').prop('disabled', false);
                }
            });
        }
        
        // 初始化願望清單按鈕
        initWishlistButton();
        
        // 使用事件委託綁定 Remove 按鈕點擊事件 - 添加防阻塞機制
        $(document).on('click', '.remove-from-wishlist', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // 防止重複點擊
            if ($(this).hasClass('processing')) {
                return false;
            }
            
            var productId = $(this).data('product-id');
            console.log('Remove button clicked for product ID:', productId);
            
            if (productId) {
                // 設置處理中狀態
                $(this).addClass('processing').prop('disabled', true);
                var originalText = $(this).text();
                $(this).text('Removing...');
                
                removeFromWishlist(productId);
            } else {
                console.error('No product ID found for remove button');
            }
        });
        
        // 如果按鈕還不存在，等待一下再試
        setTimeout(function() {
            if (!$('#view-wishlist-btn').data('events')) {
                console.log('重試綁定願望清單按鈕...');
                initWishlistButton();
            }
        }, 1000);
        
    });
    
})(jQuery);
