<?php
/**
 * Settings Class for UCP Plugin
 *
 * Handles admin menu, settings page, and product page listings
 *
 * @package Unique_Client_Page
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Settings class for UCP plugin
 */
class UCP_Settings extends UCP_Base {
    
    /**
     * Initialize hooks
     */
    public function init() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Add main menu
        add_menu_page(
            __('Unique Client Page', 'unique-client-page'),     // Page title
            __('Unique Client', 'unique-client-page'),         // Menu title
            'edit_posts',                                      // Capability
            'unique-client-page',                              // Menu slug
            array($this, 'render_admin_page'),                 // Callback function
            'dashicons-admin-page',                           // Icon
            30                                                 // Position
        );
        
        // Add Create Page submenu
        add_submenu_page(
            'unique-client-page',                                // Parent slug
            __('Create Product Page', 'unique-client-page'),     // Page title
            __('Create Product Page', 'unique-client-page'),     // Menu title
            'read',                                            // Capability - lower to read
            'ucp-create-page',                                 // Menu slug
            array($this, 'render_create_page_callback')          // Callback function
        );
        
        // Re-add the old URL mapping but with a different callback to prevent duplicate form
        add_submenu_page(
            null,                                                // Hidden from menu
            __('Create Product Page', 'unique-client-page'),     // Page title
            __('Create Product Page', 'unique-client-page'),     // Menu title
            'read',                                            // Capability
            'create-unique-client-page',                        // Menu slug
            array($this, 'redirect_to_new_page')                 // Callback function - use a different callback
        );
        
        // Add Edit Page submenu (Hidden)
        add_submenu_page(
            null,                                                // Not displayed in menu
            __('Edit Product Page', 'unique-client-page'),       // Page title
            __('Edit Product Page', 'unique-client-page'),       // Menu title
            'edit_posts',                                      // Capability
            'edit-unique-client-page',                        // Menu slug
            array($this, 'render_edit_page_callback')            // Callback function
        );
        
        // Add wishlist viewing submenu
        add_submenu_page(
            'unique-client-page',                                // Parent slug
            __('View Wishlist', 'unique-client-page'),         // Page title
            __('View Wishlist', 'unique-client-page'),         // Menu title
            'edit_posts',                                      // Capability
            'ucp-wishlist-manage',                             // Menu slug
            array($this, 'render_wishlist_manage_callback')      // Callback function
        );
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        // Get already created pages
        $custom_pages = get_posts(array(
            'post_type' => 'page',
            'meta_key' => '_wp_page_template',
            'meta_value' => 'unique-client-template.php',
            'posts_per_page' => -1
        ));
        
        echo '<div class="wrap">';
        echo '<h1>' . __('Unique Client Product Pages', 'unique-client-page') . '</h1>';
        echo '<p>' . __('Manage your custom product pages.', 'unique-client-page') . '</p>';
        
        // If updated successfully, show message
        if (isset($_GET['updated']) && $_GET['updated'] == 'true') {
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Page successfully updated!', 'unique-client-page') . '</p></div>';
        }
        
        // Create new page button
        echo '<a href="' . admin_url('admin.php?page=create-unique-client-page') . '" class="button button-primary">' . __('Create New Product Page', 'unique-client-page') . '</a>';
        
        // Display created page list
        echo '<h2>' . __('Created Product Pages', 'unique-client-page') . '</h2>';
        if (!empty($custom_pages)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>';
            echo '<th>' . __('Page Title', 'unique-client-page') . '</th>';
            echo '<th>' . __('Saleperson', 'unique-client-page') . '</th>';
            echo '<th>' . __('Publication Date', 'unique-client-page') . '</th>';
            echo '<th>' . __('Actions', 'unique-client-page') . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            
            foreach ($custom_pages as $page) {
                echo '<tr>';
                echo '<td>' . esc_html($page->post_title) . '</td>';
                $sale_name = get_post_meta($page->ID, '_ucp_sale_name', true);
                echo '<td>' . esc_html($sale_name) . '</td>';
                echo '<td>' . get_the_date('d/m/Y', $page->ID) . '</td>';
                echo '<td>';
                echo '<a href="' . get_permalink($page->ID) . '" target="_blank">' . __('View', 'unique-client-page') . '</a> | ';
                echo '<a href="' . admin_url('admin.php?page=edit-unique-client-page&page_id=' . $page->ID) . '">' . __('Edit', 'unique-client-page') . '</a> | ';
                $delete_nonce = wp_create_nonce('trash-post_' . $page->ID);
                echo '<a href="' . admin_url('post.php?post=' . $page->ID . '&action=trash&_wpnonce=' . $delete_nonce) . '" class="submitdelete">' . __('Delete', 'unique-client-page') . '</a>';
                echo '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        } else {
            echo '<p>' . __('No custom product pages yet. Please click the create button above to create your first page.', 'unique-client-page') . '</p>';
        }
        
        echo '</div>';
    }
    
    /**
     * Create page callback wrapper
     * Uses the new form component
     */
    public function render_create_page_callback() {
        // Get the main instance
        $main = UCP_Main::get_instance();
        
        // Get the form component and call its method
        $form = $main->get_form();
        if ($form) {
            $form->render_create_page();
        } else {
            echo '<div class="notice notice-error"><p>' . __('Form component not found.', 'unique-client-page') . '</p></div>';
        }
    }
    
    /**
     * Edit page callback wrapper
     * Uses the new form component
     */
    public function render_edit_page_callback() {
        // Get the main instance
        $main = UCP_Main::get_instance();
        
        // Get the form component and call its method
        $form = $main->get_form();
        if ($form) {
            $form->render_edit_page();
        } else {
            echo '<div class="notice notice-error"><p>' . __('Form component not found.', 'unique-client-page') . '</p></div>';
        }
    }
    
    /**
     * Wishlist manage callback wrapper
     * Still requires original class for now, but with safeguards
     */
    public function render_wishlist_manage_callback() {
        // For wishlist management, we still need to use the original class temporarily
        // But we'll add better error handling
        try {
            if (class_exists('UCP_Product_Page')) {
                $product_page = new UCP_Product_Page();
                if (method_exists($product_page, 'render_wishlist_manage_page')) {
                    $product_page->render_wishlist_manage_page();
                    return;
                }
            }
            
            // If we get here, something went wrong
            echo '<div class="notice notice-warning"><p>' . 
                __('Wishlist management is not available in this version. Please contact support.', 'unique-client-page') . 
                '</p></div>';
                
        } catch (Exception $e) {
            echo '<div class="notice notice-error"><p>' . 
                __('Error loading wishlist management: ', 'unique-client-page') . esc_html($e->getMessage()) . 
                '</p></div>';
        }
    }
    
    /**
     * Redirect from old URL to new page
     * This handles requests to the old 'create-unique-client-page' URL
     */
    public function redirect_to_new_page() {
        // Show a message that we're redirecting
        echo '<div class="wrap">';
        echo '<h1>' . __('Redirecting...', 'unique-client-page') . '</h1>';
        echo '<p>' . __('Please wait while we redirect you to the new page location...', 'unique-client-page') . '</p>';
        echo '</div>';
        
        // Use JavaScript for a smoother redirect experience
        echo '<script type="text/javascript">';
        echo 'window.location.href = "' . esc_url(admin_url('admin.php?page=ucp-create-page')) . '";';
        echo '</script>';
        
        // Also add a fallback link in case JavaScript is disabled
        echo '<p>' . __('If you are not redirected automatically, please follow ', 'unique-client-page');
        echo '<a href="' . esc_url(admin_url('admin.php?page=ucp-create-page')) . '">' . __('this link', 'unique-client-page') . '</a>.</p>';
    }
    
    /**
     * Get plugin settings
     * 
     * @param string $key Setting key
     * @param mixed $default Default value if setting not found
     * @return mixed
     */
    public function get_setting($key, $default = '') {
        $settings = get_option('ucp_settings', array());
        return isset($settings[$key]) ? $settings[$key] : $default;
    }
    
    /**
     * Save plugin settings
     * 
     * @param array $settings Settings to save
     * @return bool
     */
    public function save_settings($settings) {
        return update_option('ucp_settings', $settings);
    }
}
