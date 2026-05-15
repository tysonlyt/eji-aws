<?php
/**
 * Product Form Class for UCP Plugin
 *
 * Handles all form rendering and processing for product pages
 *
 * @package Unique_Client_Page
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product Form class for UCP plugin
 */
class UCP_Product_Form extends UCP_Base {
    
    /**
     * Initialize hooks
     */
    public function init() {
        // Handle form submissions
        add_action('admin_init', array($this, 'handle_form_submissions'));
    }
    
    /**
     * Handle all form submissions
     */
    public function handle_form_submissions() {
        $this->handle_create_page_request();
    }
    
    /**
     * Render create page form
     */
    public function render_create_page() {
        // Check user capability with basic requirement
        if (!current_user_can('read')) {
            wp_die(__('Sorry, you don\'t have sufficient permissions to access this page.', 'unique-client-page'));
        }
        
        // Ensure product selector resources are loaded
        $this->load_selector_resources();
        
        echo '<div class="wrap">';
        echo '<h1>' . __('Create New Product Page', 'unique-client-page') . '</h1>';
        
        echo '<form method="post" action="">';
        // Add security verification
        wp_nonce_field('create_unique_client_page', 'ucp_create_nonce');
        echo '<input type="hidden" name="action" value="create_unique_client_page">';
        
        // Render form fields using the shared function
        $this->render_product_form_fields();
        
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Create Page', 'unique-client-page') . '"></p>';
        echo '</form>';
        
        // Render product selector modal
        $this->render_product_selector_modal();
        
        // 添加基本的样式来确保模态框正确显示
    ?>
    <style>
    /* 基本模态框样式 - 使用!important覆盖所有其他样式 */
    #ucp-product-selector-modal {
        display: none;
    }
    
    #ucp-product-selector-modal.ucp-show-modal {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        z-index: 999999 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        height: 100% !important;
        overflow: auto !important;
        background-color: rgba(0,0,0,0.5) !important;
    }
    
    #ucp-product-selector-modal .ucp-modal-container {
        background-color: #fefefe !important;
        margin: 5% auto !important;
        padding: 20px !important;
        width: 90% !important;
        max-width: 1000px !important;
        max-height: 80vh !important;
        overflow: auto !important;
        position: relative !important;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19) !important;
    }
    
    /* 确保模态框内部元素正确显示 */
    #ucp-product-selector-modal .ucp-modal-header,
    #ucp-product-selector-modal .ucp-modal-content,
    #ucp-product-selector-modal .ucp-modal-footer {
        display: block !important;
        visibility: visible !important;
    }
    </style>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        console.log('UCP: Page loaded with direct UCPAdminModalController integration');
        
        // Debug functionality removed
        
        function debugLog(message) {
            // 只在控制台输出，不在页面上显示
            console.log('UCP Debug: ' + message);
        }
        
        debugLog('JavaScript loaded');
        
        // Add delay to ensure UCPAdminModalController is loaded
        setTimeout(function() {
            // Verify UCPAdminModalController is available
            if (window.UCPAdminModalController && typeof window.UCPAdminModalController.openModal === 'function') {
                debugLog('SUCCESS: UCPAdminModalController is available');
            } else {
                debugLog('ERROR: UCPAdminModalController is not available');
            }
        }, 500);
        
        // Handle Select Products button click
        $('#ucp-select-products-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            debugLog('Select Products button clicked');
            
            // Get modal element
            var $modal = $('#ucp-product-selector-modal');
            if ($modal.length === 0) {
                debugLog('ERROR: Modal element not found!');
                return false;
            }
            
            // 首先确保模态框是隐藏的，这样可以重置所有状态
            $modal.css({
                'display': 'none',
                'visibility': 'hidden',
                'opacity': '0'
            }).removeClass('show active fullscreen');
            
            // 移除可能影响模态框显示的类
            $('body').removeClass('ucp-modal-open modal-open');
            
            // 短延迟后再打开模态框，确保DOM有时间更新
            setTimeout(function() {
                if (window.UCPAdminModalController) {
                    debugLog('Using UCPAdminModalController.openModal with delay');
                    window.UCPAdminModalController.openModal($modal);
                } else {
                    debugLog('ERROR: UCPAdminModalController not available');
                }
            }, 50);
            
            return false;
        });
        
        // Simplified close button handler
        $('.ucp-close-modal, .ucp-modal-overlay').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Get modal element
            var $modal = $('#ucp-product-selector-modal');
            
            // Direct hide method
            $modal.css({
                'display': 'none',
                'opacity': '0',
                'visibility': 'hidden'
            }).removeClass('show');
            
            console.log('UCP Debug: Modal closed');
            return false;
        });
        
        // 使用直接事件绑定处理生成随机码按钮
        $(document).ready(function() {
            // 直接绑定事件处理器
            $(document).on('click', '#ucp-access-code-generator', function(e) {
                // 阻止事件传播和默认行为
                e.preventDefault();
                e.stopPropagation();
                
                // 生成随机8位字母数字组合的访问码
                var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var code = '';
                for (var i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                // 设置访问码到输入字段
                $('#ucp_access_code').val(code);
                
                console.log('UCP Debug: 已生成随机访问码: ' + code);
                alert('随机访问码已生成: ' + code);
                return false;
            });
            
            console.log('UCP Debug: 访问码生成器事件绑定完成');
        });
        
        // 添加直接的内联样式
        $('<style>\n'+
        '.ucp-custom-button { z-index: 999; position: relative; }\n'+
        '.ucp-access-code-field { z-index: 999; position: relative; }\n'+
        '</style>').appendTo('head');
        
                // 确保只初始化一次
        if (typeof window.UCP_Debug === 'undefined') {
            // 调试工具类
            window.UCP_Debug = {
                enabled: true,
                initialized: false,
                log: function(message, data) {
                    if (!this.enabled) return;
                    const timestamp = new Date().toISOString().substr(11, 12);
                    const logMessage = `[UCP ${timestamp}] ${message}`;
                    console.log(logMessage, data || '');
                    
                    // 添加到调试面板
                    if (!$('#ucp-debug-panel').length) {
                        $('body').append(`
                            <div id="ucp-debug-container" style="position:fixed;bottom:0;right:0;z-index:999999;width:300px;">
                                <div style="background:#333;color:#fff;padding:5px 10px;cursor:pointer;" id="ucp-debug-toggle">
                                    UCP Debug ▲
                                </div>
                                <div id="ucp-debug-panel" style="display:none;height:200px;overflow:auto;background:rgba(0,0,0,0.9);color:#0f0;padding:10px;font-family:monospace;font-size:11px;line-height:1.4;">
                                </div>
                            </div>
                        `);
                        
                        // 切换面板显示/隐藏
                        $('#ucp-debug-toggle').on('click', function() {
                            const $panel = $('#ucp-debug-panel');
                            $panel.toggle();
                            $(this).html('UCP Debug ' + ($panel.is(':visible') ? '▼' : '▲'));
                        });
                    }
                    
                    // 添加日志
                    const $logEntry = $('<div>').text(logMessage);
                    if (data && Object.keys(data).length > 0) {
                        $logEntry.append($('<pre>').text(JSON.stringify(data, null, 2)));
                    }
                    
                    $('#ucp-debug-panel').prepend($logEntry);
                    
                    // 限制日志数量
                    const $logs = $('#ucp-debug-panel > div');
                    if ($logs.length > 50) {
                        $logs.last().remove();
                    }
                },
                error: function(message, error) {
                    this.log(`[ERROR] ${message}`, error);
                },
                init: function() {
                    if (this.initialized) return;
                    this.initialized = true;
                    this.log('UCP 调试工具已初始化', {
                        jQueryVersion: $.fn.jquery,
                        ucpParams: typeof ucp_params !== 'undefined' ? '已加载' : '未加载',
                        timestamp: new Date().toISOString()
                    });
                }
            };
            
            // 初始化调试工具
            UCP_Debug.init();
            
            // 添加全局错误处理
            window.addEventListener('error', function(e) {
                UCP_Debug.error('全局错误捕获', {
                    message: e.message,
                    filename: e.filename,
                    lineno: e.lineno,
                    colno: e.colno
                });
            });
            
            // 处理未捕获的Promise错误
            window.addEventListener('unhandledrejection', function(e) {
                UCP_Debug.error('未处理的Promise拒绝', {
                    reason: e.reason,
                    message: e.reason?.message || '无错误信息'
                });
            });
        }
        
        // 处理Access code相关交互
        (function() {
            // 确保只绑定一次
            if ($._data($('#ucp_access_code')[0], 'events')?.click) {
                UCP_Debug.log('事件处理器已存在，跳过重复绑定');
                return;
            }
            
            // 生成随机访问码
            function generateAccessCode() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let code = '';
                for (let i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                const $input = $('#ucp_access_code');
                $input.val(code).trigger('change');
                UCP_Debug.log('已生成访问码', { code: code });
                
                // 添加视觉反馈
                const originalBackground = $input.css('background-color');
                $input.css('background-color', '#e6ffe6')
                      .animate({ backgroundColor: originalBackground }, 1000);
                
                return code;
            }
            
            // 绑定事件
            $(document)
                .off('click.ucp_access_code', '#ucp_access_code, #ucp-access-code-generator')
                .on('click.ucp_access_code', '#ucp_access_code, #ucp-access-code-generator', function(e) {
                    UCP_Debug.log('Access code 元素点击', { 
                        id: this.id,
                        tagName: this.tagName,
                        className: this.className,
                        time: new Date().toISOString()
                    });
                    
                    // 阻止事件冒泡和默认行为
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    // 如果是生成按钮，执行生成逻辑
                    if (this.id === 'ucp-access-code-generator') {
                        generateAccessCode();
                    }
                    
                    return false;
                });
                
            UCP_Debug.log('Access code 事件处理器已绑定');
        })();
        
        // Add global ESC key handler
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27) { // ESC key
                var $modal = $('#ucp-product-selector-modal');
                if ($modal.is(':visible')) {
                    $modal.css({
                        'display': 'none',
                        'opacity': '0',
                        'visibility': 'hidden'
                    }).removeClass('show');
                    console.log('UCP Debug: Modal closed via ESC key');
                }
            }
        });
    });
    </script>
    <?php
        
        echo '</div>';
    }
    
    /**
     * Render edit page form
     */
    public function render_edit_page() {
        // Check if page ID is provided
        if (!isset($_GET['page_id']) || empty($_GET['page_id'])) {
            wp_die(__('Missing page ID parameter.', 'unique-client-page'));
        }
        
        $page_id = intval($_GET['page_id']);
        $page = get_post($page_id);
        
        // Check if page exists and uses our template
        if (!$page || $page->post_type !== 'page' || get_post_meta($page_id, '_wp_page_template', true) !== 'unique-client-template.php') {
            wp_die(__('Cannot find the specified product page.', 'unique-client-page'));
        }
        
        // Ensure product selector resources are loaded
        $this->load_selector_resources();
        
        // Extract shortcode parameters from page content
        $shortcode_params = array();
        $selected_products = '';
        $product_limit = 12;
        $product_columns = 4;
        $page_content = '';
        
        // Get sale name, email, access code and page content from post meta
        $sale_name = get_post_meta($page_id, '_ucp_sale_name', true);
        $sale_email = get_post_meta($page_id, '_ucp_sale_email', true);
        $access_code = get_post_meta($page_id, '_ucp_access_code', true);
        $page_content = get_post_meta($page_id, '_ucp_page_content', true);
        
        // Get product IDs from post_meta (Single Source of Truth)
        $product_ids = get_post_meta($page_id, '_client_products', true);
        
        // If post_meta doesn't exist, fallback to parsing shortcode (backward compatibility)
        if (empty($product_ids)) {
            // Parse shortcode from post_content
            if (preg_match('/\[unique_client_products([^\]]*)\]/', $page->post_content, $matches)) {
                if (isset($matches[1])) {
                    $shortcode_attrs = $matches[1];
                    
                    // Parse ids parameter
                    if (preg_match('/ids="([^"]+)"/', $shortcode_attrs, $ids_match)) {
                        $selected_products = $ids_match[1];
                    }
                    
                    // Parse per_page parameter
                    if (preg_match('/per_page="(\d+)"/', $shortcode_attrs, $limit_match)) {
                        $product_limit = intval($limit_match[1]);
                    }
                    
                    // Parse columns parameter
                    if (preg_match('/columns="(\d+)"/', $shortcode_attrs, $columns_match)) {
                        $product_columns = intval($columns_match[1]);
                    }
                }
            }
        } else {
            // Use product IDs from post_meta
            if (is_array($product_ids)) {
                $selected_products = implode(',', $product_ids);
            } else {
                $selected_products = $product_ids;
            }
            
            // Still parse other parameters from shortcode
            if (preg_match('/\[unique_client_products([^\]]*)\]/', $page->post_content, $matches)) {
                if (isset($matches[1])) {
                    $shortcode_attrs = $matches[1];
                    
                    // Parse per_page parameter
                    if (preg_match('/per_page="(\d+)"/', $shortcode_attrs, $limit_match)) {
                        $product_limit = intval($limit_match[1]);
                    }
                    
                    // Parse columns parameter
                    if (preg_match('/columns="(\d+)"/', $shortcode_attrs, $columns_match)) {
                        $product_columns = intval($columns_match[1]);
                    }
                }
            }
        }
        
        // Prepare form data for rendering
        $form_data = array(
            'page_title' => $page->post_title,
            'sale_name' => $sale_name,
            'sale_email' => $sale_email,
            'access_code' => $access_code,
            'product_limit' => $product_limit,
            'product_columns' => $product_columns,
            'page_content' => $page_content,
            'selected_products' => $selected_products
        );
        
        // Begin output edit form
        echo '<div class="wrap">';
        echo '<h1>' . __('Edit Product Page', 'unique-client-page') . '</h1>';
        
        echo '<form method="post" action="">';
        // Add security verification
        wp_nonce_field('create_unique_client_page', 'ucp_create_nonce');
        echo '<input type="hidden" name="action" value="create_unique_client_page">';
        echo '<input type="hidden" name="page_id" value="' . $page_id . '">';
        
        // Render form fields using the shared function
        $this->render_product_form_fields($form_data);
        
        echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="' . __('Update Page', 'unique-client-page') . '"></p>';
        echo '</form>';
        
        // Render product selector modal
        $this->render_product_selector_modal($page_id);
        
        // Add JavaScript for interaction
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            console.log('UCP: Edit page loaded');
            
            // Ensure the form group is clickable for product selection
            $('.ucp-form-group').on('click', function(e) {
                e.preventDefault();
                
                // Show the modal
                $('#ucp-product-selector-modal').css({
                    'display': 'block',
                    'position': 'fixed',
                    'z-index': '999999',
                    'top': '0',
                    'left': '0',
                    'width': '100%',
                    'height': '100%'
                });
                
                // Show modal with animation
                $('#ucp-product-selector-modal').addClass('show');
            });
            
            // Ensure close button works
            $('.ucp-close-modal, .ucp-modal-overlay').on('click', function() {
                $('#ucp-product-selector-modal').removeClass('show').css('display', 'none');
            });
        });
        </script>
        <?php
        
        echo '</div>';
    }
    
    /**
     * Renders the form fields for creating or editing product pages
     *
     * @param array $data Form data for pre-filling fields
     * @return void
     */
    public function render_product_form_fields($data = []) {
        // Extract form values with defaults
        $page_title = isset($data['page_title']) ? $data['page_title'] : '';
        $sale_name = isset($data['sale_name']) ? $data['sale_name'] : '';
        $sale_email = isset($data['sale_email']) ? $data['sale_email'] : '';
        $product_limit = isset($data['product_limit']) ? $data['product_limit'] : 12;
        $product_columns = isset($data['product_columns']) ? $data['product_columns'] : 4;
        $page_content = isset($data['page_content']) ? $data['page_content'] : '';
        $selected_products = isset($data['selected_products']) ? $data['selected_products'] : '';
        $selected_count = !empty($selected_products) ? count(explode(',', $selected_products)) : 0;
        $access_code = isset($data['access_code']) ? $data['access_code'] : '';
        
        echo '<table class="form-table">';
        echo '<tr>';
        echo '<th scope="row"><label for="page_title">' . __('Client Name', 'unique-client-page') . '</label></th>';
        echo '<td><input type="text" id="page_title" name="page_title" class="regular-text" required value="' . esc_attr($page_title) . '"></td>';
        echo '</tr>';
        
        // Sale Name field
        echo '<tr>';
        echo '<th scope="row"><label for="sale_name">' . __('Sale Name', 'unique-client-page') . '</label></th>';
        echo '<td><input type="text" id="sale_name" name="sale_name" class="regular-text" value="' . esc_attr($sale_name) . '"></td>';
        echo '</tr>';
        
        // Sale Email field
        echo '<tr>';
        echo '<th scope="row"><label for="sale_email">' . __('Sale Email', 'unique-client-page') . '</label></th>';
        echo '<td><input type="email" id="sale_email" name="sale_email" class="regular-text" value="' . esc_attr($sale_email) . '"></td>';
        echo '</tr>';
        
        // Product display limit
        echo '<tr>';
        echo '<th scope="row"><label for="product_limit">' . __('Products Per Page', 'unique-client-page') . '</label></th>';
        echo '<td><input type="number" id="product_limit" name="product_limit" class="small-text" min="1" max="50" value="' . esc_attr($product_limit) . '"></td>';
        echo '</tr>';
        
        // Product columns
        echo '<tr>';
        echo '<th scope="row"><label for="product_columns">' . __('Product Columns', 'unique-client-page') . '</label></th>';
        echo '<td>';
        echo '<select id="product_columns" name="product_columns">';
        for ($i = 2; $i <= 6; $i++) {
            $selected = ($i == $product_columns) ? 'selected' : '';
            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        
        // Access Code field
        echo '<tr>';
        echo '<th scope="row"><label for="ucp_access_code">' . __('Access Code', 'unique-client-page') . '</label></th>';
        echo '<td>';
        echo '<div class="ucp-form-group">';
        echo '<div style="display:flex;align-items:center;gap:10px;">';
        echo '<input type="text" id="ucp_access_code" name="access_code" class="regular-text ucp-access-code-field" value="' . esc_attr($access_code) . '" placeholder="' . __('Leave empty for public access', 'unique-client-page') . '" style="flex:1;">';
        echo '<button type="button" class="button button-secondary ucp-custom-button" id="ucp-copy-code" style="white-space:nowrap;">' . __('Copy', 'unique-client-page') . '</button>';
        echo '<button type="button" class="button button-secondary ucp-custom-button" id="ucp-access-code-generator" style="white-space:nowrap;">' . ($access_code ? __('Generate Again', 'unique-client-page') : __('Generate Code', 'unique-client-page')) . '</button>';
        echo '</div>';
        
        // 添加内联JS函数
        echo '<script type="text/javascript">
        jQuery(document).ready(function($) {
            // 复制访问码功能
            $("#ucp-copy-code").on("click", function(e) {
                // 阻止事件冒泡和默认行为
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // 复制文本到剪贴板
                var copyText = document.getElementById("ucp_access_code");
                copyText.select();
                copyText.setSelectionRange(0, 99999); // 移动设备支持
                document.execCommand("copy");
                
                // 显示复制成功提示
                var originalText = $(this).text();
                $(this).text("' . __('Copied!', 'unique-client-page') . '");
                setTimeout(function() {
                    $("#ucp-copy-code").text(originalText);
                }, 2000);
                
                // 返回false阻止默认行为和事件冒泡
                return false;
            });

            // 生成访问码功能
            function generateAccessCode() {
                var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                var code = "";
                for (var i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                
                var $input = $("#ucp_access_code");
                $input.val(code).trigger("change");
                
                // 更新按钮文本
                $("#ucp-access-code-generator").text("' . __('Generate Again', 'unique-client-page') . '");
                
                // 视觉反馈
                $input.css("background-color", "#e6ffe6").animate({backgroundColor: "white"}, 1000);
                
                return false;
            }
            
            // 绑定生成按钮点击事件
            $("#ucp-access-code-generator").on("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                generateAccessCode();
                return false;
            });
            
            // 阻止输入框点击事件冒泡
            $("#ucp_access_code").on("click", function(e) {
                e.stopPropagation();
            });
        });
        </script>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
        
        // Page content (optional)
        echo '<tr>';
        echo '<th scope="row"><label for="page_content">' . __('Page Content (optional)', 'unique-client-page') . '</label></th>';
        echo '<td><textarea id="page_content" name="page_content" rows="5" class="large-text">' . esc_textarea($page_content) . '</textarea></td>';
        echo '</tr>';
        
        // Selected products information with Select Products button
        echo '<tr>';
        echo '<th scope="row"><label>' . __('Selected Products', 'unique-client-page') . '</label></th>';
        echo '<td>';
        echo '<div class="ucp-form-group">';
        echo '<input type="hidden" id="ucp-selected-products" name="selected_products" value="' . esc_attr($selected_products) . '">';
        echo '<button type="button" class="button button-secondary ucp-select-products-btn" id="ucp-select-products-btn">' . __('Select Products', 'unique-client-page') . '</button> ';
        echo '<span class="ucp-selected-info" style="display: inline-block; margin-left: 10px;">Selected <span class="ucp-selected-count">' . $selected_count . '</span> products</span>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
        
        echo '</table>';
    }
    
    /**
     * Handle create page request
     */
    public function handle_create_page_request() {
        // Check if this is our form submission
        if (!isset($_POST['action']) || $_POST['action'] != 'create_unique_client_page') {
            return;
        }
        
        // Verify nonce
        if (!isset($_POST['ucp_create_nonce']) || !wp_verify_nonce($_POST['ucp_create_nonce'], 'create_unique_client_page')) {
            wp_die(__('Security verification failed', 'unique-client-page'));
        }
        
        // Check user permissions
        if (!current_user_can('edit_pages')) {
            wp_die(__('You do not have sufficient permissions', 'unique-client-page'));
        }
        
        // Get form data
        $page_title = isset($_POST['page_title']) ? sanitize_text_field($_POST['page_title']) : '';
        $product_category = isset($_POST['product_category']) ? sanitize_text_field($_POST['product_category']) : '';
        $product_limit = isset($_POST['product_limit']) ? intval($_POST['product_limit']) : 12;
        $product_columns = isset($_POST['product_columns']) ? intval($_POST['product_columns']) : 4;
        $page_content = isset($_POST['page_content']) ? wp_kses_post($_POST['page_content']) : '';
        $selected_products = isset($_POST['selected_products']) ? sanitize_text_field($_POST['selected_products']) : '';
        $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0; // Get page ID (if exists)
        
        // Get sale name and email
        $sale_name = isset($_POST['sale_name']) ? sanitize_text_field($_POST['sale_name']) : '';
        $sale_email = isset($_POST['sale_email']) ? sanitize_email($_POST['sale_email']) : '';
        
        // Get access code
        $access_code = isset($_POST['access_code']) ? sanitize_text_field($_POST['access_code']) : '';
        
        // Log debug info
        error_log('UCP: Handling page request - Page ID=' . $page_id);
        error_log('UCP: Handling page request - Selected products=' . $selected_products);
        
        // Validate required fields
        if (empty($page_title)) {
            wp_die(__('Page title cannot be empty', 'unique-client-page'));
        }
        
        // Create shortcode
        $shortcode = '[unique_client_products';
        
        // If product IDs are selected, prioritize them
        if (!empty($selected_products)) {
            $shortcode .= ' ids="' . esc_attr($selected_products) . '"';
        } else {
            // If no products selected, use category and other settings
            if (!empty($product_category)) {
                $shortcode .= ' category="' . esc_attr($product_category) . '"';
            }
        }
        
        // Load count and columns don't conflict with product selection, can be specified simultaneously
        if ($product_limit != 12) {
            $shortcode .= ' per_page="' . esc_attr($product_limit) . '"';
        }
        if ($product_columns != 4) {
            $shortcode .= ' columns="' . esc_attr($product_columns) . '"';
        }
        $shortcode .= ']';
        
        // Create page content
        // Note: page_content (sales message) is saved separately in post_meta
        // post_content only contains the shortcode
        $content = $shortcode;
        
        // Ensure template file is copied to theme directory
        $this->copy_template_file();
        
        // Create or update page
        $page_args = array(
            'post_title' => $page_title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'page',
            'meta_input' => array(
                '_wp_page_template' => 'unique-client-template.php',
                '_ucp_sale_name' => $sale_name,
                '_ucp_sale_email' => $sale_email,
                '_ucp_access_code' => $access_code,
                '_ucp_page_content' => $page_content
            )
        );
        
        // If page ID exists, update existing page instead of creating new one
        if (!empty($page_id)) {
            $page_args['ID'] = $page_id;
            $page_id = wp_update_post($page_args);
            error_log('UCP: Updated existing page ID=' . $page_id);
            
            // Update sale metadata separately to ensure it's updated
            update_post_meta($page_id, '_ucp_sale_name', $sale_name);
            update_post_meta($page_id, '_ucp_sale_email', $sale_email);
            update_post_meta($page_id, '_ucp_access_code', $access_code);
            
            // Redirect to admin page with success message
            wp_redirect(admin_url('admin.php?page=unique-client-page&updated=true'));
            exit;
        } else {
            $page_id = wp_insert_post($page_args);
            error_log('UCP: Created new page ID=' . $page_id);
            
            if (!is_wp_error($page_id)) {
                // Redirect to unique-client-page plugin main page
                wp_redirect(admin_url('admin.php?page=unique-client-page'));
                exit;
            }
        }
    }
    /**
     * Copy template file to theme directory
     * This is important for themes that look for templates in the theme directory
     */
    public function copy_template_file() {
        $source_file = plugin_dir_path(dirname(__FILE__)) . 'templates/unique-client-template.php';
        $destination_file = get_template_directory() . '/unique-client-template.php';
        
        // If template file doesn't exist in theme directory, copy it
        if (!file_exists($destination_file)) {
            copy($source_file, $destination_file);
        }
    }
    
    /**
     * Load product selector resources
     * Uses the UCP_Product_Selector component
     */
    public function load_selector_resources() {
        // Get the main instance
        $main = UCP_Main::get_instance();
        
        // Get the selector component and call its method
        $selector = $main->get_selector();
        if ($selector && method_exists($selector, 'load_selector_resources')) {
            $selector->load_selector_resources();
        } else {
            // Fallback - load minimum required resources
            wp_enqueue_style('dashicons');
            wp_enqueue_script('jquery');
            
            // Log error for debugging
            error_log('UCP: Selector component not found or missing method');
        }
    }
    
    /**
     * Render product selector modal
     * Uses the UCP_Product_Selector component
     * 
     * @param int $page_id Page ID, used for adding products to the page
     */
    public function render_product_selector_modal($page_id = 0) {
        // Get the main instance
        $main = UCP_Main::get_instance();
        
        // Get the selector component and call its method
        $selector = $main->get_selector();
        if ($selector && method_exists($selector, 'render_product_selector_modal')) {
            $selector->render_product_selector_modal($page_id);
        } else {
            // Display error message if selector not found
            echo '<div class="notice notice-error"><p>' . 
                 __('Product selector component not found. Please contact support.', 'unique-client-page') . 
                 '</p></div>';
            
            // Log error for debugging
            error_log('UCP: Selector component not found or missing method');
        }
    }
}
