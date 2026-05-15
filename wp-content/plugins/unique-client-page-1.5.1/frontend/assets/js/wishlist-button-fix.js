/**
 * Wishlist Button Fix - 增强版
 * 
 * 这个脚本修复心愿单按钮的发送状态显示问题
 * 使用多种方法确保按钮文本正确显示为"Resend Wishlist to Sales"
 */
jQuery(document).ready(function($) {
    console.log('心愿单按钮修复脚本已加载 - 增强版');
    
    // 立即运行一次状态检查，获取心愿单状态
    checkWishlistStatus();
    
    // 每秒检查一次按钮状态，确保不被其他代码覆盖
    setInterval(function() {
        forceButtonText();
    }, 1000);
    
    // 监听DOM变化，当心愿单模态框显示时进行修复
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                for (var i = 0; i < mutation.addedNodes.length; i++) {
                    var node = mutation.addedNodes[i];
                    if (node.id === 'wishlist-modal' || $(node).find('#wishlist-modal').length > 0) {
                        console.log('检测到心愿单模态框，应用修复...');
                        setTimeout(checkWishlistStatus, 500);
                    }
                    if (node.id === 'send-wishlist-btn' || $(node).find('#send-wishlist-btn').length > 0) {
                        console.log('检测到发送心愿单按钮，应用修复...');
                        setTimeout(forceButtonText, 200);
                    }
                }
            }
        });
    });
    
    // 开始观察整个文档
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // 劫持原始的点击事件处理函数
    $(document).on('click', '#send-wishlist-btn', function() {
        console.log('拦截到发送心愿单按钮点击事件');
        // 在这里我们只记录，不阻止默认行为
    });
    
    // 监听AJAX请求完成事件
    $(document).ajaxComplete(function(event, xhr, settings) {
        console.log('AJAX请求完成：', settings.url);
        
        // 处理心愿单相关请求
        if (settings.url && settings.url.indexOf('admin-ajax.php') > -1) {
            try {
                // 尝试解析响应数据
                var response = JSON.parse(xhr.responseText);
                console.log('AJAX响应数据：', response);
                
                // 处理心愿单发送请求
                if (settings.data && settings.data.indexOf('ucp_send_wishlist_email') > -1) {
                    console.log('检测到心愿单发送请求完成');
                    if (response.success) {
                        console.log('心愿单发送成功，5秒后将更新按钮文本');
                        // 5秒后，保持与原代码中的成功消息显示时间一致
                        setTimeout(function() {
                            $('#send-wishlist-btn').text('Resend Wishlist to Sales');
                            console.log('按钮文本已更新为：Resend Wishlist to Sales');
                        }, 5000);
                        
                        // 同时更新存储的状态，表示心愿单已发送
                        try {
                            localStorage.setItem('wishlist_sent_' + getCurrentPageId(), 'yes');
                            localStorage.setItem('wishlist_last_sent_' + getCurrentPageId(), new Date().toISOString());
                            console.log('已将发送状态保存到本地存储');
                        } catch (e) {
                            console.error('保存状态到本地存储时出错：', e);
                        }
                    }
                }
                
                // 处理获取心愿单数据请求
                if (settings.data && settings.data.indexOf('ucp_get_wishlist') > -1) {
                    console.log('检测到获取心愿单数据请求完成');
                    if (response.success) {
                        var wishlistSent = response.data.wishlist_sent;
                        console.log('服务器返回的心愿单发送状态：', wishlistSent);
                        
                        if (wishlistSent) {
                            setTimeout(function() {
                                $('#send-wishlist-btn').text('Resend Wishlist to Sales');
                                console.log('基于服务器数据更新按钮文本为：Resend Wishlist to Sales');
                            }, 200);
                        }
                    }
                }
            } catch (e) {
                console.error('处理AJAX响应时出错：', e);
            }
        }
    });
    
    // 检查心愿单状态函数
    function checkWishlistStatus() {
        console.log('检查心愿单发送状态...');
        
        // 1. 首先检查本地存储
        var pageId = getCurrentPageId();
        var sentStatus = localStorage.getItem('wishlist_sent_' + pageId);
        
        if (sentStatus === 'yes') {
            console.log('本地存储显示心愿单已发送');
            $('#send-wishlist-btn').text('Resend Wishlist to Sales');
            return;
        }
        
        // 2. 如果本地存储没有，发送AJAX请求检查服务器状态
        if (typeof window.ucp_params === 'undefined' || !window.ucp_params.ajax_url) {
            console.error('Unable to get AJAX URL, cannot check server status. ucp_params is not properly defined.');
            return;
        }
        
        var ajaxUrl = window.ucp_params.ajax_url;
        var nonce = window.ucp_params.nonce || '';
        
        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'ucp_get_wishlist_status',
                nonce: nonce,
                page_id: pageId
            },
            success: function(response) {
                console.log('获取心愿单状态响应：', response);
                if (response.success && response.data && response.data.wishlist_sent) {
                    $('#send-wishlist-btn').text('Resend Wishlist to Sales');
                    console.log('根据服务器状态更新按钮文本');
                    
                    // 保存到本地存储
                    try {
                        localStorage.setItem('wishlist_sent_' + pageId, 'yes');
                        if (response.data.wishlist_last_sent) {
                            localStorage.setItem('wishlist_last_sent_' + pageId, response.data.wishlist_last_sent);
                        }
                    } catch (e) {}
                }
            },
            error: function(xhr, status, error) {
                console.error('获取心愿单状态失败：', error);
            }
        });
    }
    
    // 强制设置按钮文本函数
    function forceButtonText() {
        var $btn = $('#send-wishlist-btn');
        if ($btn.length > 0 && $btn.text() !== 'Sending...' && 
            $btn.text() !== 'Your wishlist has been successfully sent!' &&
            $btn.text() !== 'Resend Wishlist to Sales') {
            
            var pageId = getCurrentPageId();
            var sentStatus = localStorage.getItem('wishlist_sent_' + pageId);
            
            if (sentStatus === 'yes') {
                console.log('强制设置按钮文本为：Resend Wishlist to Sales');
                $btn.text('Resend Wishlist to Sales');
            }
        }
    }
    
    // 获取当前页面ID函数
    function getCurrentPageId() {
        // 尝试从各种可能的来源获取页面ID
        var pageId = 0;
        
        // 从.ucp-page-container元素的data-page-id属性获取
        var $container = $('.ucp-page-container');
        if ($container.length > 0 && $container.data('page-id')) {
            pageId = $container.data('page-id');
        }
        
        // 从隐藏字段获取
        if (!pageId && $('#current-page-id').length > 0) {
            pageId = $('#current-page-id').val();
        }
        
        // 从全局变量获取
        if (!pageId && window.ucpCurrentPageId) {
            pageId = window.ucpCurrentPageId;
        }
        
        // 从本地存储获取
        if (!pageId) {
            try {
                pageId = localStorage.getItem('ucp_last_page_id');
            } catch (e) {}
        }
        
        console.log('当前页面ID：', pageId);
        return pageId;
    }
});
