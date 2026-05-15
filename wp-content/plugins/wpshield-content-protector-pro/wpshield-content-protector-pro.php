<?php
/*
 * Plugin Name: WP Shield Content Protector PRO
 * Plugin URI: https://getwpshield.com/plugins/content-protector/
 * Description: Pro version of WP Shield Content Protector
 * Version: 1.4.0
 * Author: WPShield
 * Author URI: http://getwpshield.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: wpshield-content-protector
 * Tested up to: 6.2.2
 * Requires PHP: 7.2
 * Requires at least: 5.2
 *
 * @package WPShieldContentProtectorPRO
*/

#Accessibilities Check!
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	die( 'Access Denied!' );
}

require __DIR__ . '/loader-composer.php';

Better_Composer_Loader::init( __DIR__ . '/vendor/' );

#Constants declarations.
define( 'WPSHIELD_CPP__FILE__', __FILE__ );
define( 'WPSHIELD_CPP_PLUGIN_BASE', plugin_basename( WPSHIELD_CPP__FILE__ ) );
define( 'WPSHIELD_CPP_PATH', plugin_dir_path( WPSHIELD_CPP__FILE__ ) );

if ( defined( 'WPSHIELD_CPP_TESTS' ) && WPSHIELD_CPP_TESTS ) {
	define( 'WPSHIELD_CPP_URL', 'file://' . WPSHIELD_CPP_PATH );
} else {
	define( 'WPSHIELD_CPP_URL', plugins_url( '/', WPSHIELD_CPP__FILE__ ) );
}

define( 'WPSHIELD_CPP_ASSETS_PATH', WPSHIELD_CPP_PATH . 'assets/' );

define( 'WPSHIELD_CPP_ASSETS_URL', WPSHIELD_CPP_URL . 'assets/' );

add_action( 'plugins_loaded', 'cpp_load_plugin' );

/**
 * Load Content Protector.
 *
 * Load gettext translate for Content Protector.
 *
 * @since 1.0.0
 *
 * @return void
 */
function cpp_load_plugin(): void {

	load_plugin_textdomain( 'wpshield-content-protector-pro' );
}

add_action( 'better-composer-loader/loaded', static function () {

	//Initialize Premium plugin.
	WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup::setup();

	//Initialize Addons.
	WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\FilterAndConditionsSetup::setup();
	WPShield\Plugin\ContentProtectorPro\Core\Addons\AudioAlert\AudioAlert::setup();
	WPShield\Plugin\ContentProtectorPro\Core\Addons\PopupMessage\PopupMessage::setup();
} );


$loader = require WPSHIELD_CPP_PATH . 'libs/better-framework/init.php';
$loader( [
	'uri'  => WPSHIELD_CPP_URL . 'libs/better-framework/',
	'path' => WPSHIELD_CPP_PATH . 'libs/better-framework/',
] );

$loader = require WPSHIELD_CPP_PATH . 'libs/better-framework-pro/init.php';
$loader( [
	'uri'  => WPSHIELD_CPP_URL . 'libs/better-framework-pro/',
	'path' => WPSHIELD_CPP_PATH . 'libs/better-framework-pro/',
] );

