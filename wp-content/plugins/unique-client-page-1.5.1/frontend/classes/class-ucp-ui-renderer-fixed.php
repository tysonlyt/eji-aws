<?php
/**
 * UI Renderer Component - 修复版
 *
 * Handles the rendering of all UI elements and frontend HTML generation
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * UI Renderer Component
 */
class UCP_UI_Renderer_Fixed {
    /**
     * Class instance
     *
     * @var UCP_UI_Renderer_Fixed
     */
    private static $instance = null;
    
    /**
     * Reference to debug manager
     * 
     * @var UCP_Debug_Manager
     */
    private $debug_manager = null;
    
    /**
     * Get the singleton instance
     *
     * @return UCP_UI_Renderer_Fixed instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Get reference to debug manager
        if (class_exists('UCP_Debug_Manager')) {
            $this->debug_manager = UCP_Debug_Manager::get_instance();
        }
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // No direct hooks needed at this time
        // This component is called directly by other components
    }

    /**
     * Render wishlist items - 修复版本
     *
     * @param array $items Wishlist items
     * @param string $wishlist_key Wishlist key
     * @param int $page_id Page ID
     * @return string HTML output
     */
    public function render_wishlist_items($items, $wishlist_key, $page_id) {
        if (empty($items)) {
            return '<p class="ucp-empty-wishlist">' . __('No items in your wishlist', 'unique-client-page') . '</p>';
        }
        
        ob_start();
        
        // 添加标题行和表格结构
        echo '<div class="ucp-wishlist-container">';
        echo '<table class="ucp-wishlist-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="ucp-wishlist-no">' . __('NO.', 'unique-client-page') . '</th>';
        echo '<th class="ucp-wishlist-sku">' . __('SKU#', 'unique-client-page') . '</th>';
        echo '<th class="ucp-wishlist-image">' . __('PRODUCT IMAGE', 'unique-client-page') . '</th>';
        echo '<th class="ucp-wishlist-name">' . __('PRODUCT NAME', 'unique-client-page') . '</th>';
        echo '<th class="ucp-wishlist-action">' . __('ACTION', 'unique-client-page') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        $counter = 1;
        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            // 获取SKU
            $sku = $product->get_sku();
            if (empty($sku)) {
                $sku = 'N/A';
            }
            
            // 确保图片正确加载 - 使用WooCommerce函数获取图片HTML
            $image = $product->get_image('thumbnail', array('class' => 'ucp-wishlist-product-image'));
            if (empty($image)) {
                // 如果WooCommerce函数失败，尝试手动获取图片
                $image_id = $product->get_image_id();
                if ($image_id) {
                    $image = wp_get_attachment_image($image_id, 'thumbnail', false, array('class' => 'ucp-wishlist-product-image'));
                } else {
                    // 使用占位符图片
                    $image = '<img src="' . wc_placeholder_img_src('thumbnail') . '" class="ucp-wishlist-product-image" alt="' . esc_attr__('Placeholder', 'unique-client-page') . '">';
                }
            }
            
            echo '<tr class="ucp-wishlist-item" data-product-id="' . esc_attr($product_id) . '">';
            
            // 序号
            echo '<td class="ucp-wishlist-no">' . $counter++ . '</td>';
            
            // SKU
            echo '<td class="ucp-wishlist-sku">' . esc_html($sku) . '</td>';
            
            // 产品图片
            echo '<td class="ucp-wishlist-image">' . $image . '</td>';
            
            // 产品名称
            echo '<td class="ucp-wishlist-name">' . esc_html($product->get_name()) . '</td>';
            
            // 操作按钮
            echo '<td class="ucp-wishlist-action">';
            echo '<button type="button" class="ucp-remove-from-wishlist button" 
                data-product-id="' . esc_attr($product_id) . '" 
                data-page-id="' . esc_attr($page_id) . '" 
                data-wishlist-key="' . esc_attr($wishlist_key) . '" 
                data-nonce="' . wp_create_nonce('ucp-ajax-nonce') . '">
                ' . __('Remove', 'unique-client-page') . '
            </button>';
            echo '</td>';
            
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // .ucp-wishlist-container
        
        // 修改JavaScript，防止Remove按钮关闭页面
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var removeButtons = document.querySelectorAll(".ucp-remove-from-wishlist");
            
            for (var i = 0; i < removeButtons.length; i++) {
                removeButtons[i].addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // 停止事件冒泡
                    
                    var btn = this;
                    var productId = btn.getAttribute("data-product-id");
                    var pageId = btn.getAttribute("data-page-id");
                    var wishlistKey = btn.getAttribute("data-wishlist-key");
                    var nonce = btn.getAttribute("data-nonce");
                    
                    // 显示加载状态
                    btn.disabled = true;
                    btn.innerHTML = "' . __('Removing...', 'unique-client-page') . '";
                    
                    // 创建XHR请求
                    var xhr = new XMLHttpRequest();
                    var formData = new FormData();
                    
                    // 添加action参数，确保AJAX请求被正确路由
                    formData.append("action", "ucp_update_wishlist");
                    formData.append("product_id", productId);
                    formData.append("page_id", pageId);
                    formData.append("wishlist_key", wishlistKey);
                    formData.append("wishlist_action", "remove");
                    formData.append("nonce", nonce);
                    
                    // 记录到控制台以便调试
                    console.log("Sending wishlist remove request:", {
                        productId: productId,
                        pageId: pageId,
                        wishlistKey: wishlistKey,
                        action: "remove"
                    });
                    
                    xhr.open("POST", "' . admin_url('admin-ajax.php') . '");
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                console.log("Server response:", response);
                                
                                if (response.success) {
                                    // 移除整行
                                    var row = btn.closest("tr.ucp-wishlist-item");
                                    row.style.transition = "opacity 0.3s ease";
                                    row.style.opacity = "0";
                                    
                                    // 等待转场动画完成后移除
                                    setTimeout(function() {
                                        row.parentNode.removeChild(row);
                                        
                                        // 重新计算序号
                                        var rows = document.querySelectorAll("tr.ucp-wishlist-item");
                                        for (var i = 0; i < rows.length; i++) {
                                            rows[i].querySelector(".ucp-wishlist-no").textContent = (i + 1);
                                        }
                                        
                                        // 检查是否还有商品
                                        if (rows.length === 0) {
                                            var table = document.querySelector(".ucp-wishlist-table");
                                            var container = document.querySelector(".ucp-wishlist-container");
                                            table.style.display = "none";
                                            container.innerHTML += "<p class=\"ucp-empty-wishlist\">" + "' . __('No items in your wishlist', 'unique-client-page') . '" + "</p>";
                                        }
                                    }, 300);
                                } else {
                                    // 恢复按钮状态
                                    btn.disabled = false;
                                    btn.innerHTML = "' . __('Remove', 'unique-client-page') . '";
                                    alert(response.data ? response.data.message : "' . __('Operation failed', 'unique-client-page') . '");
                                }
                            } catch (e) {
                                console.error("Error parsing response:", e);
                                btn.disabled = false;
                                btn.innerHTML = "' . __('Remove', 'unique-client-page') . '";
                                alert("' . __('Invalid response from server', 'unique-client-page') . '");
                            }
                        } else {
                            // 恢复按钮状态
                            btn.disabled = false;
                            btn.innerHTML = "' . __('Remove', 'unique-client-page') . '";
                            console.error("HTTP Error:", xhr.status, xhr.statusText);
                            alert("' . __('Request error, please try again', 'unique-client-page') . '");
                        }
                    };
                    xhr.onerror = function() {
                        // 恢复按钮状态
                        btn.disabled = false;
                        btn.innerHTML = "' . __('Remove', 'unique-client-page') . '";
                        console.error("Network Error");
                        alert("' . __('Network error, please try again', 'unique-client-page') . '");
                    };
                    xhr.send(formData);
                });
            }
        });
        </script>';
        
        // 添加样式
        echo '<style>
        .ucp-wishlist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .ucp-wishlist-table th, .ucp-wishlist-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }
        .ucp-wishlist-table th {
            background-color: #f5f5f5;
            font-weight: 600;
        }
        .ucp-wishlist-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .ucp-wishlist-product-image {
            max-width: 80px;
            height: auto;
        }
        .ucp-wishlist-no, .ucp-wishlist-sku {
            width: 80px;
        }
        .ucp-wishlist-image {
            width: 120px;
        }
        .ucp-wishlist-action {
            width: 100px;
            text-align: center;
        }
        .ucp-empty-wishlist {
            padding: 20px;
            text-align: center;
            font-style: italic;
            color: #666;
        }
        .ucp-remove-from-wishlist {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .ucp-remove-from-wishlist:hover {
            background-color: #d32f2f;
        }
        </style>';
        
        return ob_get_clean();
    }
}
