<?php
/**
 * 愿望清单版本清理工具
 * 
 * 用于清理引用不存在页面的愿望清单版本记录
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/');
    require_once(ABSPATH . 'wp-config.php');
    require_once(ABSPATH . 'wp-load.php');
}

// 验证管理员权限
if (!current_user_can('manage_options')) {
    wp_die('权限不足');
}

global $wpdb;
$version_table = $wpdb->prefix . 'ucp_wishlist_versions';
$posts_table = $wpdb->posts;

/**
 * 获取所有孤儿记录（引用不存在页面的记录）
 */
function get_orphaned_versions() {
    global $wpdb, $version_table, $posts_table;
    
    $query = "SELECT v.* FROM $version_table v 
              LEFT JOIN $posts_table p ON v.page_id = p.ID
              WHERE p.ID IS NULL OR p.post_status = 'trash'
              ORDER BY v.page_id, v.version_id";
    
    $results = $wpdb->get_results($query);
    return $results;
}

/**
 * 清理指定页面ID的所有版本
 */
function cleanup_versions_by_page($page_id) {
    global $wpdb, $version_table;
    
    $page_id = intval($page_id);
    if (!$page_id) return false;
    
    $result = $wpdb->delete($version_table, array('page_id' => $page_id), array('%d'));
    return $result;
}

/**
 * 清理所有孤儿记录
 */
function cleanup_all_orphaned_versions() {
    global $wpdb, $version_table, $posts_table;
    
    $query = "DELETE v FROM $version_table v 
              LEFT JOIN $posts_table p ON v.page_id = p.ID
              WHERE p.ID IS NULL OR p.post_status = 'trash'";
    
    $result = $wpdb->query($query);
    return $result;
}

// 处理表单提交
$message = '';
$message_type = '';
$orphaned_versions = get_orphaned_versions();

if (isset($_POST['clean_all']) && $_POST['clean_all'] == 1) {
    // 清理所有孤儿记录
    $deleted = cleanup_all_orphaned_versions();
    if ($deleted !== false) {
        $message = "成功清理了 {$deleted} 条孤儿记录！";
        $message_type = 'updated';
        $orphaned_versions = array(); // 清空列表
    } else {
        $message = "清理失败: " . $wpdb->last_error;
        $message_type = 'error';
    }
} else if (isset($_POST['clean_page']) && !empty($_POST['page_id'])) {
    // 清理特定页面的记录
    $page_id = intval($_POST['page_id']);
    $deleted = cleanup_versions_by_page($page_id);
    if ($deleted !== false) {
        $message = "成功清理了页面 ID {$page_id} 的 {$deleted} 条记录！";
        $message_type = 'updated';
        $orphaned_versions = get_orphaned_versions(); // 重新获取列表
    } else {
        $message = "清理失败: " . $wpdb->last_error;
        $message_type = 'error';
    }
}

// 输出页面内容
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>孤儿愿望清单版本清理工具</title>
    <?php wp_head(); ?>
    <style>
        .wrap {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .orphaned-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orphaned-table th, .orphaned-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .orphaned-table th {
            background-color: #f5f5f5;
        }
        .orphaned-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .message {
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .updated {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .button-container {
            margin: 20px 0;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body class="wp-admin">
    <div class="wrap">
        <h1>孤儿愿望清单版本清理工具</h1>
        <p>此工具用于清理引用不存在页面的愿望清单版本记录。</p>
        
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (empty($orphaned_versions)): ?>
            <div class="message updated">没有找到孤儿记录。所有愿望清单版本均引用有效页面。</div>
        <?php else: ?>
            <h2>找到 <?php echo count($orphaned_versions); ?> 条孤儿记录：</h2>
            
            <div class="button-container">
                <form method="post">
                    <input type="hidden" name="clean_all" value="1">
                    <button type="submit" class="button button-primary" onclick="return confirm('确认清理所有孤儿记录？此操作不可撤销。');">
                        清理所有孤儿记录
                    </button>
                </form>
            </div>
            
            <table class="orphaned-table">
                <thead>
                    <tr>
                        <th>版本ID</th>
                        <th>用户ID</th>
                        <th>页面ID</th>
                        <th>版本号</th>
                        <th>版本名称</th>
                        <th>创建时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $current_page_id = 0;
                    foreach ($orphaned_versions as $version): 
                        $page_id_changed = ($current_page_id != $version->page_id);
                        $current_page_id = $version->page_id;
                    ?>
                        <tr<?php echo $page_id_changed ? ' style="border-top: 2px solid #0073aa;"' : ''; ?>>
                            <td><?php echo $version->version_id; ?></td>
                            <td><?php echo $version->user_id; ?></td>
                            <td><?php echo $version->page_id; ?></td>
                            <td><?php echo $version->version_number; ?></td>
                            <td><?php echo $version->version_name; ?></td>
                            <td><?php echo $version->created_at; ?></td>
                            <td>
                                <?php if ($page_id_changed): ?>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="clean_page" value="1">
                                    <input type="hidden" name="page_id" value="<?php echo $version->page_id; ?>">
                                    <button type="submit" class="button button-small" onclick="return confirm('确认清理页面 ID <?php echo $version->page_id; ?> 的所有记录？此操作不可撤销。');">
                                        清理此页面所有记录
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
