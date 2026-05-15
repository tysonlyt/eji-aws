<?php
/**
 * Admin UI Component
 * 
 * Handles admin interface functionality
 *
 * @package Unique_Client_Page
 * @since 1.3.2
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin UI Class
 */
class UCP_Admin_UI {
    /**
     * Component instance
     *
     * @var UCP_Admin_UI
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     *
     * @return UCP_Admin_UI Component instance
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
        // Initialization code
    }
    
    /**
     * Register hooks
     */
    public function register_hooks() {
        // Add admin menus
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        // Main menu
        add_menu_page(
            __('Unique Client Page', 'unique-client-page'),
            __('Client Pages', 'unique-client-page'),
            'manage_options',
            'unique-client-page',
            array($this, 'render_admin_page'),
            'dashicons-clipboard',
            30
        );
        
        // Create page submenu
        add_submenu_page(
            'unique-client-page',
            __('Create Page', 'unique-client-page'),
            __('Create Page', 'unique-client-page'),
            'manage_options',
            'ucp-create-page',
            array($this, 'render_create_page')
        );
        
        // Edit page submenu
        add_submenu_page(
            'unique-client-page',
            __('Edit Page', 'unique-client-page'),
            __('Edit Page', 'unique-client-page'),
            'manage_options',
            'ucp-edit-page',
            array($this, 'render_edit_page')
        );
        
        // Wishlist management submenu
        add_submenu_page(
            'unique-client-page',
            __('Wishlist Management', 'unique-client-page'),
            __('Wishlist Management', 'unique-client-page'),
            'manage_options',
            'ucp-wishlist-manage',
            array($this, 'render_wishlist_manage_page')
        );
        
        // Settings submenu
        add_submenu_page(
            'unique-client-page',
            __('Settings', 'unique-client-page'),
            __('Settings', 'unique-client-page'),
            'manage_options',
            'ucp-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page
     */
    public function enqueue_admin_scripts($hook) {
        // Check if we're on our plugin's admin pages
        if (strpos($hook, 'unique-client-page') !== false || strpos($hook, 'ucp-') !== false) {
            // Enqueue admin CSS
            wp_enqueue_style(
                'ucp-admin-css',
                plugin_dir_url(dirname(__FILE__)) . 'assets/css/admin-style.css',
                array(),
                defined('UCP_VERSION') ? UCP_VERSION : '1.3.2'
            );
            
            // Enqueue admin JS
            wp_enqueue_script(
                'ucp-admin-js',
                plugin_dir_url(dirname(__FILE__)) . 'assets/js/admin-script.js',
                array('jquery', 'jquery-ui-sortable', 'wp-util'),
                defined('UCP_VERSION') ? UCP_VERSION : '1.3.2',
                true
            );
            
            // Localize admin script
            wp_localize_script(
                'ucp-admin-js',
                'ucp_admin_vars',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('ucp_admin_nonce'),
                    'strings' => array(
                        'confirm_delete' => __('Are you sure you want to delete this item?', 'unique-client-page'),
                        'saving' => __('Saving...', 'unique-client-page'),
                        'saved' => __('Saved!', 'unique-client-page'),
                        'error' => __('Error', 'unique-client-page')
                    )
                )
            );
        }
    }
    
    /**
     * Render main admin page
     */
    public function render_admin_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Display the main admin interface
        ?>
        <div class="wrap ucp-admin-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="ucp-admin-content">
                <h2><?php _e('Client Pages Overview', 'unique-client-page'); ?></h2>
                
                <?php
                // Get all client pages
                $pages = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'unique-client-template.php'
                ));
                
                if (!empty($pages)) {
                    ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Page Title', 'unique-client-page'); ?></th>
                                <th><?php _e('Created', 'unique-client-page'); ?></th>
                                <th><?php _e('Client', 'unique-client-page'); ?></th>
                                <th><?php _e('Products', 'unique-client-page'); ?></th>
                                <th><?php _e('Actions', 'unique-client-page'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($pages as $page) {
                                $client_id = get_post_meta($page->ID, '_ucp_client_id', true);
                                $client = !empty($client_id) ? get_user_by('id', $client_id) : null;
                                $product_count = $this->get_page_product_count($page->ID);
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html($page->post_title); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($page->post_date))); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($client) {
                                            echo esc_html($client->display_name . ' (' . $client->user_email . ')');
                                        } else {
                                            echo '<em>' . esc_html__('No client assigned', 'unique-client-page') . '</em>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo esc_html($product_count); ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url(admin_url('admin.php?page=ucp-edit-page&page_id=' . $page->ID)); ?>" class="button">
                                            <?php _e('Edit', 'unique-client-page'); ?>
                                        </a>
                                        <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" class="button" target="_blank">
                                            <?php _e('View', 'unique-client-page'); ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    ?>
                    <div class="ucp-notice">
                        <p><?php _e('No client pages found.', 'unique-client-page'); ?></p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=ucp-create-page')); ?>" class="button button-primary">
                            <?php _e('Create First Page', 'unique-client-page'); ?>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render create page admin page
     */
    public function render_create_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Display the create page interface
        ?>
        <div class="wrap ucp-admin-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="ucp-admin-content">
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="ucp-create-form">
                    <?php wp_nonce_field('ucp_create_page', 'ucp_create_page_nonce'); ?>
                    <input type="hidden" name="action" value="ucp_create_page">
                    
                    <div class="form-group">
                        <label for="page_title"><?php _e('Client Name', 'unique-client-page'); ?></label>
                        <input type="text" name="page_title" id="page_title" class="regular-text" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="client_id"><?php _e('Select Client', 'unique-client-page'); ?></label>
                        <select name="client_id" id="client_id">
                            <option value=""><?php _e('Select a client', 'unique-client-page'); ?></option>
                            <?php
                            $clients = get_users(array('role' => 'customer'));
                            foreach ($clients as $client) {
                                echo '<option value="' . esc_attr($client->ID) . '">' . esc_html($client->display_name . ' (' . $client->user_email . ')') . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="button button-primary"><?php _e('Create Page', 'unique-client-page'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render edit page admin page
     */
    public function render_edit_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Get page ID from URL
        $page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;
        
        if (!$page_id) {
            ?>
            <div class="wrap ucp-admin-wrap">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                
                <div class="ucp-admin-content">
                    <div class="ucp-notice ucp-error">
                        <p><?php _e('No page ID specified.', 'unique-client-page'); ?></p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=unique-client-page')); ?>" class="button">
                            <?php _e('Back to Overview', 'unique-client-page'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        
        // Get page data
        $page = get_post($page_id);
        
        if (!$page || $page->post_type !== 'page') {
            ?>
            <div class="wrap ucp-admin-wrap">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                
                <div class="ucp-admin-content">
                    <div class="ucp-notice ucp-error">
                        <p><?php _e('Invalid page ID.', 'unique-client-page'); ?></p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=unique-client-page')); ?>" class="button">
                            <?php _e('Back to Overview', 'unique-client-page'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        
        // Check if this is a client page
        $page_template = get_post_meta($page_id, '_wp_page_template', true);
        
        if ($page_template !== 'unique-client-template.php') {
            ?>
            <div class="wrap ucp-admin-wrap">
                <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
                
                <div class="ucp-admin-content">
                    <div class="ucp-notice ucp-error">
                        <p><?php _e('This is not a client page.', 'unique-client-page'); ?></p>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=unique-client-page')); ?>" class="button">
                            <?php _e('Back to Overview', 'unique-client-page'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        
        // Get client ID
        $client_id = get_post_meta($page_id, '_ucp_client_id', true);
        $client = !empty($client_id) ? get_user_by('id', $client_id) : null;
        
        // Display the edit page interface
        ?>
        <div class="wrap ucp-admin-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="ucp-admin-content">
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="ucp-edit-form">
                    <?php wp_nonce_field('ucp_edit_page', 'ucp_edit_page_nonce'); ?>
                    <input type="hidden" name="action" value="ucp_edit_page">
                    <input type="hidden" name="page_id" value="<?php echo esc_attr($page_id); ?>">
                    
                    <div class="form-group">
                        <label for="page_title"><?php _e('Client Name', 'unique-client-page'); ?></label>
                        <input type="text" name="page_title" id="page_title" class="regular-text" value="<?php echo esc_attr($page->post_title); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="client_id"><?php _e('Select Client', 'unique-client-page'); ?></label>
                        <select name="client_id" id="client_id">
                            <option value=""><?php _e('Select a client', 'unique-client-page'); ?></option>
                            <?php
                            $clients = get_users(array('role' => 'customer'));
                            foreach ($clients as $c) {
                                $selected = $client && $client->ID == $c->ID ? ' selected' : '';
                                echo '<option value="' . esc_attr($c->ID) . '"' . $selected . '>' . esc_html($c->display_name . ' (' . $c->user_email . ')') . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="ucp-product-selector">
                        <h3><?php _e('Selected Products', 'unique-client-page'); ?></h3>
                        <div id="ucp-selected-products" class="ucp-product-list">
                            <?php
                            $selected_products = get_post_meta($page_id, '_ucp_selected_products', true);
                            if (!empty($selected_products)) {
                                $selected_products = maybe_unserialize($selected_products);
                                foreach ($selected_products as $product_id) {
                                    $product = wc_get_product($product_id);
                                    if (!$product) {
                                        continue;
                                    }
                                    ?>
                                    <div class="ucp-product-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                        <?php
                                        if ($product->get_image_id()) {
                                            echo '<div class="product-image">' . wp_get_attachment_image($product->get_image_id(), 'thumbnail') . '</div>';
                                        }
                                        ?>
                                        <div class="product-info">
                                            <div class="product-title"><?php echo esc_html($product->get_name()); ?></div>
                                            <div class="product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                                        </div>
                                        <div class="product-actions">
                                            <a href="#" class="remove-product">×</a>
                                            <input type="hidden" name="selected_products[]" value="<?php echo esc_attr($product_id); ?>">
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        
                        <div class="ucp-add-product">
                            <h3><?php _e('Add Products', 'unique-client-page'); ?></h3>
                            <input type="text" id="ucp-product-search" placeholder="<?php esc_attr_e('Search products...', 'unique-client-page'); ?>">
                            <div id="ucp-search-results" class="ucp-search-results"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="button button-primary"><?php _e('Update Page', 'unique-client-page'); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Process form submission
        if (isset($_POST['ucp_settings_nonce']) && wp_verify_nonce($_POST['ucp_settings_nonce'], 'ucp_save_settings')) {
            // Save settings
            update_option('ucp_products_per_page', isset($_POST['products_per_page']) ? intval($_POST['products_per_page']) : 12);
            update_option('ucp_enable_categories', isset($_POST['enable_categories']) ? 1 : 0);
            update_option('ucp_enable_search', isset($_POST['enable_search']) ? 1 : 0);
            
            // Show success message
            add_settings_error('ucp_settings', 'settings_updated', __('Settings saved.', 'unique-client-page'), 'updated');
        }
        
        // Get current settings
        $products_per_page = get_option('ucp_products_per_page', 12);
        $enable_categories = get_option('ucp_enable_categories', 1);
        $enable_search = get_option('ucp_enable_search', 1);
        
        // Display the settings interface
        ?>
        <div class="wrap ucp-admin-wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <?php settings_errors('ucp_settings'); ?>
            
            <div class="ucp-admin-content">
                <form method="post" action="">
                    <?php wp_nonce_field('ucp_save_settings', 'ucp_settings_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="products_per_page"><?php _e('Products Per Page', 'unique-client-page'); ?></label>
                            </th>
                            <td>
                                <input type="number" name="products_per_page" id="products_per_page" value="<?php echo esc_attr($products_per_page); ?>" min="1" max="100">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Enable Categories', 'unique-client-page'); ?>
                            </th>
                            <td>
                                <label for="enable_categories">
                                    <input type="checkbox" name="enable_categories" id="enable_categories" <?php checked($enable_categories); ?>>
                                    <?php _e('Allow filtering by product categories', 'unique-client-page'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <?php _e('Enable Search', 'unique-client-page'); ?>
                            </th>
                            <td>
                                <label for="enable_search">
                                    <input type="checkbox" name="enable_search" id="enable_search" <?php checked($enable_search); ?>>
                                    <?php _e('Allow searching products', 'unique-client-page'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" class="button button-primary" value="<?php esc_attr_e('Save Settings', 'unique-client-page'); ?>">
                    </p>
                </form>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get product count for a page
     *
     * @param int $page_id Page ID
     * @return int Product count
     */
    private function get_page_product_count($page_id) {
        $selected_products = get_post_meta($page_id, '_ucp_selected_products', true);
        
        if (!empty($selected_products)) {
            $selected_products = maybe_unserialize($selected_products);
            return count($selected_products);
        }
        
        return 0;
    }
    
    /**
     * Render wishlist management page
     *
     * Shows wishlist versions for a specific page and user
     */
    public function render_wishlist_manage_page() {
        global $wp_query, $wpdb;
        
        // Add version list styles
        
        // Check for page_id parameter
        if (!isset($_GET['page_id']) || empty($_GET['page_id'])) {
            // If no page_id is provided, show list of all client pages
            $this->render_wishlist_pages_list();
        } else {
            $page_id = intval($_GET['page_id']);
            $page = get_post($page_id);
            
            if (!$page) {
                wp_die(__('Page not found.', 'unique-client-page'));
            }
            
            // Get the site URL for testing on local or production
            $site_url = get_site_url();
            
            // Include the wishlist management template
            include_once(UCP_PLUGIN_PATH . 'admin/modules/wishlist/views/wishlist-management.php');
        }
    }
    
    /**
     * Render wishlist pages list when no page_id is provided
     */
    private function render_wishlist_pages_list() {
        // Get all client pages
        $client_pages = get_posts(array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'unique-client-template.php',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        echo '<div class="wrap">';
        echo '<h1>' . __('Wishlist Viewer', 'unique-client-page') . '</h1>';
        echo '<p>' . __('Select a client page to view its wishlist:', 'unique-client-page') . '</p>';
        
        if (empty($client_pages)) {
            echo '<p>' . __('No client pages found. Please create a client page first.', 'unique-client-page') . '</p>';
            echo '<p><a href="' . admin_url('admin.php?page=create-unique-client-page') . '" class="button button-primary">' . __('Create Client Page', 'unique-client-page') . '</a></p>';
        } else {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>
                <th>' . __('Page Title', 'unique-client-page') . '</th>
                <th>' . __('Product Count', 'unique-client-page') . '</th>
                <th>' . __('Versions', 'unique-client-page') . '</th>
                <th>' . __('Actions', 'unique-client-page') . '</th>
            </tr></thead>';
            echo '<tbody>';
            
            foreach ($client_pages as $page) {
                // Calculate the number of user wishlist items for this page
                $user_count = $this->count_user_wishlist_items($page->ID);
                
                // Get page products (admin selected)
                $page_products = get_post_meta($page->ID, '_ucp_wishlist', true);
                $page_product_count = is_array($page_products) ? count($page_products) : 0;
                $total_products = $user_count + $page_product_count;
                
                // Get wishlist versions
                global $wpdb;
                $version_table = $wpdb->prefix . 'ucp_wishlist_versions';
                
                // Debug - Check if table exists and create it if not
                if($wpdb->get_var("SHOW TABLES LIKE '$version_table'") != $version_table) {
                    // Create the table
                    $charset_collate = $wpdb->get_charset_collate();
                    $sql = "CREATE TABLE $version_table (
                        id bigint(20) NOT NULL AUTO_INCREMENT,
                        page_id bigint(20) NOT NULL,
                        user_id bigint(20) NOT NULL,
                        version_number int(11) NOT NULL,
                        version_name varchar(255),
                        wishlist_data longtext,
                        created_by bigint(20) NOT NULL,
                        created_at datetime NOT NULL,
                        is_current tinyint(1) DEFAULT 0,
                        notes text,
                        PRIMARY KEY (id),
                        KEY page_id (page_id),
                        KEY user_id (user_id)
                    ) $charset_collate;";
                    
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    dbDelta($sql);
                }
                
                // Check versions count
                $version_count = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $version_table WHERE page_id = %d",
                    $page->ID
                ));
                
                // Get wishlist sent status
                $wishlist_sent = get_post_meta($page->ID, '_wishlist_sent', true);
                $last_sent = get_post_meta($page->ID, '_wishlist_last_sent', true);
                
                // Get version information from database
                $versions = $wpdb->get_results($wpdb->prepare(
                    "SELECT DISTINCT version_number, version_name, created_at 
                     FROM $version_table 
                     WHERE page_id = %d 
                     ORDER BY version_number DESC",
                    $page->ID
                ));
                
                // Process version display
                if ($wishlist_sent == 'yes' || !empty($versions)) {
                    // Initialize version display
                    $email_status = '';
                    
                    // Version list display
                    if (!empty($versions)) {
                        $email_status .= '<div class="versions-list">';
                        
                        // Track displayed versions
                        $shown_versions = array();
                        
                        foreach ($versions as $index => $version) {
                            // Get version name
                            $v_name = isset($version->version_name) && !empty($version->version_name) 
                                ? $version->version_name 
                                : sprintf('version%02d', $version->version_number);
                            
                            // Avoid duplicate versions
                            if (in_array($v_name, $shown_versions)) {
                                continue;
                            }
                            
                            // Mark this version as shown
                            $shown_versions[] = $v_name;
                            
                            // Format creation time
                            $v_time = date_i18n('Y-m-d H:i', strtotime($version->created_at));
                            
                            // Add version entry - clickable to open modal
                            $email_status .= '<div class="version-item">';
                            $email_status .= '<a href="#" class="version-link" data-version-id="' . esc_attr($version->version_number) . '" data-page-id="' . esc_attr($page->ID) . '" data-version-time="' . esc_attr($v_time) . '" title="' . esc_attr($v_time) . '">' . $v_name . '</a>';
                            $email_status .= '</div>';
                        }
                        $email_status .= '</div>';
                    } else {
                        // If no version records but marked as sent, show mock version
                        $email_status .= 'version01';
                    }
                } else {
                    // Indication that wishlist has never been sent
                    $email_status = '<span class="dashicons dashicons-no" style="color:#dc3232;" title="' . 
                        esc_attr(__('No wishlist version has been sent yet', 'unique-client-page')) . '"></span> ' . 
                        __('no reply yet', 'unique-client-page');
                }
                
                echo '<tr>';
                echo '<td>' . esc_html($page->post_title) . '</td>';
                echo '<td>' . $total_products . ' ' . _n('product', 'products', $total_products, 'unique-client-page') . 
                     ' <small>(' . $user_count . ' users, ' . $page_product_count . ' page)</small></td>';
                echo '<td>' . $email_status . '</td>';
                echo '<td><a href="' . admin_url('admin.php?page=ucp-wishlist-manage&page_id=' . $page->ID) . '" class="button">' . 
                     '<span class="dashicons dashicons-visibility"></span> ' . __('View', 'unique-client-page') . '</a></td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
            
            // Add some basic styles
            echo '<style>
                .wp-list-table th { font-weight: 600; }
                .wp-list-table td { vertical-align: middle; }
                .wp-list-table small { color: #666; }
                .dashicons { vertical-align: middle; margin-right: 3px; }
                .versions-list { margin-top: 5px; }
                .version-item { margin-bottom: 3px; }
                .version-link { text-decoration: none; color: #0073aa; cursor: pointer; }
                .version-link:hover { text-decoration: underline; }
            </style>';
        }
        
        echo '</div>';
    }
    
    /**
     * Count wishlist items for a specific page across all users
     * 
     * @param int $page_id Page ID
     * @return int Number of wishlist items
     */
    private function count_user_wishlist_items($page_id) {
        global $wpdb;
        
        $meta_key = '_ucp_wishlist_' . $page_id;
        $count = 0;
        
        // Find all users with wishlist items for this page
        $users_with_wishlists = $wpdb->get_results($wpdb->prepare(
            "SELECT user_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key = %s",
            $meta_key
        ));
        
        // Count all items
        // Collect data
        foreach ($users_with_wishlists as $user_data) {
            $user_id = $user_data->user_id;
            $user_info = get_userdata($user_id);
            
            if (!$user_info) continue; // Skip if user no longer exists
            
            $user_wishlist = maybe_unserialize($user_data->meta_value);
            
            if (!is_array($user_wishlist) || empty($user_wishlist)) continue;
            
            foreach ($user_wishlist as $product_id) {
                $product = wc_get_product($product_id);
                if (!$product) continue; // Skip if product no longer exists
                
                // Get date added if available
                $dates_meta_key = $meta_key . '_dates';
                $dates = get_user_meta($user_id, $dates_meta_key, true);
                $date_added = (is_array($dates) && isset($dates[$product_id])) ? 
                    date('Y-m-d H:i:s', $dates[$product_id]) : 'Unknown';
                
                $user_wishlist_data[] = [
                    'user' => $user_info,
                    'product' => $product,
                    'date_added' => $date_added
                ];
            }
        }
        
        // Render page
        echo '<div class="wrap">';
        echo '<h1>' . __('Wishlist for: ', 'unique-client-page') . esc_html($page->post_title) . '</h1>';
        echo '<p><a href="' . admin_url('admin.php?page=unique-client-page') . '" class="button">' . __('Back to Client Pages', 'unique-client-page') . '</a></p>';
        
        // User wishlist section
        echo '<h2>' . __('User Wishlist Items', 'unique-client-page') . '</h2>';
        
        if (empty($user_wishlist_data)) {
            echo '<p>' . __('No users have added items to their wishlist for this page yet.', 'unique-client-page') . '</p>';
        } else {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>' . __('SKU Number', 'unique-client-page') . '</th>';
            echo '<th>' . __('Product', 'unique-client-page') . '</th>';
            echo '<th>' . __('Date Added', 'unique-client-page') . '</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            
            foreach ($user_wishlist_data as $item) {
                $sku = $item['product']->get_sku();
                if (empty($sku)) {
                    $sku = 'N/A';
                }
                
                echo '<tr>';
                echo '<td>' . esc_html($sku) . '</td>';
                echo '<td>' . esc_html($item['product']->get_name()) . '</td>';
                echo '<td>' . esc_html($item['date_added']) . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
        }
        
        echo '</div>';
        
        echo '<script>
        // Debug function
        function ucpDebugLog(msg, data) {
            if (console && console.log) {
                console.log("[UCP Debug] " + msg, data || "");
            }
        }
        
        // Execute when page is fully loaded
        jQuery(document).ready(function($) {
            // Debug: check if version links are found
            var versionLinks = $(".version-link");
            ucpDebugLog("Version links found: " + versionLinks.length);
            
            // Check if UCPModal class is available
            if (typeof UCPModal === "undefined") {
                ucpDebugLog("Error: UCPModal class is not defined! Please make sure ucp-modal-manager.js is properly loaded");
            } else {
                ucpDebugLog("UCPModal class is loaded");
            }
        });
        </script>';
    }
    
    // 结束类定义
}
