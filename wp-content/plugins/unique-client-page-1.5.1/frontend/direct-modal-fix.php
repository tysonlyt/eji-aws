<?php
/**
 * Direct Fix for Modal Close Button Issues
 * 
 * This file loads the JavaScript to directly fix modal close button functionality
 */

// Security check
if (!defined('ABSPATH')) {
    exit; // Direct access forbidden
}

/**
 * Load direct fix script
 */
function ucp_load_direct_modal_fix() {
    // Get plugin URL
    $plugin_url = plugin_dir_url(dirname(__FILE__));
    
    // Register and load script
    wp_enqueue_script(
        'ucp-direct-modal-fix',
        $plugin_url . 'assets/js/direct-modal-fix.js',
        array('jquery'),
        filemtime(plugin_dir_path(dirname(__FILE__)) . 'assets/js/direct-modal-fix.js'),
        true
    );
}

// Add to WordPress hooks
add_action('wp_enqueue_scripts', 'ucp_load_direct_modal_fix', 999);
