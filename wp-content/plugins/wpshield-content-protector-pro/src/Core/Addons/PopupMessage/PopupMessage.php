<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\PopupMessage;

use WPShield\Core\PluginCore\Core\Contracts\Bootstrap;
use WPShield\Plugin\ContentProtector\Components\Addons\PopupMessage\PopupMessage as FreeAddon;

/**
 * Class PluginSetup
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\PopupMessage
 */
class PopupMessage extends \WPShield\Core\PluginCore\PluginSetup implements Bootstrap {

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function file(): string {

		return __FILE__;
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

		return 'popup-message-addons';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_name(): string {

		return __( 'Popup Message Addons', 'wpshield-content-protector-pro' );
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

		return true;
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

		add_filter( 'wpshield/content-protector/addons/popup-message/templates', [ $this, 'register_alert_templates' ], 10, 3 );

		add_filter( 'wpshield/content-protector/addons/popup-message/assets', [ $this, 'register_assets' ] );
	}

	/**
	 * Registering premium templates for popup message addons.
	 *
	 * @param array     $templates
	 * @param FreeAddon $popup_message
	 * @param array     $protectors
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_alert_templates( array $templates, FreeAddon $popup_message, array $protectors ): array {

		$pattern = sprintf( '%s/templates/*.php', __DIR__ );

		$glob = glob( $pattern );

		foreach ( $protectors as $protector ) {

			foreach ( $glob as $item ) {

				$id          = str_replace( '.php', '', basename( $item ) );
				$template_id = sprintf( '%s/%s', $protector, $id );

				$templates[ $template_id ] = $popup_message->get_template( $id, $protector, $item );
			}
		}

		return $templates;
	}

	/**
	 * Enqueue css and js scripts.
	 *
	 * @param array $assets
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_assets( array $assets ): array {

		$assets[] = [
			'deps'    => [],
			'format'  => 'style',
			'version' => $this->version(),
			'handle'  => sprintf( '%s-pro-css', $this->product_id() ),
			'src'     => self::url( '/css/alert-popup-message.css' ),
		];

		return $assets;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function dir( string $directory = '' ): string {

		$base_path = plugin_dir_path( __FILE__ );

		if ( ! empty( $directory ) ) {

			return sprintf(
				'%s%s',
				$base_path,
				$directory
			);
		}

		return $base_path;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function uri( string $directory = '' ): string {

		$base_url = plugins_url( '/', __FILE__ );

		if ( ! empty( $directory ) ) {

			return sprintf(
				'%s%s',
				$base_url,
				$directory
			);
		}

		return $base_url;
	}
}
