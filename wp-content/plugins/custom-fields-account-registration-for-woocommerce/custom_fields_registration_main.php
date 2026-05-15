<?php
/**
 * Plugin Name: Custom Fields Account Registration For Woocommerce
 * Description: This plugin allows create Custom Fields Registration plugin.
 * Version: 1.3
 * Copyright: 2023
 * Text Domain: custom-fields-account-for-woocommerce-registration
 * Domain Path: /languages 
 */

if (!defined('ABSPATH')) {
    exit();
}

// Define Plugin File
if (!defined('CFAFWR_PLUGIN_FILE')) {
  define('CFAFWR_PLUGIN_FILE', __FILE__);
}

// Define Plugin Dir
if (!defined('CFAFWR_PLUGIN_DIR')) {
    define('CFAFWR_PLUGIN_DIR', plugins_url('', __FILE__));
}

// Define Plugin Base Name
if (!defined('CFAFWR_BASE_NAME')) {
define('CFAFWR_BASE_NAME', plugin_basename(CFAFWR_PLUGIN_FILE));
}

include_once('main/backend/cfafwr_backend.php');
include_once('main/backend/cfafwr_comman.php');
include_once('main/frontend/cfafwr_frontend.php');
include_once('main/resource/cfafwr-language.php');
include_once('main/resource/cfafwr-load-js-css.php');

function CFAFWRP_append_support_and_rating_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
  
  if ( strpos( $plugin_file_name, basename(__FILE__) ) ) {

    // You can still use `array_unshift()` to add links at the beginning.
    $links_array[] = '<a href="https://www.plugin999.com/support/">'. __('Support', 'custom-fields-account-for-woocommerce-registration') .'</a>';
    $links_array[] = '<a href="https://wordpress.org/support/plugin/custom-fields-account-registration-for-woocommerce/reviews/?filter=5">'. __('Rate the plugin ★★★★★', 'custom-fields-account-for-woocommerce-registration') .'</a>';
  }
 
  return $links_array;
}
add_filter( 'plugin_row_meta', 'CFAFWRP_append_support_and_rating_links', 10, 4 );

function CFAFWRP_plugin_activate() {
    add_rewrite_endpoint('oc-custom-fields', EP_ROOT | EP_PAGES);
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'CFAFWRP_plugin_activate');

function CFAFWRP_switch_theme_activate() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'CFAFWRP_switch_theme_activate');
