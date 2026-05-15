<?php
/**
 * Template for displaying wishlist version details
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Version data is expected in $version variable
if (!isset($version) || empty($version)) {
    echo '<p class="error">版本数据不可用</p>';
    return;
}
?>
<div class="version-details">
    <h3>版本详情</h3>
    <table class="widefat">
        <tr>
            <th>版本号</th>
            <td><?php echo esc_html($version->version_number); ?></td>
        </tr>
        <tr>
            <th>版本名称</th>
            <td><?php echo esc_html($version->version_name); ?></td>
        </tr>
        <tr>
            <th>创建日期</th>
            <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($version->created_at)); ?></td>
        </tr>
        <?php if (!empty($version->notes)): ?>
        <tr>
            <th>备注</th>
            <td><?php echo esc_html($version->notes); ?></td>
        </tr>
        <?php endif; ?>
    </table>
    
    <div class="wishlist-content">
        <h4>愿望清单内容</h4>
        <?php 
        $wishlist_data = maybe_unserialize($version->wishlist_data);
        if (!empty($wishlist_data) && is_array($wishlist_data)): 
        ?>
            <table class="widefat wishlist-items-table">
                <thead>
                    <tr>
                        <th>项目键</th>
                        <th>数据</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($wishlist_data as $key => $item): ?>
                    <tr>
                        <td><?php echo esc_html($key); ?></td>
                        <td>
                            <?php if (is_array($item)): ?>
                                <pre><?php print_r($item); ?></pre>
                            <?php else: ?>
                                <?php echo esc_html($item); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>没有愿望清单数据</p>
        <?php endif; ?>
    </div>
</div>
