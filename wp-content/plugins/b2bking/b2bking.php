<?php
/*
/**
 * Plugin Name:       B2BKing Pro
 * Plugin URI:        woocommerce-b2b-plugin.com
 * Description:       B2BKing is the complete solution for turning WooCommerce into an enterprise-level B2B e-commerce platform.
 * Version:           5.5.00
 * Author:            WebWizards
 * Author URI:        webwizards.dev
 * Text Domain:       b2bking
 * Domain Path:       /languages
 * WC requires at least: 5.0.0
 * WC tested up to: 10.5.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'B2BKING_VERSION' ) ) {
	define(	'B2BKING_VERSION', 'v5.5.00');
}
if ( ! defined( 'B2BKING_FILE_RELEASE' ) ) { // dev or production
	define(	'B2BKING_FILE_RELEASE', 'PROD');
}


if ( ! defined( 'B2BKING_DIR' ) ) {
	define( 'B2BKING_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'B2BKINGMAIN_DIR' ) ) {
	define( 'B2BKINGMAIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'B2BKING_LANG' ) ){
	define( 'B2BKING_LANG', basename( dirname( __FILE__ ) ) . '/languages' );
}

// Autoupdates
// checkPeriod set to 12, checks for updates every 12 hrs
require 'includes/assets/lib/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Autoupdates
$license = get_option('b2bking_license_key_setting', '');
$email = get_option('b2bking_license_email_setting', '');
$info = parse_url(get_site_url());
$host = $info['host'];
$host_names = explode(".", $host);

if (isset($host_names[count($host_names)-2])){ // e.g. if not on localhost, xampp etc

	$bottom_host_name = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];

	if (strlen($host_names[count($host_names)-2]) <= 3){    // likely .com.au, .co.uk, .org.uk etc
		if (isset($host_names[count($host_names)-3])){
		    $bottom_host_name_new = $host_names[count($host_names)-3] . "." . $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];
		    // new, overwrite legacy, just use new one
		    $bottom_host_name = $bottom_host_name_new;
		}
	}


	$activation = get_option('pluginactivation_'.$email.'_'.$license.'_'.$bottom_host_name);

	if ($activation == 'active'){
	    $query = '?email='.$email.'&license='.$license.'&requesttype=autoupdates&plugin=BK&website='.$bottom_host_name;

	    // Use fallback if primary previously failed (re-check primary every x hours)
	    $base = get_transient('b2bking_use_fallback_updates') ? 'https://apifallback.wpbay.co' : 'https://kingsplugins.com';

	    $myUpdateChecker = PucFactory::buildUpdateChecker(
	        $base . '/wp-json/licensing/v1/request' . $query,
	        __FILE__,
	        'b2bking'
	    );

	    // If check fails, switch to fallback for next attempt
	    add_filter('puc_request_info_result-b2bking', function($plugin_info) {
	        if ($plugin_info === null) {
	            set_transient('b2bking_use_fallback_updates', true, 24 * HOUR_IN_SECONDS);
	        } else {
	            delete_transient('b2bking_use_fallback_updates');
	        }
	        return $plugin_info;
	    });
	}
}

// Begins execution of the plugin.

if (!function_exists('b2bking_run')){
	function b2bking_run() {

		require_once ( B2BKING_DIR . 'includes/class-b2bking-global-helper.php' );

		if (!function_exists('b2bking')){
			function b2bking() {
			    return B2bking_Globalhelper::init();
			}
		}

		if (!function_exists('b2bking_activate')){
			function b2bking_activate() {
				require_once B2BKING_DIR . 'includes/class-b2bking-activator.php';
				B2bking_Activator::activate();
			}
		}

		register_activation_hook( __FILE__, 'b2bking_activate' );

		// deactivate private store plugin if active
		register_activation_hook(__FILE__, function() {
		    // Check multiple possible paths for the helper plugin
		    $possible_paths = [
		        'b2bking-private-store-for-woocommerce/b2bking.php',  
		        'private-store-for-woocommerce/b2bking.php',                
		    ];
		    
		    foreach ($possible_paths as $plugin_path) {
		        if (is_plugin_active($plugin_path)) {
		            deactivate_plugins($plugin_path);
		            set_transient('b2bking_helper_deactivated', true, 30);
		            break; // Stop after deactivating the first match
		        }
		    }
		});

		require B2BKING_DIR . 'includes/class-b2bking.php';

		// Load plugin language
		add_action( 'plugins_loaded', 'b2bking_load_language');
		function b2bking_load_language() {
			load_plugin_textdomain( 'b2bking', FALSE, basename( dirname( __FILE__ ) ) . '/languages');
		}

		global $b2bking_plugin;
		$b2bking_plugin = new B2bking();
	}

	b2bking_run();
} else {
	
    register_activation_hook( __FILE__, 'b2bking_activation_error' );
    function b2bking_activation_error() {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
        wp_die( 'The plugin could not be activated because another version of B2BKing Pro, version '.B2BKING_VERSION.' is already active. <strong>Please deactivate version '.B2BKING_VERSION.' before activating this one.</strong>');
    }

}

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
	}
	
} );