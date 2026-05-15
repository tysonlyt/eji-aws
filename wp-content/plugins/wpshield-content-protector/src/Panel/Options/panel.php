<?php

add_filter( 'better-framework/panel/add', 'wpshield_cp_panel_add', 16 );

if ( ! function_exists( 'wpshield_cp_panel_add' ) ) {
	/**
	 * Introduce panel to framework
	 *
	 * @hooked better-framework/panel/add
	 *
	 * @param array $panels
	 *
	 * @since  1.0.0
	 * @return array
	 */
	function wpshield_cp_panel_add( array $panels ): array {

		$plugin_setup = \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance();

		$panels[ $plugin_setup->product_id() ] = array(
			'id' => $plugin_setup->product_id(),
		);

		return $panels;
	}
}

add_filter( 'better-framework/panel/wpshield-content-protector/config', 'wpshield_cp_panel_config', 12 );

if ( ! function_exists( 'wpshield_cp_panel_config' ) ) {
	/**
	 * Init BF options
	 *
	 * @hooked better-framework/panel/content-protector/config
	 *
	 * @since  1.0.0
	 * @return array
	 */
	function wpshield_cp_panel_config(): array {

		$panel = array(
			'config'         => array(
				'name'                => __( 'Content Protector', 'wpshield-content-protector' ),
				'page_title'          => __( 'Content Protector', 'wpshield-content-protector' ),
				'menu_title'          => __( 'Content Protector', 'wpshield-content-protector' ),
				'slug'                => 'wpshield/wpshield-content-protector',
				'capability'          => 'manage_options',
				'parent'              => 'wpshield',
				'exclude_from_export' => false,
				'icon_url'            => NULL,
				'position'            => 83,
			),
			'panel-logo'     => \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->uri(
				'assets/images/wpshield-content-protector-logo.svg'
			),
			'panel-pre-name' => __( 'WP Shield', 'wpshield-content-protector' ),
			'panel-name'     => _x( 'Content Protector', 'Panel title', 'wpshield-content-protector' ),
			'panel-desc'     => '<p>' . __( 'Disable right click and prevent users from viewing your site code in order to prevent content theft and image theft', 'wpshield-content-protector' ) . '</p>',
		);

		return $panel;
	} // wpshield_cp_panel_config
}


add_filter( 'better-framework/panel/wpshield-content-protector/std', 'wpshield_cp_panel_std', 12 );

if ( ! function_exists( 'wpshield_cp_panel_std' ) ) {
	/**
	 * Options std fields
	 *
	 * @hooked better-framework/panel/content-protector/std
	 *
	 * @param array $fields
	 *
	 * @since  1.0.0
	 * @return array
	 */
	function wpshield_cp_panel_std( array $fields ): array {

		include \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->dir(
			'src/Panel/Options/panel-stds.php'
		);

		return $fields;
	}
}


add_filter( 'better-framework/panel/wpshield-content-protector/fields', 'wpshield_cp_panel_fields', 12 );

if ( ! function_exists( 'wpshield_cp_panel_fields' ) ) {
	/**
	 * Init BF options
	 *
	 * @hooked better-framework/panel/content-protector/fields
	 *
	 * @param array $fields
	 *
	 * @since  1.0.0
	 * @return array
	 */
	function wpshield_cp_panel_fields( array $fields ): array {

		include \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->dir(
			'src/Panel/Options/panel-fields.php'
		);

		return $fields;
	}
}

add_filter( 'wpshield/dashboard/menu/settings/plugins', 'wpshield_cp_plugin_config' );

if ( ! function_exists( 'wpshield_cp_plugin_config' ) ) {

	/**
	 * Register plugin config.
	 *
	 * @param array $plugins
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function wpshield_cp_plugin_config( array $plugins ): array {

		$plugin = WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance();

		$plugins[ $plugin->product_id() ] = [
			'is_premium'             => false,
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
			'author'                 => __( 'WP Shield', 'wpshield-content-protector' ),
			'banner'                 => $plugin->uri( 'assets/images/wpshield-content-protector-banner.svg' ),
			'thumbnail'              => $plugin->uri( 'assets/images/wpshield-content-protector-thumbnail.svg' ),
			'description'            => __( 'Disable right click and prevent users from viewing your site code in order to prevent content theft and image theft.', 'wpshield-content-protector' ),
		];

		return $plugins;
	}
}