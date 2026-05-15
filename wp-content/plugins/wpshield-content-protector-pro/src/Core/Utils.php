<?php


namespace WPShield\Plugin\ContentProtectorPro\Core;

use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core
 */
class Utils {
	/**
	 * Make secure file for any protectors to display popup template!
	 *
	 * @param string $protector
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function make_disable_js_secure_file( string $protector = 'javascript' ): void {

		if ( ! empty( $_POST ) && ! wp_verify_nonce( $_POST['nonce'] ?? '', 'popup-template' ) ) {

			return;
		}

		$upload_dir = wp_upload_dir();

		$root_directory = sprintf( '%s/%s', $upload_dir['basedir'] ?? ABSPATH, ContentProtectorSetup::instance()->product_id() );

		if ( ! class_exists( \WPShield\Plugin\ContentProtector\ContentProtectorSetup::class ) ) {

			return;
		}

		$alert_content = '';
		$bf_fs         = bf_file_system_instance();
		$free_plugin   = \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance();

		$icon           = $_POST['icon'] ?? bf_get_option( $protector . '/alert-popup/icon', $free_plugin->product_id() );
		$content        = $_POST['text'] ?? bf_get_option( $protector . '/alert-popup/text', $free_plugin->product_id() );
		$title          = $_POST['title'] ?? bf_get_option( $protector . '/alert-popup/title', $free_plugin->product_id() );
		$color          = $_POST['color'] ?? bf_get_option( $protector . '/alert-popup/color', $free_plugin->product_id() );
		$alert_template = $_POST['template'] ?? bf_get_option( $protector . '/alert-popup/template', $free_plugin->product_id() );

		$front_controller = sprintf( '%s/%s', $root_directory, $protector );

		$alert_filename       = sprintf(
			'%s/src/Components/Addons/PopupMessage/templates/%s.php',
			WPSHIELD_CP_PATH,
			$alert_template
		);
		$alert__css_filename  = sprintf( '%ssrc/Components/Addons/PopupMessage/css/popup-message.css', WPSHIELD_CP_PATH );
		$bf_icon_css_filename = sprintf( '%slibs/better-framework/assets/css/bf-icon.css', WPSHIELD_CP_PATH );

		if ( file_exists( $alert__css_filename ) ) {
			$alert_content = sprintf(
				'<html lang="%s"><body><style>%s%s</style><div class="cp-popup-message-wrap">{{BODY}}</div></body></html>',
				get_locale(),
				$bf_fs->get_contents( $bf_icon_css_filename ),
				$bf_fs->get_contents( $alert__css_filename )
			);
		}

		//Render popup template on buffer.
		ob_start();

		include $alert_filename;

		//End.
		$alert_content = str_replace( '{{BODY}}', ob_get_clean(), $alert_content );

		//if blank file not exits or alert template content is not equals with current blank page content!
		if ( $bf_fs->exists( $front_controller ) && $alert_content === $bf_fs->get_contents( $front_controller ) ) {

			return;
		}

		if ( ! file_exists( $front_controller ) ) {

			//handle recursive path
			mkdir( $front_controller, 0777, true );
		}

		$bf_fs->put_contents( $front_controller . '/index.php', $alert_content );
	}
}
