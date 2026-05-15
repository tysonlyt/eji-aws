<?php
/**
 * Plugin Name: WP Shield Content Protector
 * Plugin URI: https://getwpshield.com/plugins/content-protector/
 * Description: Prevent content copiers from copying your website content. It protects all types of content, including text, images, videos, and even source code.
 * Version: 1.4.0
 * Author: WP Shield
 * Author URI: http://getwpshield.com
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: wpshield-content-protector
 * Tested up to: 6.2.2
 * Requires PHP: 7.2
 * Requires at least: 5.2
 *
 * @package WPShieldContentProtector
 */

#Accessibilities Check!
if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	die( 'Access Denied!' );
}

require __DIR__ . '/loader-composer.php';

Better_Composer_Loader::init( __DIR__ . '/vendor/' );

#Constants declarations.
define( 'WPSHIELD_CP__FILE__', __FILE__ );
define( 'WPSHIELD_CP_PLUGIN_BASE', plugin_basename( WPSHIELD_CP__FILE__ ) );
define( 'WPSHIELD_CP_PATH', plugin_dir_path( WPSHIELD_CP__FILE__ ) );

if ( defined( 'WPSHIELD_CP_TESTS' ) && WPSHIELD_CP_TESTS ) {
	define( 'WPSHIELD_CP_URL', 'file://' . WPSHIELD_CP_PATH );
} else {
	define( 'WPSHIELD_CP_URL', plugins_url( '/', WPSHIELD_CP__FILE__ ) );
}

define( 'WPSHIELD_CP_ASSETS_PATH', WPSHIELD_CP_PATH . 'assets/' );
define( 'WPSHIELD_CP_ASSETS_URL', WPSHIELD_CP_URL . 'assets/' );

add_action( 'better-composer-loader/loaded', 'wpshield_cp_load_plugin_text_domain' );

/**
 * Load Content Protector text domain.
 *
 * Load gettext translate for Content Protector text domain.
 *
 * @since 1.0.0
 *
 * @return void
 */
function wpshield_cp_load_plugin_text_domain() {

	load_plugin_textdomain( 'wpshield-content-protector' );

	\WPShield\Plugin\ContentProtector\ContentProtectorSetup::setup();
}

$loader = require WPSHIELD_CP_PATH . 'libs/better-framework/init.php';
$loader( [
	'uri'  => WPSHIELD_CP_URL . 'libs/better-framework/',
	'path' => WPSHIELD_CP_PATH . 'libs/better-framework/',
] );

