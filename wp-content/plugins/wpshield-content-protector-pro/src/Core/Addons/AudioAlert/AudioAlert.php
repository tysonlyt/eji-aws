<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\AudioAlert;

use WPShield\Core\PluginCore\Core\Contracts\Bootstrap;
use WPShield\Plugin\ContentProtector\ContentProtectorSetup;

#Constants declarations.
define( 'WPSHIELD_CPP_CORE_AA__FILE__', __FILE__ );
define( 'WPSHIELD_CPP_CORE_AA_PLUGIN_BASE', plugin_basename( WPSHIELD_CPP_CORE_AA__FILE__ ) );
define( 'WPSHIELD_CPP_CORE_AA_PATH', plugin_dir_path( WPSHIELD_CPP_CORE_AA__FILE__ ) );
define( 'WPSHIELD_CPP_CORE_AA_URL', plugins_url( '/', WPSHIELD_CPP_CORE_AA__FILE__ ) );

/**
 * Class PluginSetup
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\AudioAlert
 */
class AudioAlert extends \WPShield\Core\PluginCore\PluginSetup implements Bootstrap {

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function file(): string {

		return WPSHIELD_CPP_CORE_AA__FILE__;
	}

	/**
	 * Retrieve plugin released version number.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function version(): string {

		return '1.0.0';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_id(): string {

		return 'audio-alert';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_name(): string {

		return __( 'Audio Alert', 'wpshield-content-protector-pro' );
	}

	/**
	 * Initializing all components.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init_components(): bool {

		add_filter( 'wpshield/content-protector/extensions', [ $this, 'add_extension' ] );

		add_action( 'wpshield/content-protector/extensions/init', [ $this, 'boot' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'l10n' ], 30 );

		return true;
	}

	public function l10n(): void {

		if ( ! defined( 'WPSHIELD_CPP_CORE_AA__FILE__' ) || ! class_exists( ContentProtectorSetup::class ) ) {

			return;
		}

		$handle = sprintf( '%s-admin-js', ContentProtectorSetup::instance()->product_id() );

		wp_localize_script( $handle, 'AudioAlertL10n',
			[
				'assets-url' => sprintf( '%sassets/', WPSHIELD_CPP_CORE_AA_URL ),
			]
		);
	}

	public function enqueue_assets(): void {

		$handle = sprintf( '%s-js', $this->product_id() );

		wp_enqueue_script(
			$handle,
			sprintf( '%sassets/js/%s.js', WPSHIELD_CPP_CORE_AA_URL, $this->product_id() ),
			[],
			$this->version(),
			true
		);

		wp_localize_script( $handle, 'AudioAlertL10n',
			[
				'assets-url' => sprintf( '%sassets/', WPSHIELD_CPP_CORE_AA_URL ),
			]
		);
	}

	/**
	 * Add current plugin to extensions list.
	 *
	 * @hooked "wpshield/content-protector/extensions"
	 *
	 * @param array $extensions
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function add_extension( array $extensions ): array {

		return array_merge( $extensions, [ $this->product_id() ] );
	}

	/**
	 * Fire up plugin functionalities.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function boot(): void {

		//TODO: implements
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function dir( string $directory = '' ): string {

		if ( ! empty( $directory ) ) {

			return sprintf(
				'%s%s',
				WPSHIELD_CPP_CORE_AA_PATH,
				$directory
			);
		}

		return WPSHIELD_CPP_CORE_AA_PATH;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function uri( string $directory = '' ): string {

		if ( ! empty( $directory ) ) {

			return sprintf(
				'%s%s',
				WPSHIELD_CPP_CORE_AA_URL,
				$directory
			);
		}

		return WPSHIELD_CPP_CORE_AA_URL;
	}
}
