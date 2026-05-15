<?php
/**
 * 产品展示组件
 * 
 * 处理产品的前端展示逻辑
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 产品展示组件类
 */
class UCP_Product_Display {
    /**
     * 组件实例
     *
     * @var UCP_Product_Display
     */
    private static $instance = null;
    
    /**
     * 获取单例实例
     *
     * @return UCP_Product_Display 组件实例
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 构造函数
     */
    private function __construct() {
        // 初始化代码
    }
    
    /**
     * 注册钩子
     */
    public function register_hooks() {
        // 添加shortcode
        add_shortcode('unique_client_products', array($this, 'render_product_shortcode'));
    }
    
    /**
     * 渲染产品展示shortcode
     *
     * @param array $atts Shortcode属性
     * @return string 渲染的HTML
     */
    public function render_product_shortcode($atts) {
        // 解析shortcode参数
        $atts = shortcode_atts(array(
            'ids' => '',               // 产品ID，逗号分隔
            'columns' => 4,            // 列数
            'orderby' => 'title',      // 排序字段
            'order' => 'ASC'           // 排序方式 
        ), $atts, 'unique_client_products');
        
        // 初始化查询参数 - 显示所有产品
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,    // 显示所有产品
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );
        
        // 如果指定了产品ID
        if (isset($atts['ids']) && !empty($atts['ids'])) {
            $ids = array_map('trim', explode(',', $atts['ids']));
            $args['post__in'] = $ids;
        }
        
        // 启用输出缓冲
        ob_start();
        
        // 查询产品
        $products_query = $this->get_products($args);
        
        // 检查是否有产品
        if ($products_query->have_posts()) {
            // 渲染产品网格
            $this->render_product_grid($products_query, $atts);
            
            // 渲染模态框
            $this->render_product_modal();
        } else {
            echo '<p class="ucp-no-products">' . __('No Products', 'unique-client-page') . '</p>';
        }
        
        // 重置查询数据
        wp_reset_postdata();
        
        // 返回缓冲内容
        return ob_get_clean();
    }
    
    /**
     * 获取产品
     *
     * @param array $args 产品查询参数
     * @return WP_Query 产品查询结果
     */
    public function get_products($args = array()) {
        $default_args = array(
            'post_type' => 'product',
            'posts_per_page' => 12,
            'paged' => 1
        );
        
        $args = wp_parse_args($args, $default_args);
        
        return new WP_Query($args);
    }
    
    /**
     * 获取产品分类
     *
     * @return array 产品分类列表
     */
    public function get_product_categories() {
        $args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true
        );
        
        return get_terms($args);
    }
    
    /**
     * 渲染产品网格
     * 
     * @param WP_Query $products_query 产品查询结果
     * @param array $atts Shortcode属性
     */
    public function render_product_grid($products_query, $atts) {
        // 计算列数
        $columns = absint($atts['columns']);
        if ($columns < 1) $columns = 4; // 默认4列
        
        // 输出产品网格容器
        echo '<div class="ucp-products-grid columns-' . $columns . '">';
        
        // 循环输出每个产品
        while ($products_query->have_posts()) {
            $products_query->the_post();
            global $product;
            
            if (!$product || !$product->is_visible()) {
                continue;
            }
            
            // 渲染单个产品
            $this->render_product_item($product);
        }
        
        echo '</div>'; // .ucp-products-grid
    }
    
    /**
     * 渲染单个产品项
     * 
     * @param WC_Product $product 产品对象
     */
    public function render_product_item($product) {
        // 获取产品ID
        $product_id = $product->get_id();
        
        // 输出产品容器
        echo '<div class="ucp-product-item" data-product-id="' . $product_id . '">';
        
        // 产品图片
        echo '<div class="ucp-product-image">';
        echo '<a href="#" class="product-image-link" data-product-id="' . $product_id . '">';
        echo $product->get_image('woocommerce_thumbnail');
        echo '</a>';
        echo '</div>';
        
        // 产品信息
        echo '<div class="ucp-product-info">';
        
        // 产品标题
        echo '<h3 class="ucp-product-title">';
        echo '<a href="#" class="product-title-link" data-product-id="' . $product_id . '">' . $product->get_name() . '</a>';
        echo '</h3>';
        
        // 产品SKU
        if ($product->get_sku()) {
            echo '<div class="ucp-product-sku">' . __('SKU', 'unique-client-page') . ': ' . $product->get_sku() . '</div>';
        }
        
        // 产品价格
        echo '<div class="ucp-product-price">';
        echo $product->get_price_html();
        echo '</div>';
        
        // 愿望清单按钮
        $this->render_wishlist_buttons($product_id);
        
        echo '</div>'; // .ucp-product-info
        
        echo '</div>'; // .ucp-product-item
    }
    
    /**
     * Render wishlist buttons for a product
     *
     * @param int $product_id Product ID
     */
    public function render_wishlist_buttons($product_id) {
        // Start wishlist buttons container
        echo '<div class="ucp-wishlist-buttons">';
        
        // Get page ID from query params or current post
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : get_the_ID();
        $nonce = wp_create_nonce('ucp-wishlist-nonce');
        
        // Check if product is already in wishlist
        $in_wishlist = $this->is_product_in_wishlist($product_id);
        $button_text = $in_wishlist 
            ? __('Remove from Wishlist', 'unique-client-page') 
            : __('Add to Wishlist', 'unique-client-page');
        $button_class = $in_wishlist ? 'remove-from-wishlist' : 'add-to-wishlist';
        
        // Render the wishlist button
        echo '<button type="button" class="ucp-wishlist-button ' . $button_class . '" '
            . 'data-product-id="' . $product_id . '" '
            . 'data-page-id="' . $page_id . '" '
            . 'data-nonce="' . $nonce . '">';
        echo '<span class="wishlist-text">' . $button_text . '</span>';
        echo '</button>';
        
        echo '</div>'; // .ucp-wishlist-buttons
    }
    
    /**
     * Render product modal dialog
     */
    public function render_product_modal() {
        // 產品詳情模態框容器 - 使用專屬類名
        echo '<div id="ucp-product-detail-modal" class="ucp-product-detail-modal" style="display: none;">';
        echo '<div class="ucp-product-detail-modal-container">';
        
        // Modal header
        echo '<div class="ucp-product-detail-modal-header">';
        echo '<h2 class="ucp-product-detail-modal-title"></h2>';
        echo '<button type="button" class="ucp-product-detail-modal-close">&times;</button>';
        echo '</div>';
        
        // Modal body
        echo '<div class="ucp-product-detail-modal-body">';
        echo '<div class="ucp-product-detail-modal-content">';
        
        // Left column (image)
        echo '<div class="ucp-product-detail-modal-left">';
        echo '<div class="ucp-product-detail-modal-image-container"></div>';
        echo '</div>';
        
        // Right column (product details)
        echo '<div class="ucp-product-detail-modal-right">';
        echo '<div class="ucp-product-detail-modal-details">';
        
        // Product title
        echo '<h1 class="ucp-product-detail-modal-product-title"></h1>';
        
        // Product SKU
        echo '<div class="ucp-product-detail-modal-sku"></div>';
        
        // Product price
        echo '<div class="ucp-product-detail-modal-price"></div>';
        
        // Product description
        echo '<div class="ucp-product-detail-modal-description"></div>';
        
        // Wishlist button container
        echo '<div class="ucp-product-detail-modal-wishlist-container">';
        echo '</div>';
        
        echo '</div>'; // .ucp-product-detail-modal-details
        echo '</div>'; // .ucp-product-detail-modal-right
        
        echo '</div>'; // .ucp-product-detail-modal-content
        echo '</div>'; // .ucp-product-detail-modal-body
        
        echo '</div>'; // .ucp-product-detail-modal-container
        echo '</div>'; // .ucp-product-detail-modal
        
        // Add JavaScript to handle modal interaction
        $this->enqueue_product_scripts();
    }
    
    /**
     * Enqueue necessary JavaScript for product display
     */
    public function enqueue_product_scripts() {
        // Get AJAX URL and nonce
        $ajax_url = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('ucp-ajax-nonce');

        // 加载愿望清单队列系统脚本
        $queue_js_path = plugin_dir_url(dirname(__FILE__)) . 'assets/js/wishlist-queue.js';
        wp_enqueue_script('ucp-wishlist-queue', $queue_js_path, array('jquery'), '1.0.0', true);
        
        // 获取当前页面ID
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : get_the_ID();
        
        // 向队列脚本传递本地化变量
        wp_localize_script('ucp-wishlist-queue', 'UCP_WishlistData', array(
            'ajax_url' => $ajax_url,
            'nonce' => $nonce,
            'page_id' => $page_id,
            'add_text' => __('Add to Wishlist', 'unique-client-page'),
            'remove_text' => __('Remove from Wishlist', 'unique-client-page'),
            'processing_text' => __('Processing...', 'unique-client-page'),
            'queued_text' => __('Queued...', 'unique-client-page')
        ));
        
        echo '<script>
        jQuery(document).ready(function($) {
            // 愿望清单队列管理系统
            var wishlistQueue = [];
            var processingWishlist = false;
            var batchSize = 10; // 每批处理的最大产品数量
            var processingDelay = 50; // 队列处理延迟(毫秒)
        
        // Product modal functionality
        function openProductModal(productId) {
            var modal = $("#ucp-product-detail-modal");
            
            // Show loading state
            modal.find(".ucp-product-detail-modal-body").html(\'<div class="loading">Loading...</div>\');
            modal.addClass("show").show();
            
            // AJAX request to get product details
            $.ajax({
                url: "' . admin_url('admin-ajax.php') . '",
                type: "POST",
                data: {
                    action: "get_product_details",
                    product_id: productId,
                    nonce: "' . wp_create_nonce('product_details_nonce') . '"
                },
                success: function(response) {
                    if (response.success) {
                        updateProductModal(response.data);
                    } else {
                        modal.find(".ucp-product-detail-modal-body").html(\'<div class="error">Error loading product</div>\');
                    }
                },
                error: function() {
                    modal.find(".ucp-product-detail-modal-body").html(\'<div class="error">Error loading product</div>\');
                }
            });
        }
        
        // Update modal with product data
        function updateProductModal(product) {
            var modal = $("#ucp-product-detail-modal");
            
            // Set title
            modal.find(".ucp-product-detail-modal-title").text(product.name);
            modal.find(".ucp-product-detail-modal-product-title").text(product.name);
            
            // Set image
            modal.find(".ucp-product-detail-modal-image-container").html(product.image);
            
            // Set SKU
            if (product.sku) {
                modal.find(".ucp-product-detail-modal-sku").html("<strong>SKU:</strong> " + product.sku);
            } else {
                modal.find(".ucp-product-detail-modal-sku").html("");
            }
            
            // Set price
            modal.find(".ucp-product-detail-modal-price").html(product.price_html);
            
            // Set description
            modal.find(".ucp-product-detail-modal-description").html(product.description);
            
            // Set wishlist button
            modal.find(".ucp-product-detail-modal-wishlist-container").html(product.wishlist_button);
            
            // Reinitialize wishlist button events
            initWishlistButtons();
        }
        
        // Close modal when clicking close button
        $(document).on("click", ".ucp-product-detail-modal-close", function() {
            $("#ucp-product-detail-modal").removeClass("show").hide();
        });
        
        // Close modal when clicking outside the modal container
        $(document).on("click", "#ucp-product-detail-modal", function(e) {
            if (e.target === this) {
                $("#ucp-product-detail-modal").removeClass("show").hide();
            }
        });
        
        // Open modal when clicking product image or title
        $(document).on("click", ".product-image-link, .product-title-link", function(e) {
            e.preventDefault();
            var productId = $(this).data("product-id");
            openProductModal(productId);
        });
        
        // 初始化愿望清单队列系统
        if (typeof UCP_WishlistQueue !== "undefined") {
            UCP_WishlistQueue.init(UCP_WishlistData.ajax_url, UCP_WishlistData.nonce, UCP_WishlistData.page_id);
            console.log("UCP Wishlist Queue System initialized with version <?php echo UCP_VERSION; ?>");
        }
        
        // Initialize wishlist buttons
        function initWishlistButtons() {
            
            // 绑定愿望清单按钮点击事件
            $(".ucp-wishlist-button").off("click").on("click", function() {
                var button = $(this);
                var productId = button.data("product-id");
                var isRemove = button.hasClass("remove-from-wishlist");
                
                console.log("Wishlist button clicked:", productId, isRemove ? "remove" : "add");
                
                // 使用队列系统处理
                if (typeof UCP_WishlistQueue !== "undefined") {
                    try {
                        // 通过队列系统处理请求
                        if (isRemove) {
                            UCP_WishlistQueue.removeFromQueue(productId, button);
                        } else {
                            UCP_WishlistQueue.addToQueue(productId, button);
                        }
                        return false; // 阻止默认事件
                    } catch (e) {
                        console.error("Error in wishlist queue:", e);
                    }
                }
                
                // 旧方式作为备用 - 添加加载状态
                button.prop("disabled", true);
                button.find(".wishlist-text").text(UCP_WishlistData.processing_text);
                
                // 发送AJAX请求（兼容模式）
                $.ajax({
                    url: "<?php echo $ajax_url; ?>",
                    type: "POST",
                    data: {
                        action: "ucp_update_wishlist",
                        wishlist_action: isRemove ? "remove" : "add", 
                        product_ids: [productId],
                        page_id: UCP_WishlistData.page_id,
                        nonce: UCP_WishlistData.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            if (isRemove) {
                                button.removeClass("remove-from-wishlist").addClass("add-to-wishlist");
                                button.find(".wishlist-text").text(UCP_WishlistData.add_text);
                    $.ajax({
                        url: "' . $ajax_url . '",
                        type: "POST",
                        data: {
                            action: "ucp_wishlist_handler",
                            wishlist_action: isRemove ? "remove" : "add", 
                            product_ids: [productId], // 数组格式与新接口兼容
                            page_id: UCP_WishlistData.page_id,
                            nonce: UCP_WishlistData.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                // 更新按钮状态
                                if (isRemove) {
                                    button.removeClass("remove-from-wishlist").addClass("add-to-wishlist");
                                    button.find(".wishlist-text").text(UCP_WishlistData.add_text);
                                } else {
                                    button.removeClass("add-to-wishlist").addClass("remove-from-wishlist");
                                    button.find(".wishlist-text").text(UCP_WishlistData.remove_text);
                                }
                            } else {
                                alert(response.data.message || "Error processing wishlist");
                            }
                        },
                        error: function() {
                            alert("Server error");
                        },
                        complete: function() {
                            button.prop("disabled", false);
                        }
                    });
                });
            }
            
            // Initialize all wishlist buttons on page load
            initWishlistButtons();
        });
        </script>';
    }
    
    /**
     * Check if product is in wishlist
     *
     * @param int $product_id Product ID
     * @return bool Whether product is in wishlist
     */
    public function is_product_in_wishlist($product_id) {
        // Get current page ID
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : get_the_ID();
        
        // Get current user ID
        $user_id = get_current_user_id();
        if (!$user_id) {
            return false;
        }
        
        // Get user wishlist data for this page
        $wishlist_data = get_user_meta($user_id, 'ucp_wishlist_' . $page_id, true);
        if (empty($wishlist_data)) {
            return false;
        }
        
        // Check if product exists in wishlist
        $wishlist_data = maybe_unserialize($wishlist_data);
        if (!is_array($wishlist_data)) {
            return false;
        }
        
        foreach ($wishlist_data as $item) {
            if (isset($item['product_id']) && $item['product_id'] == $product_id) {
                return true;
            }
        }
        
        return false;
    }
}
