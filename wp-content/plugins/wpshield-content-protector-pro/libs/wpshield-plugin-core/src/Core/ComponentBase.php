<?php

namespace WPShield\Core\PluginCore\Core;

use WPShield\Core\PluginCore\Core\Contracts\Bootstrap;
use WPShield\Core\PluginCore\Core\Contracts\Localization;
use WPShield\Core\PluginCore\PluginSetup;

/**
 * Class ComponentBase
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core
 */
trait ComponentBase {

	/**
	 * Store instance of main module.
	 *
	 * @var PluginSetup|Bootstrap $plugin
	 */
	protected $plugin;

	/**
	 * ComponentBase constructor.
	 *
	 * @param PluginSetup|null $plugin
	 *
	 * @since 1.0.0
	 */
	public function __construct( PluginSetup $plugin = null ) {

		$this->plugin = $plugin;
	}

	/**
	 * Enqueue component assets files.
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	public function prepare(): bool {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 20 );
		add_action( 'wp_enqueue_scripts', [ $this, 'l10n_scripts' ], 20 );

		return true;
	}

	/**
	 * Enqueue module assets files.
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	public function enqueue_styles(): bool {

		if ( ! $this->plugin instanceof Bootstrap ) {

			return false;
		}

		$hook_name = sprintf( '%s/%s/enqueue-assets/assets', $this->plugin->get_company_name(), $this->plugin->product_id() );

		/**
		 * External developers can be added assets in this hook!
		 *
		 * @since 1.0.0
		 */
		$assets = apply_filters( $hook_name, $this->assets() );

		foreach ( $assets as $asset ) {

			if ( ! $this->validation_asset( $asset ) ) {

				continue;
			}

			if ( ! empty( $asset['type'] ) && 'script' === $asset['type'] ) {

				//Enqueue js
				wp_enqueue_script(
					$asset['handle'],
					$asset['src'],
					$asset['deps'] ?? [],
					$asset['version'] ?? $this->plugin->version(),
					$asset['in_footer'] ?? true
				);

				continue;
			}

			//Enqueue css.
			wp_enqueue_style(
				$asset['handle'],
				$asset['src'],
				$asset['deps'] ?? [],
				$asset['version'] ?? $this->plugin->version(),
				$asset['media'] ?? 'all'
			);
		}

		return true;
	}

	/**
	 * localization scripts object data.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function l10n_scripts(): bool {

		if ( ! $this instanceof Localization ) {

			return true;
		}

		global $wp_scripts;

		$l10n = $this->l10n();

		if ( ! $this->validation_l10n_data( $l10n ) ) {

			return true;
		}

		$is_registered = isset( $wp_scripts->registered[ $l10n['handle'] ] );
		$l10n_data     = $wp_scripts->get_data( $l10n['handle'], 'data' );

		if ( $is_registered && $l10n_data && false !== strpos( $l10n_data, $l10n['object'] ) ) {

			return false;
		}

		return wp_localize_script( $l10n['handle'], $l10n['object'], $l10n['l10n-data'] );
	}

	/**
	 * Enqueue module assets on the page.
	 *
	 * @since 1.0.0
	 * @return array of assets files with details.
	 */
	abstract public function assets(): array;

	/**
	 * Validate asset file details.
	 *
	 * @param array $asset_details
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	protected function validation_asset( array $asset_details ): bool {

		if ( ! isset( $asset_details['handle'], $asset_details['src'] ) ) {

			return false;
		}

		return true;
	}

	/**
	 * Validate localization object data.
	 *
	 * @param array $l10
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	protected function validation_l10n_data( array $l10 ): bool {

		if ( ! isset( $l10['handle'], $l10['object'], $l10['l10n-data'] ) ) {

			return false;
		}

		return true;
	}
}
