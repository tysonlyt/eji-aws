<?php
/**
 * Email Settings Management Functions
 */

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Load required files first to ensure functions are available
// Load email settings file
require_once UCP_PLUGIN_DIR . 'admin/pages/email-settings.php';
// Load mailer class
require_once UCP_PLUGIN_DIR . 'includes/class-ucp-mailer.php';

/**
 * Add email settings menu item
 */
function ucp_add_email_settings_menu() {
    add_submenu_page(
        'unique-client-page',                      // Parent menu slug
        __('Email Settings', 'unique-client-page'),     // Page title
        __('Email Settings', 'unique-client-page'),     // Menu title
        'manage_options',                          // Capability
        'ucp-email-settings',                      // Menu slug
        'ucp_render_email_settings_page'           // Callback function
    );
}
add_action('admin_menu', 'ucp_add_email_settings_menu', 20); // Priority 20 to ensure it's added after the main menu

/**
 * Initialize email sending functionality
 */
function ucp_init_mailer() {
    // Get email settings
    $settings = get_option('ucp_email_settings');
    
    // Initialize Mailer class only when SMTP is enabled
    if (isset($settings['smtp_enabled']) && $settings['smtp_enabled']) {
        new UCP_Mailer();
    }
}
add_action('init', 'ucp_init_mailer');
