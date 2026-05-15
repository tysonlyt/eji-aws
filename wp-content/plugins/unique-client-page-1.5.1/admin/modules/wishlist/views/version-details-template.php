<?php 
/**
 * Template for displaying wishlist version details
 * 
 * This template is loaded via AJAX when viewing version details
 * 
 * @package UCP
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;

// Get version data from passed arguments
$version_id = isset($_GET['version_id']) ? intval($_GET['version_id']) : 0;
$page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;

if (!$version_id) {
    echo '<div class="notice notice-error"><p>无效的版本ID</p></div>';
    exit;
}

// Check nonce for security
if (!isset($_GET['security']) || !wp_verify_nonce($_GET['security'], 'ucp_wishlist_nonce')) {
    echo '<div class="notice notice-error"><p>安全验证失败</p></div>';
    exit;
}

// Get version data from database - 增加调试信息
global $wpdb;

// 检查表结构
$table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}ucp_wishlist_versions'") === $wpdb->prefix.'ucp_wishlist_versions';
if (!$table_exists) {
    echo '<div class="notice notice-error"><p>愿望清单版本表不存在，系统将尝试创建</p></div>';
    
    // 动态创建表结构
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$wpdb->prefix}ucp_wishlist_versions (
        version_id bigint(20) NOT NULL AUTO_INCREMENT,
        page_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        version_number int(11) NOT NULL,
        version_name varchar(255),
        wishlist_data longtext,
        created_by bigint(20),
        created_at datetime NOT NULL,
        is_current tinyint(1) DEFAULT 0,
        notes text,
        PRIMARY KEY (version_id),
        KEY page_id (page_id),
        KEY user_id (user_id)
    ) $charset_collate;";
    
    dbDelta($sql);
    
    // 检查表是否创建成功
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}ucp_wishlist_versions'") === $wpdb->prefix.'ucp_wishlist_versions';
    if (!$table_exists) {
        echo '<div class="notice notice-error"><p>无法创建愿望清单版本表，请联系管理员</p></div>';
        exit;
    }
}

// 获取版本数据
$version = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}ucp_wishlist_versions WHERE version_id = %d",
    $version_id
));

if (!$version) {
    echo '<div class="notice notice-error"><p>未找到版本数据</p></div>';
    exit;
}

// Parse wishlist data - 增强兼容性处理
$products = array();
if (!empty($version->wishlist_data)) {
    if (is_string($version->wishlist_data)) {
        $wishlist_data = maybe_unserialize($version->wishlist_data);
        // 检查是否已经反序列化成功
        if (is_array($wishlist_data)) {
            $products = $wishlist_data;
        } else {
            // 尝试JSON解码
            $json_decoded = json_decode($version->wishlist_data, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $products = $json_decoded;
            } else {
                // 尝试按逗号分割
                $products = explode(',', $version->wishlist_data);
            }
        }
    } else if (is_array($version->wishlist_data)) {
        $products = $version->wishlist_data;
    } else {
        // 尝试其他方法解析 - 比如把可能是对象的数据转为数组
        $products = (array)$version->wishlist_data;
    }
}

// Format date
$created_date = !empty($version->created_at) ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($version->created_at)) : '-';
?>

<div class="version-details">
    <h3>版本信息</h3>
    
    <table class="form-table">
        <tr>
            <th>版本ID:</th>
            <td><?php echo esc_html($version->version_id); ?></td>
        </tr>
        <tr>
            <th>版本号:</th>
            <td><?php echo esc_html($version->version_number); ?></td>
        </tr>
        <?php if (!empty($version->version_name)) : ?>
        <tr>
            <th>版本名称:</th>
            <td><?php echo esc_html($version->version_name); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th>创建时间:</th>
            <td><?php echo esc_html($created_date); ?></td>
        </tr>
        <tr>
            <th>页面ID:</th>
            <td><?php echo esc_html($version->page_id); ?></td>
        </tr>
        <?php if (!empty($version->created_by)) : ?>
        <tr>
            <th>创建者:</th>
            <td>
                <?php 
                $user = get_userdata($version->created_by);
                echo $user ? esc_html($user->display_name) : esc_html($version->created_by); 
                ?>
            </td>
        </tr>
        <?php endif; ?>
        <?php if (!empty($version->notes)) : ?>
        <tr>
            <th>备注:</th>
            <td><?php echo esc_html($version->notes); ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div class="product-list">
        <h4>产品列表</h4>
        <?php if (!empty($products)) : ?>
            <ul class="product-items">
                <?php foreach ($products as $product) : ?>
                    <li>
                        <?php 
                        // 增强产品数据处理 - 兼容多种格式
                        $product_name = '';
                        $product_id = 0;
                        
                        // 首先确定我们有什么类型的数据
                        if (is_object($product)) {
                            // 对象情况 - 可能是产品对象或标准对象
                            if (!empty($product->title)) {
                                $product_name = $product->title;
                            } elseif (!empty($product->name)) {
                                $product_name = $product->name;
                            } elseif (!empty($product->product_name)) {
                                $product_name = $product->product_name;
                            }
                            
                            // 尝试获取ID
                            if (!empty($product->id)) {
                                $product_id = $product->id; 
                            } elseif (!empty($product->product_id)) {
                                $product_id = $product->product_id;
                            }
                        } elseif (is_array($product)) {
                            // 数组情况
                            $product = (object) $product;
                            if (!empty($product->title)) {
                                $product_name = $product->title;
                            } elseif (!empty($product->name)) {
                                $product_name = $product->name;
                            } elseif (!empty($product->product_name)) {
                                $product_name = $product->product_name;
                            }
                            
                            // 尝试获取ID
                            if (!empty($product->id)) {
                                $product_id = $product->id;
                            } elseif (!empty($product->product_id)) {
                                $product_id = $product->product_id;
                            }
                        } elseif (is_numeric($product)) {
                            // 纯数字情况 - 可能直接是产品ID
                            $product_id = $product;
                            $product_name = 'Product #' . $product_id;
                        } else {
                            // 其他情况 - 字符串等
                            $product_name = (string) $product;
                        }
                        
                        // 如果我们有ID，尝试从WooCommerce获取产品信息
                        if ($product_id > 0 && function_exists('wc_get_product')) {
                            $wc_product = wc_get_product($product_id);
                            if ($wc_product) {
                                $product_name = $wc_product->get_name();
                            }
                        }
                        
                        // 如果仍然没有名称，设置默认值
                        if (empty($product_name)) {
                            $product_name = '未命名产品 #' . $product_id;
                        } else {
                            $product_name = '产品数据格式错误';
                        }
                        echo esc_html($product_name); 
                        ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>此版本没有产品数据</p>
        <?php endif; ?>
    </div>
</div>
