<?php
/**
 * Debug file for AJAX requests
 */

// Enable error reporting for AJAX debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log function
function ucp_debug_log($message, $data = null) {
    $log_file = dirname(__FILE__) . '/debug.log';
    $timestamp = date('[Y-m-d H:i:s]');
    
    if (is_null($data)) {
        file_put_contents($log_file, "$timestamp $message\n", FILE_APPEND);
    } else {
        $data_str = print_r($data, true);
        file_put_contents($log_file, "$timestamp $message\n$data_str\n\n", FILE_APPEND);
    }
}

// This hook will run on all AJAX actions
add_action('wp_ajax_nopriv_ucp_get_wishlist_version', 'ucp_debug_ajax_request');
add_action('wp_ajax_ucp_get_wishlist_version', 'ucp_debug_ajax_request');

function ucp_debug_ajax_request() {
    ucp_debug_log('AJAX request received: ' . $_REQUEST['action']);
    ucp_debug_log('AJAX POST data', $_POST);
}
