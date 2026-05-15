<?php
/**
 * Plugin Name: Unique Client Page
 * Plugin URI: 
 * Description: Create a custom product page that allows selecting products from the product library and integrates with Wishlist.
 * Version: 2.0.0
 * Author: EveryIdeas
 * Author URI: https://everyideas.com
 * Text Domain: unique-client-page
 * Domain Path: /languages
 * Requires PHP: 7.2
 * Requires at least: 5.4
 * 
 * WC requires at least: 4.0
 * WC tested up to: 7.0
 *
 * @package Unique_Client_Page 
 */

// Prevent direct access to the plugin file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('UCP_PLUGIN_FILE', __FILE__);
define('UCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('UCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('UCP_VERSION', '1.6.4');



// Create necessary directories if they don't exist
$directories = [
    'admin/classes',
    'admin/assets',
    'admin/pages',
    'frontend/classes',
    'frontend/assets',
    'includes/admin'
];

foreach ($directories as $dir) {
    if (!file_exists(UCP_PLUGIN_DIR . $dir)) {
        mkdir(UCP_PLUGIN_DIR . $dir, 0755, true);
    }
}

// Include the resource loader class
require_once UCP_PLUGIN_DIR . 'includes/class-ucp-loader.php';
$ucp_loader = UCP_Loader::get_instance();

// Include the modular wishlist admin class (new architecture)
require_once UCP_PLUGIN_DIR . 'admin/modules/wishlist/class-wishlist-admin.php';

// Legacy code for backwards compatibility
// Include the page-specific wishlist admin functions if they still exist
if (file_exists(UCP_PLUGIN_DIR . 'admin/pages/page-wishlist-admin.php')) {
    require_once UCP_PLUGIN_DIR . 'admin/pages/page-wishlist-admin.php';
}

// Add admin menus for wishlist management (legacy functions)
function ucp_add_admin_menus() {
    // Legacy page wishlist submenu if it exists
    if (function_exists('ucp_add_page_wishlist_submenu')) {
        ucp_add_page_wishlist_submenu();
    }
}
add_action('admin_menu', 'ucp_add_admin_menus');

// Include the main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-ucp-main.php';

// 加载AJAX引导类
require_once plugin_dir_path(__FILE__) . 'includes/class-ucp-ajax-bootstrap.php';

// Include versioning classes
require_once UCP_PLUGIN_DIR . 'includes/class-ucp-wishlist-versioning.php';
require_once UCP_PLUGIN_DIR . 'includes/class-ucp-wishlist-version-manager.php';
require_once UCP_PLUGIN_DIR . 'includes/class-ucp-wishlist-version-ajax.php';

// Initialize the main plugin class
$ucp_main = UCP_Main::get_instance();

// Initialize versioning
UCP_Wishlist_Versioning::get_instance();
UCP_Wishlist_Version_Manager::get_instance();
UCP_Wishlist_Version_Ajax::get_instance();

// Initialize the cleanup handler
require_once UCP_PLUGIN_DIR . 'includes/class-ucp-cleanup-handler.php';

// Initialize the AJAX handler
require_once UCP_PLUGIN_DIR . 'frontend/classes/class-ucp-ajax-handler.php';
$ucp_ajax_handler = UCP_AJAX_Handler::get_instance();

// Initialize the template handler
require_once UCP_PLUGIN_DIR . 'frontend/classes/class-ucp-template-handler.php';
$ucp_template_handler = UCP_Template_Handler::get_instance();
$ucp_template_handler->register_hooks();

// Ensure WooCommerce is activated
function ucp_check_woocommerce_active() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'ucp_woocommerce_missing_notice');
        return false;
    }
    return true;
}

// WooCommerce missing notice
function ucp_woocommerce_missing_notice() {
    ?>
    <div class="error">
        <p><?php _e('The Unique Client Page plugin requires WooCommerce support. Please install and activate WooCommerce.', 'unique-client-page'); ?></p>
    </div>
    <?php
}

// Plugin initialization
function ucp_plugin_init() {
    // Check required plugins
    if (!ucp_check_woocommerce_active()) {
        return;
    }
    
    // Load text domain
    load_plugin_textdomain('unique-client-page', false, basename(dirname(__FILE__)) . '/languages');
    
    // Load frontend files
    require_once plugin_dir_path(__FILE__) . 'frontend/template-functions.php';
    require_once plugin_dir_path(__FILE__) . 'frontend/wishlist-functions.php';
    
    // Load modal close functionality fix
    require_once plugin_dir_path(__FILE__) . 'frontend/direct-modal-fix.php';
    
    // 暂时禁用 Wishlist Renderer Fix 以避免冲突
    // require_once plugin_dir_path(__FILE__) . 'frontend/wishlist-renderer-fix.php';
    
    // Load Send Wishlist Fix
    require_once plugin_dir_path(__FILE__) . 'frontend/send-wishlist-fix.php';
    
    // Load access code handler
    require_once plugin_dir_path(__FILE__) . 'includes/access-code-handler.php';
    
    // Load product display class (required by AJAX handler) - temporarily disabled due to syntax errors
    // require_once plugin_dir_path(__FILE__) . 'frontend/classes/class-ucp-product-display.php';
    
    // Load product AJAX handler
    require_once plugin_dir_path(__FILE__) . 'includes/class-ucp-product-ajax.php';
    
    // Initialize product AJAX handler
    new UCP_Product_Ajax();
    
    // Load admin files
    if (is_admin()) {
        require_once plugin_dir_path(__FILE__) . 'admin/pages/email-settings.php';
        require_once plugin_dir_path(__FILE__) . 'admin/pages/page-settings.php';
        require_once plugin_dir_path(__FILE__) . 'admin/pages/ucp-email-admin.php';
    }
    
    // Register scripts and styles
    add_action('wp_enqueue_scripts', 'ucp_enqueue_scripts');
    
    // Register shortcode - now managed by UCP_Page_Creator
    // add_shortcode('unique_client_products', 'ucp_products_shortcode');
    
    // Everything else is now handled by the main plugin class and its components
}
add_action('plugins_loaded', 'ucp_plugin_init');

// Register scripts and styles
function ucp_enqueue_scripts() {
    global $post;
    $should_enqueue = false;
    
    // Check if we're in admin or on frontend
    if (is_admin()) {
        // In admin, only load on our plugin pages
        $screen = get_current_screen();
        if (strpos($screen->id, 'ucp-') !== false || 
            (isset($_GET['page']) && strpos($_GET['page'], 'ucp-') === 0)) {
            $should_enqueue = true;
        }
    } else {
        // On frontend, check if we're on a page with the shortcode or a product page
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'unique_client_products')) {
            $should_enqueue = true;
        }
        
        if (function_exists('is_product') && is_product()) {
            $should_enqueue = true;
        }
    }
    
    if ($should_enqueue) {
        // Load core styles
        wp_enqueue_style('ucp-core', UCP_PLUGIN_URL . 'assets/shared/css/_variables.css', array(), UCP_VERSION);
        
        // Load base styles that depend on variables
        wp_enqueue_style('ucp-base', UCP_PLUGIN_URL . 'assets/shared/css/_base.css', array('ucp-core'), UCP_VERSION);
        
        // Load components
        wp_enqueue_style('ucp-components', UCP_PLUGIN_URL . 'assets/shared/css/_buttons.css', array('ucp-core'), UCP_VERSION);
        
        // Load layouts
        wp_enqueue_style('ucp-layouts', UCP_PLUGIN_URL . 'assets/shared/css/_grid.css', array('ucp-core'), UCP_VERSION);
        
        // Load pages
        wp_enqueue_style('ucp-product', UCP_PLUGIN_URL . 'frontend/assets/css/_product.css', array('ucp-core'), UCP_VERSION);
        wp_enqueue_style('ucp-wishlist', UCP_PLUGIN_URL . 'frontend/assets/css/_wishlist.css', array('ucp-core'), UCP_VERSION);
        
        // Load modals component
        wp_enqueue_style('ucp-modals', UCP_PLUGIN_URL . 'modules/product-selector/assets/css/_modals.css', array('ucp-core'), UCP_VERSION);
        
        // Font Awesome for icons
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
        
        // Add AJAX URL and nonce to the page (must be added before scripts)
        wp_localize_script('jquery', 'ucp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('ucp-ajax-nonce')
        ));
        
        // Also add global ajaxurl for compatibility
        wp_add_inline_script('jquery', 'window.ajaxurl = "' . admin_url('admin-ajax.php') . '";', 'after');
        
        // Load modal manager first (in header)
        wp_enqueue_script('ucp-modal-manager', UCP_PLUGIN_URL . 'frontend/assets/js/ucp-modal-manager.js', array('jquery'), UCP_VERSION, false);
        
        // Important: Product details script in header to ensure global functions are available
        wp_enqueue_script('ucp-product-details', UCP_PLUGIN_URL . 'frontend/assets/js/ucp-product-details.js', array('jquery', 'ucp-modal-manager'), UCP_VERSION, false);
        
        // Load frontend product selector with integrated notification and modal managers
        wp_enqueue_script('ucp-product-selector', UCP_PLUGIN_URL . 'modules/product-selector/assets/js/ucp-shared-product-selector.js', array('jquery', 'ucp-modal-manager'), UCP_VERSION, true);
        
        // Pagination removed - all products shown on single page
        // wp_enqueue_script('ucp-pagination', UCP_PLUGIN_URL . 'assets/js/ucp-pagination.js', array('jquery'), UCP_VERSION, true);
        
        // Pass some settings to JS
        $ucp_params = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            // General AJAX nonce (legacy)
            'nonce' => wp_create_nonce('ucp-ajax-nonce'),
            // Product details specific nonce for stricter verification
            'product_nonce' => wp_create_nonce('ucp-product-nonce'),
            // Provide both for backward compatibility
            'current_page_id' => get_the_ID(),
            'page_id' => get_the_ID(),
            'plugin_url' => UCP_PLUGIN_URL,
            'version' => UCP_VERSION,
            'debug_mode' => defined('WP_DEBUG') && WP_DEBUG,
            'i18n' => array(
                'view_details' => __('View Details', 'unique-client-page'),
                'hide_details' => __('Hide Details', 'unique-client-page'),
                'loading' => __('Loading...', 'unique-client-page'),
                'error_loading' => __('Error loading product details. Please try again.', 'unique-client-page')
            )
        );
        
        // Localize scripts with parameters
        wp_localize_script('ucp-modal-manager', 'ucp_params', $ucp_params);
        wp_localize_script('ucp-product-details', 'ucp_params', $ucp_params);
        wp_localize_script('ucp-product-selector', 'ucp_params', $ucp_params);
        
        // Load the main UCP scripts last
        wp_enqueue_script('ucp-scripts', UCP_PLUGIN_URL . 'assets/js/ucp-scripts.js', array('jquery'), UCP_VERSION, true);
        wp_localize_script('ucp-scripts', 'ucp_params', $ucp_params);
        
        // Wishlist button fix script removed - functionality moved to template
    }
}

// Product shortcode implementation - using new class structure
function ucp_products_shortcode($atts) {
    // Get the UCP_Main instance
    $ucp_main = UCP_Main::get_instance();
    
    // Get the page creator component
    $page_creator = $ucp_main->get_creator();
    
    // Call the render_product_shortcode method
    if (method_exists($page_creator, 'render_product_shortcode')) {
        return $page_creator->render_product_shortcode($atts);
    }
    
    // No fallback to original class
    return '<p>' . __('Product display unavailable', 'unique-client-page') . '</p>';
}

/**
 * Register AJAX handlers
 * 
 * All AJAX handlers have been moved to the UCP_AJAX_Handler class
 * for better code organization and maintainability.
 */

// Main AJAX handler for all requests
add_action('wp_ajax_ucp_ajax_handler', array($ucp_ajax_handler, 'handle_ajax_request'));
add_action('wp_ajax_nopriv_ucp_ajax_handler', array($ucp_ajax_handler, 'handle_ajax_request'));

// Product details handler - disabled to use UCP_Product_Ajax class instead
// add_action('wp_ajax_ucp_get_product_details', array($ucp_ajax_handler, 'handle_ajax_request'));
// add_action('wp_ajax_nopriv_ucp_get_product_details', array($ucp_ajax_handler, 'handle_ajax_request'));

// Wishlist functionality handlers
add_action('wp_ajax_ucp_wishlist_handler', array($ucp_ajax_handler, 'handle_wishlist'));
add_action('wp_ajax_nopriv_ucp_wishlist_handler', array($ucp_ajax_handler, 'handle_wishlist'));

// Get wishlist items
add_action('wp_ajax_ucp_get_wishlist', array($ucp_ajax_handler, 'get_wishlist'));
add_action('wp_ajax_nopriv_ucp_get_wishlist', array($ucp_ajax_handler, 'get_wishlist'));

// Wishlist status
add_action('wp_ajax_ucp_get_wishlist_status', array($ucp_ajax_handler, 'get_wishlist_status'));
add_action('wp_ajax_nopriv_ucp_get_wishlist_status', array($ucp_ajax_handler, 'get_wishlist_status'));

// Wishlist email
add_action('wp_ajax_ucp_send_wishlist_email', array($ucp_ajax_handler, 'send_wishlist_email'));
add_action('wp_ajax_nopriv_ucp_send_wishlist_email', array($ucp_ajax_handler, 'send_wishlist_email'));

// Remove from wishlist
add_action('wp_ajax_ucp_remove_from_wishlist', array($ucp_ajax_handler, 'remove_from_wishlist'));
add_action('wp_ajax_nopriv_ucp_remove_from_wishlist', array($ucp_ajax_handler, 'remove_from_wishlist'));

/**
 * Product details functionality has been moved to the UCP_AJAX_Handler class
 * for better code organization and maintainability.
 * 
 * @see includes/class-ucp-ajax-handler.php
 */
// This function has been moved to the UCP_AJAX_Handler class
// for better code organization and maintainability.
// @see includes/class-ucp-ajax-handler.php

/**
 * Format product description to highlight key labels
 * 
 * @param string $description Product description text
 * @return string Formatted HTML
 */
function ucp_format_product_description($description) {
    // First apply wpautop to add paragraph tags
    $formatted = wpautop($description);
    
    // Use regex to make specific labels bold with unique classes
    $patterns = array(
        '/Material:/' => '<strong class="ucp-spec-label">Material:</strong>',
        '/Weight:/' => '<strong class="ucp-spec-label">Weight:</strong>',
        '/Style:/' => '<strong class="ucp-spec-label">Style:</strong>'
    );
    
    // Apply patterns
    $formatted = preg_replace(array_keys($patterns), array_values($patterns), $formatted);
    
    return $formatted;
}

// Function executed on plugin activation
function ucp_activate() {
    // Add custom page template
    $template_dir = get_stylesheet_directory();
    $source = UCP_PLUGIN_DIR . 'templates/unique-client-template.php';
    $destination = $template_dir . '/unique-client-template.php';
    
    // Check if the template file already exists
    if (!file_exists($destination)) {
        @copy($source, $destination);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ucp_activate');

// Function executed on plugin deactivation
function ucp_deactivate() {
    // Cleanup actions
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'ucp_deactivate');

/**
 * Add a custom link to directly edit plugin code in the plugin list
 * Do not modify the original edit link, but add a new, clearer link 
 */
function ucp_add_custom_edit_link($actions) {
    // Add a new edit link that clearly points to the plugin editor
    $plugin_file = 'unique-client-page/unique-client-page.php';
    $edit_url = admin_url('plugin-editor.php?file=' . $plugin_file);
    
    // Add a special plugin edit link with different text to distinguish it from the default "Edit" link
    $actions['plugin_edit'] = '<a href="' . esc_url($edit_url) . '" style="color:#e27730; font-weight:bold;">' . __('Edit Plugin Code') . '</a>';
    
    // Add a link to the settings page
    $actions['settings'] = '<a href="' . admin_url('admin.php?page=unique-client-page') . '">' . __('Settings') . '</a>';
    
    return $actions;
}

// Add a custom link using the plugin-specific filter
$plugin_base = plugin_basename(__FILE__);
add_filter('plugin_action_links_' . $plugin_base, 'ucp_add_custom_edit_link', 10);

/**
 * Handle admin edit form submission from UCP_Admin_UI::render_edit_page()
 * Action: admin-post.php?action=ucp_edit_page
 */
function ucp_handle_edit_page() {
    // Verify intent
    if (!isset($_POST['action']) || $_POST['action'] !== 'ucp_edit_page') {
        return;
    }

    // Permission check
    if (!current_user_can('edit_pages')) {
        wp_die(__('You do not have sufficient permissions', 'unique-client-page'));
    }

    // Nonce check
    if (!isset($_POST['ucp_edit_page_nonce']) || !wp_verify_nonce($_POST['ucp_edit_page_nonce'], 'ucp_edit_page')) {
        wp_die(__('Security verification failed', 'unique-client-page'));
    }

    $page_id = isset($_POST['page_id']) ? intval($_POST['page_id']) : 0;
    if (!$page_id) {
        wp_die(__('Invalid page ID', 'unique-client-page'));
    }

    // Normalize selected products from either array (selected_products[]) or comma string (selected_products)
    $ids_array = array();
    if (isset($_POST['selected_products']) && is_string($_POST['selected_products'])) {
        $parts = array_map('trim', explode(',', sanitize_text_field($_POST['selected_products'])));
        foreach ($parts as $p) { if ($p !== '' && is_numeric($p)) { $ids_array[] = intval($p); } }
    } elseif (isset($_POST['selected_products']) && is_array($_POST['selected_products'])) {
        foreach ($_POST['selected_products'] as $p) { if (is_numeric($p)) { $ids_array[] = intval($p); } }
    }
    $ids_array = array_values(array_unique(array_filter($ids_array, function($v){ return $v > 0; })));
    $ids_csv = implode(',', $ids_array);

    // Optional fields
    $page_title = isset($_POST['page_title']) ? sanitize_text_field($_POST['page_title']) : '';
    $client_id  = isset($_POST['client_id']) ? intval($_POST['client_id']) : 0;

    // Update page title if provided
    if (!empty($page_title)) {
        wp_update_post(array(
            'ID' => $page_id,
            'post_title' => $page_title,
        ));
    }

    // Update client meta
    if ($client_id > 0) {
        update_post_meta($page_id, '_ucp_client_id', $client_id);
    } else {
        delete_post_meta($page_id, '_ucp_client_id');
    }

    // Save selected products meta (array)
    update_post_meta($page_id, '_ucp_selected_products', $ids_array);

    // Sync shortcode in post content
    $post = get_post($page_id);
    if ($post) {
        $content = $post->post_content;
        $shortcode = '[unique_client_products' . (!empty($ids_csv) ? ' ids="' . esc_attr($ids_csv) . '"' : '') . ']';

        if (preg_match('/\[unique_client_products[^\]]*\]/', $content)) {
            $content = preg_replace('/\[unique_client_products[^\]]*\]/', $shortcode, $content, 1);
        } else {
            // Append shortcode if not present
            if (!empty($content)) { $content .= "\n\n"; }
            $content .= $shortcode;
        }

        // Update post content
        wp_update_post(array(
            'ID' => $page_id,
            'post_content' => $content,
        ));
    }

    // Redirect back to edit page with success flag
    wp_redirect(admin_url('admin.php?page=ucp-edit-page&page_id=' . $page_id . '&updated=1'));
    exit;
}

// Register the admin-post handler
add_action('admin_post_ucp_edit_page', 'ucp_handle_edit_page');
add_action('admin_post_nopriv_ucp_edit_page', 'ucp_handle_edit_page');

/**
 * Add admin menu item 
 * NOTE: Menu is now handled by UCP_Product_Page class to avoid duplication
 */
function ucp_add_admin_menu() {
    /* Commented out to prevent duplicate menus
    add_menu_page(
        __('Unique Client Page Settings', 'unique-client-page'), // Page title
        __('Unique Client', 'unique-client-page'),             // Menu title
        'edit_posts',                                         // Lower capability to allow editors
        'unique-client-page',                                  // Menu slug
        'ucp_settings_page',                                   // Callback function
        'dashicons-admin-generic',                            // Icon
        56                                                    // Position
    );
    */
    
    // Add capability to administrator and editor roles
    $role = get_role('administrator');
    if ($role) {
        $role->add_cap('edit_ucp_settings');
    }
    $role = get_role('editor');
    if ($role) {
        $role->add_cap('edit_ucp_settings');
    }
}
add_action('admin_menu', 'ucp_add_admin_menu');

/**
 * Settings page content 
 */
function ucp_settings_page() {
    // Check user capabilities
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    
    // Add success/error messages
    if (isset($_GET['settings-updated'])) {
        add_settings_error('ucp_messages', 'ucp_message', 
            __('Settings Saved', 'unique-client-page'), 'updated');
    }
    
    // Show error/messages
    settings_errors('ucp_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="card">
            <h2><?php _e('How to Use', 'unique-client-page'); ?></h2>
            <p><?php _e('To display the product selection page, add the following shortcode to any page or post:', 'unique-client-page'); ?></p>
            <code>[unique_client_products]</code>
            
            <h3><?php _e('Creating a New Page', 'unique-client-page'); ?></h3>
            <ol>
                <li><?php _e('Go to Pages > Add New', 'unique-client-page'); ?></li>
                <li><?php _e('Add a title (e.g., "Client Product Selection")', 'unique-client-page'); ?></li>
                <li><?php _e('Add the shortcode: <code>[unique_client_products]</code>', 'unique-client-page'); ?></li>
                <li><?php _e('Publish the page', 'unique-client-page'); ?></li>
            </ol>
        </div>
    </div>
    <?php
}
