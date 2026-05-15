<?php

namespace WPShield\Plugin\ContentProtectorPro\Panel;

use BetterStudio\Core\Module\ModuleHandler;
use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;

/**
 * Class PanelComponent
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\Components\Panel
 */
class PanelOption extends ModuleHandler {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init(): bool {

		add_filter( 'wpshield/dashboard/menu/settings/plugins', [ $this, 'plugin_config' ] );

		return true;
	}

	/**
	 * Register plugin config.
	 *
	 * @param array $plugins
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function plugin_config( array $plugins ): array {

		$plugin = ContentProtectorSetup::instance();

		$plugins[ $plugin->product_id() ] = [
			'is_premium'             => true,
			'have_access'            => true,
			'reload_after_install'   => true,
			'reload_after_uninstall' => true,
			'required'               => false,
			'type'                   => 'global',
			'color'                  => '#d60000',
			'version'                => $plugin->version(),
			'slug'                   => $plugin->product_id(),
			'name'                   => $plugin->product_name(),
			'author-uri'             => 'https://getwpshield.com/',
			'state'                  => 'released',
			'author'                 => __( 'WP Shield', 'wpshield-content-protector-pro' ),
			'banner'                 => $plugin->uri( 'assets/images/wpshield-content-protector-banner.svg' ),
			'thumbnail'              => $plugin->uri( 'assets/images/wpshield-content-protector-thumbnail.svg' ),
			'description'            => __( 'Disable right click and prevent users from viewing your site code in order to prevent content theft and image theft.', 'wpshield-content-protector-pro' ),
		];

		return $plugins;
	}
}
