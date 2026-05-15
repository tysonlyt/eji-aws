<?php


namespace WPShield\Core\PluginCore\Dashboard\Menus\Settings;

use WPShield\Core\PluginCore\PluginSetup;

/**
 * Class BundlePlugins
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Dashboard\Menus\Settings
 */
class BundlePlugins extends \BF_Product_Plugin_List_Table {

	/**
	 * Store the plugins status as stdClass object.
	 *
	 * @var \stdClass
	 */
	protected $plugins_status;

	/**
	 * BundlePlugins constructor.
	 *
	 * @param array $args
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $args = [] ) {

		parent::__construct( $args );

		$this->plugins_status = get_option( 'bs-product-plugins-status' );
	}

	/**
	 * Store the allows
	 *
	 * @var array
	 */
	protected $allows = [
		'related-plugins' => [],
	];

	/**
	 * Retrieve the plugins list.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_plugins_list(): array {

		$remote_plugins = bf_get_plugins_config( PluginSetup::PRODUCT_ITEM_ID );
		$plugins        = apply_filters( 'wpshield/dashboard/menu/settings/plugins', [] );
		$plugins        = array_merge( $remote_plugins ?? [], $plugins );

		if ( empty( $plugins ) ) {

			return [];
		}

		//Get active plugins
		$actives = array_map(
			[ $this, 'update_plugins_status' ],
			array_filter(
				array_map(
					[ $this, 'match_with_current_package' ],
					array_filter( $plugins, array( $this, 'is_plugin_active' ) )
				),
				[ $this, 'is_plugin_free' ]
			)
		);

		//Get related plugins (coming soon or free)
		$free            = array_filter( $plugins, array( $this, 'is_plugin_free' ) );
		$premium         = array_filter( $plugins, array( $this, 'is_plugin_premium' ) );
		$coming_soon     = array_filter( $plugins, array( $this, 'is_plugin_coming_soon' ) );
		$related_plugins = array_merge( $premium, $free, $coming_soon );

		$is_active_related = array_filter( $related_plugins, [ $this, 'is_plugin_active' ] );

		$this->allows['related-plugins'] = array_diff( array_keys( $related_plugins ),
			array_merge(
				array_keys( $actives ),
				array_keys( $is_active_related )
			)
		);

		$related_plugins = array_filter( array_map( [ $this, 'in_relation' ], $related_plugins ) );

		foreach ( array_keys( $related_plugins ) as $plugin_slug ) {

			$_plugin_slug = substr( $plugin_slug, 0, - 4 );

			if ( ! array_key_exists( $_plugin_slug, $free ) ) {

				continue;
			}

			//Unset plugins have Free-PRO type from related plugins list!
			unset( $related_plugins[ $plugin_slug ] );
		}

		return compact( 'actives', 'related_plugins', 'coming_soon' );
	}

	/**
	 * @param array $plugin
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function update_plugins_status( array $plugin ): array {

		if ( empty( $this->plugins_status ) || ! isset( $this->plugins_status->remote_plugins ) || empty( $this->plugins_status->remote_plugins ) ) {

			return $plugin;
		}

		$plugin_file     = sprintf( '%1$s/%1$s.php', $plugin['slug'] );
		$plugin_pro_file = sprintf( '%1$s-pro/%1$s-pro.php', $plugin['slug'] );

		if ( isset( $this->plugins_status->remote_plugins[ $plugin_file ] ) ) {

			$plugin['new_version'] = $this->plugins_status->remote_plugins[ $plugin_file ]['new_version'];

		} elseif ( isset( $this->plugins_status->remote_plugins[ $plugin_pro_file ] ) ) {

			$plugin['new_version'] = $this->plugins_status->remote_plugins[ $plugin_pro_file ]['new_version'];
		}

		return $plugin;
	}

	/**
	 * Setup update new_version param!
	 *
	 * @param array $plugin
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function match_with_current_package( array $plugin ): array {

		$plugins = get_plugins( '/' . $plugin['slug'] );

		if ( empty( $plugins ) ) {

			return $plugin;
		}

		$current_package = array_shift( $plugins );

		if ( ! isset( $current_package['Version'] ) ) {

			return $plugin;
		}

		$plugin['version'] = $current_package['Version'];

		return $plugin;
	}

	/**
	 * Is current plugin passed Free?
	 *
	 * @param array $plugin
	 *
	 * @since 1.0.0
	 * @return bool true on success, false on failure!
	 */
	protected function is_plugin_free( array $plugin ): bool {

		return ! isset( $plugin['is_premium'] ) || ! $plugin['is_premium'];
	}

	/**
	 * Is current plugin passed Coming Soon...?
	 *
	 * @param array $plugin
	 *
	 * @since 1.0.0
	 * @return bool true on success, false on failure!
	 */
	protected function is_plugin_coming_soon( array $plugin ): bool {

		return isset( $plugin['state'] ) && 'coming-soon' === $plugin['state'];
	}

	/**
	 * Is plugin in relation list?
	 *
	 * @param array $plugin
	 *
	 * @since 1.0.0
	 * @return array|null
	 */
	protected function in_relation( array $plugin ): ?array {

		if ( ! isset( $plugin['slug'] ) || empty( $this->allows['related-plugins'] ) ) {

			return null;
		}

		return in_array( $plugin['slug'], $this->allows['related-plugins'], true ) ? $plugin : null;
	}
}
