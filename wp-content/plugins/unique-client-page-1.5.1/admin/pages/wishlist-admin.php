<?php
/**
 * Wishlist Admin Functions
 * 
 * Adds an admin menu page for managing user wishlists
 *
 * @package Unique_Client_Page
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add Wishlist Management menu to admin
 * (Disabled to remove global wishlist from sidebar)
 */
function ucp_add_wishlist_admin_menu() {
    // 注册愿望清单管理页面回调函数
    add_submenu_page(
        null,                                      // 不在菜单中显示
        'Wishlist Management',                     // 页面标题
        'Wishlist Management',                     // 菜单标题
        'manage_options',                         // 权限
        'ucp-wishlist-manage',                   // 菜单别名
        'ucp_render_wishlist_management_page'    // 回调函数
    );
    
    return;
}

/**
 * Render the Wishlist Management admin page
 */
function ucp_render_wishlist_management_page() {
    // 添加错误捕获和调试信息记录
    try {
        // 日志记录当前页面情况
        error_log('UCP Debug - Wishlist管理页面加载开始');
    
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // 检查是否存在page_id，如果存在则使用新版产品页面处理逻辑
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;
        if ($page_id > 0) {
            // 使用新版页面特定的处理逻辑
            ucp_render_page_specific_wishlist($page_id);
            return;
        }

        // Handle actions
        if (isset($_GET['action']) && isset($_GET['user_id']) && isset($_GET['product_id'])) {
            if ($_GET['action'] === 'remove' && wp_verify_nonce($_GET['_wpnonce'], 'ucp_remove_wishlist_item')) {
                // Remove item from wishlist
                ucp_admin_remove_wishlist_item($_GET['user_id'], $_GET['product_id']);
                echo '<div class="notice notice-success is-dismissible"><p>Item removed from wishlist successfully.</p></div>';
            }
        }

        // Get search parameters
        $search_user = isset($_GET['search_user']) ? sanitize_text_field($_GET['search_user']) : '';
        $search_product = isset($_GET['search_product']) ? sanitize_text_field($_GET['search_product']) : '';
    
        // Start output
        ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <!-- Search Form -->
        <div class="tablenav top">
            <form method="get">
                <input type="hidden" name="page" value="ucp-wishlist-management">
                <div class="alignleft actions">
                    <input type="text" name="search_user" value="<?php echo esc_attr($search_user); ?>" placeholder="Search by username">
                    <input type="text" name="search_product" value="<?php echo esc_attr($search_product); ?>" placeholder="Search by product name">
                    <input type="submit" class="button" value="Search">
                </div>
            </form>
        </div>
        
        <!-- Wishlist Table -->
        <!-- 添加页面提示 -->
        <div class="notice notice-info">
            <p>这是全局愿望清单管理页面。如果您要查看特定产品页面的愿望清单，请从产品页面列表中选择。</p>
        </div>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Product</th>
                    <th>Added Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get all wishlist data
                $wishlist_data = ucp_get_all_wishlists($search_user, $search_product);
                
                if (empty($wishlist_data)) {
                    echo '<tr><td colspan="4">No wishlist items found.</td></tr>';
                } else {
                    foreach ($wishlist_data as $item) {
                        ?>
                        <tr>
                            <td>
                                <?php 
                                $user_info = get_userdata($item['user_id']);
                                if ($user_info) {
                                    echo '<a href="' . esc_url(admin_url('user-edit.php?user_id=' . $item['user_id'])) . '">';
                                    echo esc_html($user_info->user_login) . ' (' . esc_html($user_info->user_email) . ')';
                                    echo '</a>';
                                } else {
                                    echo 'Guest';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                $product = wc_get_product($item['product_id']);
                                if ($product) {
                                    echo '<a href="' . esc_url(admin_url('post.php?post=' . $item['product_id'] . '&action=edit')) . '">';
                                    echo esc_html($product->get_name()) . ' (#' . esc_html($item['product_id']) . ')';
                                    echo '</a>';
                                } else {
                                    echo 'Product #' . esc_html($item['product_id']) . ' (not found)';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo isset($item['date_added']) ? esc_html(date('d/m/Y', $item['date_added'])) : 'Unknown';
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=ucp-wishlist-management&action=remove&user_id=' . $item['user_id'] . '&product_id=' . $item['product_id']), 'ucp_remove_wishlist_item'); ?>" class="button button-small" onclick="return confirm('Are you sure you want to remove this item from the wishlist?');">Remove</a>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
        <?php
    } catch (Exception $e) {
        // 记录错误信息
        error_log('UCP Error - Wishlist管理页面错误: ' . $e->getMessage());
        
        // 显示用户友好的错误信息
        echo '<div class="notice notice-error"><p>加载愿望清单管理页面时发生错误。详情已记录到错误日志。</p></div>';
        echo '<div class="error-details" style="background:#f8f8f8; padding:10px; border:1px solid #ddd; margin-top:10px;">';
        echo '<strong>错误消息:</strong> ' . esc_html($e->getMessage()) . '<br>';
        echo '<strong>错误文件:</strong> ' . esc_html($e->getFile()) . '<br>';
        echo '<strong>错误行号:</strong> ' . esc_html($e->getLine()) . '<br>';
        echo '</div>';
    }
}

/**
 * Get all wishlists data for admin
 * 
 * @param string $search_user Search term for users
 * @param string $search_product Search term for products
 * @return array Wishlist data
 */
/**
 * 渲染特定页面的愿望清单管理视图
 * 
 * @param int $page_id 页面ID
 */
function ucp_render_page_specific_wishlist($page_id) {
    global $wpdb;
    
    // 获取页面信息
    $page = get_post($page_id);
    if (!$page) {
        echo '<div class="notice notice-error"><p>找不到指定的页面。</p></div>';
        return;
    }
    
    // 获取当前用户
    $user_id = get_current_user_id();
    
    // 开始输出
    echo '<div class="wrap">';
    echo '<h1>愿望清单管理: ' . esc_html($page->post_title) . '</h1>';
    echo '<p><a href="' . admin_url('admin.php?page=unique-client-page') . '" class="button">返回客户页面列表</a></p>';
    
    // 获取用户的愿望清单
    $meta_key = '_ucp_wishlist_' . $page_id;
    $users_with_wishlists = $wpdb->get_results($wpdb->prepare(
        "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = %s",
        $meta_key
    ));
    
    // 显示用户愿望清单
    echo '<h2>用户愿望清单</h2>';
    
    if (empty($users_with_wishlists)) {
        echo '<p>没有用户为此页面添加愿望清单项目。</p>';
    } else {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr>';
        echo '<th>SKU</th>';
        echo '<th>产品</th>';
        echo '<th>添加日期</th>';
        echo '</tr></thead>';
        echo '<tbody>';
        
        foreach ($users_with_wishlists as $user_data) {
            $user_wishlist = maybe_unserialize($user_data->meta_value);
            if (!is_array($user_wishlist) || empty($user_wishlist)) {
                continue;
            }
            
            foreach ($user_wishlist as $product_id) {
                $product = wc_get_product($product_id);
                if (!$product) continue;
                
                // 获取添加日期（如果有）
                $dates_meta_key = $meta_key . '_dates';
                $dates = get_user_meta($user_data->user_id, $dates_meta_key, true);
                $date_added = is_array($dates) && isset($dates[$product_id]) ? 
                    date('Y-m-d H:i:s', $dates[$product_id]) : '未知';
                
                echo '<tr>';
                echo '<td>' . esc_html($product->get_sku() ?: 'N/A') . '</td>';
                echo '<td>' . esc_html($product->get_name()) . '</td>';
                echo '<td>' . esc_html($date_added) . '</td>';
                echo '</tr>';
            }
        }
        
        echo '</tbody></table>';
    }
    
    // 显示版本历史
    echo '<h2>版本历史</h2>';
    
    $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
    
    // 检查表是否存在
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$version_table}'") === $version_table;
    if (!$table_exists) {
        // 创建表
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $version_table (
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
        echo '<div class="notice notice-info"><p>已创建版本表。</p></div>';
    }
    
    // 获取版本列表
    $versions = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $version_table WHERE page_id = %d ORDER BY created_at DESC",
        $page_id
    ));
    
    if (empty($versions)) {
        echo '<p>此页面尚无版本历史记录。</p>';
    } else {
        echo '<ul class="versions-list">';
        foreach ($versions as $version) {
            $created_date = date('d/m/Y', strtotime($version->created_at));
            $version_name = !empty($version->version_name) ? $version->version_name : '版本 #' . $version->version_number;
            
            echo '<li class="version-item">';
            echo '<a href="#" class="version-link" data-version-id="' . esc_attr($version->version_id) . '" data-page-id="' . esc_attr($page_id) . '" data-version-time="' . esc_attr($created_date) . '">';
            echo esc_html($version_name) . ' (' . esc_html($created_date) . ')';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
        
        // 添加JavaScript处理版本点击
        echo '<script>
        jQuery(document).ready(function($) {
            $(".version-link").on("click", function(e) {
                e.preventDefault();
                var versionId = $(this).data("version-id");
                var pageId = $(this).data("page-id");
                
                // 使用AJAX获取版本模板
                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {
                        action: "ucp_get_version_template",
                        version_id: versionId,
                        page_id: pageId,
                        security: "' . wp_create_nonce('ucp_wishlist_nonce') . '"
                    },
                    success: function(response) {
                        if (response.success) {
                            // 创建模态框显示内容
                            var modalContent = $("<div>").html(response.data.html);
                            $("body").append(modalContent);
                            // TODO: 使用适当的模态框显示内容
                            alert("版本详情加载成功！请查看控制台输出");
                            console.log(response.data);
                        } else {
                            alert("加载版本失败: " + (response.data ? response.data.message : "未知错误"));
                        }
                    },
                    error: function() {
                        alert("AJAX请求失败");
                    }
                });
            });
        });
        </script>';
    }
    
    echo '</div>';
}

/**
 * Get all wishlists data for admin
 * 
 * @param string $search_user Search term for users
 * @param string $search_product Search term for products
 * @return array Wishlist data
 */
function ucp_get_all_wishlists($search_user = '', $search_product = '') {
    global $wpdb;
    
    // Get all users with wishlist meta
    $users = $wpdb->get_results("
        SELECT user_id 
        FROM {$wpdb->usermeta} 
        WHERE meta_key = '_ucp_global_wishlist'
    ");
    
    $wishlist_data = array();
    
    // Loop through each user
    foreach ($users as $user) {
        $user_id = $user->user_id;
        
        // Skip if searching for user and not matching
        if (!empty($search_user)) {
            $user_info = get_userdata($user_id);
            if (!$user_info || (
                stripos($user_info->user_login, $search_user) === false && 
                stripos($user_info->user_email, $search_user) === false &&
                stripos($user_info->display_name, $search_user) === false
            )) {
                continue;
            }
        }
        
        // Get user's wishlist
        $wishlist = get_user_meta($user_id, '_ucp_global_wishlist', true);
        
        if (is_array($wishlist) && !empty($wishlist)) {
            // Get meta data for dates
            $wishlist_dates = get_user_meta($user_id, '_ucp_global_wishlist_dates', true);
            if (!is_array($wishlist_dates)) {
                $wishlist_dates = array();
            }
            
            foreach ($wishlist as $product_id) {
                // Skip if searching for product and not matching
                if (!empty($search_product)) {
                    $product = wc_get_product($product_id);
                    if (!$product || stripos($product->get_name(), $search_product) === false) {
                        continue;
                    }
                }
                
                $wishlist_data[] = array(
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'date_added' => isset($wishlist_dates[$product_id]) ? $wishlist_dates[$product_id] : time()
                );
            }
        }
    }
    
    // Sort by date (newest first)
    usort($wishlist_data, function($a, $b) {
        return $b['date_added'] - $a['date_added'];
    });
    
    return $wishlist_data;
}

/**
 * Remove item from wishlist (admin function)
 * 
 * @param int $user_id User ID
 * @param int $product_id Product ID
 * @return bool Success
 */
function ucp_admin_remove_wishlist_item($user_id, $product_id) {
    // Get user's wishlist
    $wishlist = get_user_meta($user_id, '_ucp_global_wishlist', true);
    
    if (!is_array($wishlist)) {
        return false;
    }
    
    // Remove product from wishlist
    $wishlist = array_diff($wishlist, array($product_id));
    
    // Update wishlist
    update_user_meta($user_id, '_ucp_global_wishlist', $wishlist);
    
    // Get and update dates
    $wishlist_dates = get_user_meta($user_id, '_ucp_global_wishlist_dates', true);
    if (is_array($wishlist_dates) && isset($wishlist_dates[$product_id])) {
        unset($wishlist_dates[$product_id]);
        update_user_meta($user_id, '_ucp_global_wishlist_dates', $wishlist_dates);
    }
    
    return true;
}
