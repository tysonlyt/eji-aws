<?php
/**
 * UI Renderer Component
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
class UCP_UI_Renderer {
    /**
     * Class instance
     *
     * @var UCP_UI_Renderer
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
     * @return UCP_UI_Renderer instance
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
     * Render products grid
     *
     * @param WP_Query $products Products to display
     * @param array $args Display arguments
     * @return string HTML output
     */
    public function render_products_grid($products, $args = array()) {
        if ($this->debug_manager) {
            $this->debug_manager->start_timer('render_products_grid');
        }
        
        $default_args = array(
            'columns' => 3,
            'show_add_to_wishlist' => true,
            'wishlist_key' => '',
            'page_id' => 0
        );
        
        $args = wp_parse_args($args, $default_args);
        
        ob_start();
        
        if ($products && $products->have_posts()) {
            echo '<div class="ucp-products-grid columns-' . esc_attr($args['columns']) . '">';
            
            while ($products->have_posts()) {
                $products->the_post();
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                
                if (!$product) {
                    continue;
                }
                
                $image_id = $product->get_image_id();
                $image_url = wp_get_attachment_image_url($image_id, 'medium');
                if (!$image_url) {
                    $image_url = wc_placeholder_img_src('medium');
                }
                
                echo '<div class="ucp-product-item">';
                
                // Image and title
                echo '<div class="ucp-product-image">';
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr(get_the_title()) . '">';
                echo '</div>';
                
                echo '<h3 class="ucp-product-title">' . get_the_title() . '</h3>';
                
                // Price
                echo '<div class="ucp-product-price">';
                echo $product->get_price_html();
                echo '</div>';
                
                // Show add to wishlist button if enabled
                if ($args['show_add_to_wishlist'] && !empty($args['wishlist_key']) && !empty($args['page_id'])) {
                    // Get wishlist manager instance
                    $wishlist_manager = UCP_Wishlist_Manager::get_instance();
                    
                    $is_in_wishlist = $wishlist_manager ? $wishlist_manager->is_product_in_wishlist($product_id, $args['wishlist_key']) : false;
                    $action = $is_in_wishlist ? 'remove' : 'add';
                    $icon_class = $is_in_wishlist ? 'dashicons-heart' : 'dashicons-heart-empty';
                    $button_text = $is_in_wishlist ? __('Remove from favorites', 'unique-client-page') : __('Add to favorites', 'unique-client-page');
                    
                    echo '<div class="ucp-wishlist-button-wrapper">';
                    echo '<button class="ucp-wishlist-button" 
                        data-product-id="' . esc_attr($product_id) . '" 
                        data-page-id="' . esc_attr($args['page_id']) . '" 
                        data-wishlist-key="' . esc_attr($args['wishlist_key']) . '" 
                        data-action="' . esc_attr($action) . '"
                        data-nonce="' . wp_create_nonce('ucp_wishlist_action') . '">
                        <i class="dashicons ' . esc_attr($icon_class) . '"></i>
                        <span>' . esc_html($button_text) . '</span>
                    </button>';
                    echo '</div>';
                    
                    // Attach AJAX script
                    $this->add_wishlist_script();
                }
                
                echo '</div>'; // .ucp-product-item
            }
            
            echo '</div>'; // .ucp-products-grid
        } else {
            echo '<p class="ucp-no-products">' . __('No Products', 'unique-client-page') . '</p>';
        }
        
        if ($this->debug_manager) {
            $this->debug_manager->stop_timer('render_products_grid');
            $this->debug_manager->log(
                'Rendered products grid with ' . $products->post_count . ' products in ' . 
                $this->debug_manager->get_timer('render_products_grid') . ' seconds',
                'info', 
                'product_display'
            );
        }
        
        wp_reset_postdata();
        
        return ob_get_clean();
    }
    
    /**
     * Add wishlist AJAX script to the page
     */
    private function add_wishlist_script() {
        static $script_added = false;
        
        if ($script_added) {
            return;
        }
        
        $script_added = true;
        
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var wishlistButtons = document.querySelectorAll(".ucp-wishlist-button");
            
            for (var i = 0; i < wishlistButtons.length; i++) {
                wishlistButtons[i].addEventListener("click", function(e) {
                    e.preventDefault();
                    
                    var btn = this;
                    var productId = btn.getAttribute("data-product-id");
                    var pageId = btn.getAttribute("data-page-id");
                    var wishlistKey = btn.getAttribute("data-wishlist-key");
                    var nonce = btn.getAttribute("data-nonce");
                    var action = btn.getAttribute("data-action");
                    
                    // Send AJAX request
                    var xhr = new XMLHttpRequest();
                    var formData = new FormData();
                    formData.append("product_id", productId);
                    formData.append("page_id", pageId);
                    formData.append("wishlist_key", wishlistKey);
                    formData.append("wishlist_action", action);
                    formData.append("nonce", nonce);
                    
                    xhr.open("POST", "' . admin_url('admin-ajax.php') . '");
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Update button based on action
                                if (response.data.action === "add") {
                                    btn.setAttribute("data-action", "remove");
                                    btn.querySelector("i").classList.remove("dashicons-heart-empty");
                                    btn.querySelector("i").classList.add("dashicons-heart");
                                    btn.querySelector("span").textContent = "Remove from favorites";
                                    alert("Product added to favorites");
                                } else {
                                    btn.setAttribute("data-action", "add");
                                    btn.querySelector("i").classList.remove("dashicons-heart");
                                    btn.querySelector("i").classList.add("dashicons-heart-empty");
                                    btn.querySelector("span").textContent = "Add to favorites";
                                    alert("Product removed from favorites");
                                }
                            } else {
                                alert(response.data.message || "Operation failed");
                            }
                        } else {
                            alert("Request error, please try again");
                        }
                    };
                    xhr.send(formData);
                });
            }
        });
        </script>';
    }
    
    /**
     * Render a notice box
     *
     * @param string $message Message to display
     * @param string $type Notice type (success, error, warning, info)
     * @param boolean $dismissible Whether the notice is dismissible
     * @return string HTML output
     */
    public function render_notice($message, $type = 'info', $dismissible = true) {
        $class = 'ucp-notice notice notice-' . $type;
        if ($dismissible) {
            $class .= ' is-dismissible';
        }
        
        $output = '<div class="' . esc_attr($class) . '">';
        $output .= '<p>' . wp_kses_post($message) . '</p>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Render wishlist items
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
        
        // 添加CSS样式
        ?>
        <style>
            .wishlist-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .wishlist-table th, .wishlist-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            .wishlist-table th { background-color: #f8f8f8; }
            .wishlist-table tr:nth-child(even) { background-color: #f9f9f9; }
            .wishlist-table tr:hover { background-color: #f1f1f1; }
            .wishlist-product-image { width: 60px; height: 60px; object-fit: contain; }
            .ucp-remove-item { background-color: #f44336; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; }
            .ucp-remove-item:hover { background-color: #d32f2f; }
            .ucp-remove-item:disabled { background-color: #cccccc; cursor: not-allowed; }
        </style>
        <div class="ucp-wishlist-container">
            <table class="wishlist-table" id="wishlist-table-<?php echo esc_attr($page_id); ?>">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>SKU#</th>
                        <th>PRODUCT IMAGE</th>
                        <th>PRODUCT NAME</th>
                        <th>PRICE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $item_index = 1;
                foreach ($items as $item) :
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
                    
                    // 获取产品图片
                    $image = $product->get_image('thumbnail', array('class' => 'wishlist-product-image'));
                    if (empty($image)) {
                        $image = wc_placeholder_img('thumbnail', array('class' => 'wishlist-product-image'));
                    }
                    ?>
                    <tr data-product-id="<?php echo esc_attr($product_id); ?>">
                        <td><?php echo esc_html($item_index); ?></td>
                        <td><?php echo esc_html($sku); ?></td>
                        <td><?php echo $image; ?></td>
                        <td><?php echo esc_html($product->get_name()); ?></td>
                        <td><?php echo $product->get_price_html(); ?></td>
                        <td>
                            <button class="ucp-remove-item" 
                                data-product-id="<?php echo esc_attr($product_id); ?>" 
                                data-wishlist-key="<?php echo esc_attr($wishlist_key); ?>" 
                                data-page-id="<?php echo esc_attr($page_id); ?>">
                                <?php _e('Remove', 'unique-client-page'); ?>
                            </button>
                        </td>
                    </tr>
                    <?php
                    $item_index++;
                endforeach;
                ?>
                </tbody>
            </table>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // 处理删除按钮点击
            $('.ucp-remove-item').on('click', function(e) {
                // 阻止事件冒泡和默认行为
                e.preventDefault();
                e.stopPropagation();
                
                var $this = $(this);
                var productId = $this.data('product-id');
                var wishlistKey = $this.data('wishlist-key');
                var pageId = $this.data('page-id');
                var $row = $this.closest('tr');
                
                // 禁用按钮并显示加载状态
                $this.prop('disabled', true).text('Removing...');
                
                $.ajax({
                    url: '<?php echo admin_url("admin-ajax.php"); ?>',
                    type: 'POST',
                    data: {
                        action: 'ucp_update_wishlist',
                        product_id: productId,
                        page_id: pageId,
                        wishlist_key: wishlistKey,
                        wishlist_action: 'remove',
                        nonce: '<?php echo wp_create_nonce("ucp-ajax-nonce"); ?>'
                    },
                    success: function(response) {
                        console.log('Remove response:', response);
                        if (response.success) {
                            // 淡出并移除行
                            $row.fadeOut('slow', function() {
                                $(this).remove();
                                
                                // 如果没有项目，显示空消息
                                if ($('.wishlist-table tbody tr').length === 0) {
                                    $('.wishlist-table').replaceWith('<p class="ucp-empty-wishlist"><?php echo __('No items in your wishlist', 'unique-client-page'); ?></p>');
                                } else {
                                    // 重新编号
                                    $('.wishlist-table tbody tr').each(function(index) {
                                        $(this).find('td:first').text(index + 1);
                                    });
                                }
                            });
                        } else {
                            // 恢复按钮状态
                            $this.prop('disabled', false).text('<?php _e("Remove", "unique-client-page"); ?>');
                            alert('Failed to remove: ' + (response.data ? response.data.message : 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error);
                        // 恢复按钮状态
                        $this.prop('disabled', false).text('<?php _e("Remove", "unique-client-page"); ?>');
                        alert('Failed to communicate with server. Please try again.');
                    }
                });
                
                return false;
            });
        });
        </script>
        <?php
        
        return ob_get_clean();
    }
}
