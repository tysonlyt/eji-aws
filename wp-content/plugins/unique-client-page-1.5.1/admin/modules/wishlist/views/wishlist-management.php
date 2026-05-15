<?php if (!defined('ABSPATH')) exit; ?>

<div class="wrap">
    <h1><?php esc_html_e('Wishlist Version Management', 'unique-client-page'); ?></h1>
    
    <?php if (isset($page) && $page): ?>
    <div class="page-info">
        <h2><?php printf(esc_html__('Page: %s', 'unique-client-page'), esc_html($page->post_title)); ?></h2>
        <p>
            <a href="<?php echo esc_url(admin_url('admin.php?page=unique-client-page')); ?>" class="button">
                <?php esc_html_e('Back to Client Pages', 'unique-client-page'); ?>
            </a>
            <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" class="button" target="_blank">
                <?php esc_html_e('View Page', 'unique-client-page'); ?>
            </a>
        </p>
    </div>
    <?php endif; ?>
    
    <div class="card">
        <h2><?php esc_html_e('Available Versions', 'unique-client-page'); ?></h2>
        <div class="ucp-wishlist-version-list">
            <?php if (!empty($wishlists)) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Version', 'unique-client-page'); ?></th>
                            <th><?php esc_html_e('Name', 'unique-client-page'); ?></th>
                            <?php if (!isset($page)): ?>
                            <th><?php esc_html_e('Page', 'unique-client-page'); ?></th>
                            <?php endif; ?>
                            <th><?php esc_html_e('User', 'unique-client-page'); ?></th>
                            <th><?php esc_html_e('Created', 'unique-client-page'); ?></th>
                            <th><?php esc_html_e('Status', 'unique-client-page'); ?></th>
                            <th><?php esc_html_e('Actions', 'unique-client-page'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wishlists as $list) : 
                            $user_info = get_userdata($list->user_id);
                            $page_info = isset($page) ? $page : get_post($list->page_id);
                            $username = $user_info ? $user_info->user_login : __('Unknown', 'unique-client-page');
                            $page_title = $page_info ? $page_info->post_title : __('Unknown', 'unique-client-page');
                        ?>
                            <tr class="<?php echo $list->is_current ? 'is-current-version' : ''; ?>">
                                <td><?php echo esc_html($list->version_number); ?></td>
                                <td><?php echo esc_html($list->version_name); ?></td>
                                <?php if (!isset($page)): ?>
                                <td>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=ucp-wishlist-manage&page_id=' . $list->page_id)); ?>">
                                        <?php echo esc_html($page_title); ?>
                                    </a>
                                </td>
                                <?php endif; ?>
                                <td><?php echo esc_html($username); ?></td>
                                <td><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($list->created_at))); ?></td>
                                <td>
                                    <?php if ($list->is_current): ?>
                                        <span class="current-version-badge"><?php esc_html_e('Current', 'unique-client-page'); ?></span>
                                    <?php else: ?>
                                        <span class="inactive-version-badge"><?php esc_html_e('Inactive', 'unique-client-page'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="#" 
                                       class="button view-version" 
                                       data-version-id="<?php echo esc_attr($list->version_id); ?>"
                                       data-page-id="<?php echo esc_attr($list->page_id); ?>">
                                        <?php esc_html_e('View', 'unique-client-page'); ?>
                                    </a>
                                    <?php if (!$list->is_current): ?>
                                    <a href="#" 
                                       class="button set-as-current" 
                                       data-version-id="<?php echo esc_attr($list->version_id); ?>"
                                       data-page-id="<?php echo esc_attr($list->page_id); ?>">
                                        <?php esc_html_e('Set as Current', 'unique-client-page'); ?>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <style>
                    .current-version-badge {
                        background: #4CAF50;
                        color: white;
                        padding: 3px 8px;
                        border-radius: 3px;
                        font-size: 12px;
                        font-weight: 500;
                    }
                    .inactive-version-badge {
                        background: #999;
                        color: white;
                        padding: 3px 8px;
                        border-radius: 3px;
                        font-size: 12px;
                        font-weight: 500;
                    }
                    .is-current-version {
                        background-color: #f0fff0;
                    }
                </style>
            <?php else : ?>
                <p><?php esc_html_e('No wishlist versions found.', 'unique-client-page'); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div id="ucp-version-modal" class="ucp-modal" style="display: none;">
        <div class="ucp-modal-backdrop"></div>
        <div class="ucp-modal-container ucp-modal-medium">
            <div class="ucp-modal-header">
                <h3 class="ucp-modal-title"><?php esc_html_e('Version Details', 'unique-client-page'); ?></h3>
                <button type="button" class="ucp-modal-close">&times;</button>
            </div>
            <div class="ucp-modal-body">
                <div class="ucp-loading"><?php esc_html_e('Loading...', 'unique-client-page'); ?></div>
                <div class="ucp-version-details" style="display: none;">
                    <h4><?php esc_html_e('Version Information', 'unique-client-page'); ?></h4>
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e('Version Number:', 'unique-client-page'); ?></th>
                            <td class="version-number"></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e('Version Name:', 'unique-client-page'); ?></th>
                            <td class="version-name"></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e('Status:', 'unique-client-page'); ?></th>
                            <td class="version-status"></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e('Created At:', 'unique-client-page'); ?></th>
                            <td class="version-date"></td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e('Notes:', 'unique-client-page'); ?></th>
                            <td class="version-notes"></td>
                        </tr>
                    </table>
                    
                    <h4><?php esc_html_e('Product List', 'unique-client-page'); ?></h4>
                    <div class="ucp-version-products">
                        <!-- Products will be loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
