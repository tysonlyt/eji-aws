<?php

namespace WPShield\Core\PluginCore;

use WPShield\Core\PluginCore\Dashboard\Dashboard;

/**
 * Class Apply
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore
 */
final class Apply {

	/**
	 * Store the instance of ManagerBase.
	 *
	 * @var ManagerBase
	 */
	protected $manager;

	/**
	 * Apply constructor.
	 *
	 * @param ManagerBase $manager_base
	 *
	 * @since 1.0.0
	 */
	public function __construct( ManagerBase $manager_base ) {

		$this->manager = $manager_base;
	}

	/**
	 * Apply all hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function hooks(): void {

		$this->register_oculus();

		// Enable needed sections
		add_filter( 'better-framework/sections', array( $this, 'setup_bf_features' ), 50 );

		//Loading oculus
		add_filter( 'better-framework/oculus/loader', [ $this, 'register_oculus' ], 90 );

		//Schedule
		add_filter( 'better-framework/oculus/update-schedule', [ $this, 'register_update_schedule' ] );

		//authentication
		add_filter( 'better-framework/product-pages/' . PluginSetup::PRODUCT_ITEM_ID . '/auth', [ $this, 'register_product_props' ] );
		add_filter( 'better-framework/oculus/' . PluginSetup::PRODUCT_ITEM_ID . '/auth', [ $this, 'register_product_props' ] );
	}

	/**
	 * Include all modules.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function include_libs(): void {

		#Load BF oculus.
		$bf_oculus_loader = $this->manager->get_libs_dir( '/better-framework/oculus/better-framework-oculus-loader.php' );

		if ( ! file_exists( $bf_oculus_loader ) ) {

			return;
		}

		include $bf_oculus_loader;

		#Load BF submodule.
		$bf_ini_file = $this->manager->get_libs_dir( '/better-framework/init.php' );

		if ( ! file_exists( $bf_ini_file ) ) {

			return;
		}

		include $bf_ini_file;
	}

	/**
	 * Running...
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function run(): void {

		$this->hooks();

		$this->include_libs();
	}

	/**
	 * Setups features of BetterFramework
	 *
	 * @param array $features
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function setup_bf_features( array $features ): array {

		$features['admin_panel']     = true;
		$features['product-pages']   = is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON );
		$features['product-updater'] = is_admin() || ( defined( 'DOING_CRON' ) && DOING_CRON );

		return $features;
	}

	/**
	 * Register oculus library
	 *
	 * @return array
	 */
	public function register_oculus():void {

		$loader = require $this->manager->get_libs_dir('better-framework/oculus/init.php');
		$loader( [
			'uri' => $this->manager->get_libs_uri('better-framework/oculus/'),
		] );
	}

	/**
	 * Registering update schedule time.
	 *
	 * @param array $items
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_update_schedule( array $items ): array {

		$items[] = PluginSetup::PRODUCT_ITEM_ID;

		return $items;
	}

	/**
	 * Registering product properties.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_product_props(): array {

		$item_id = PluginSetup::PRODUCT_ITEM_ID;
		$version = '1.0.0';
		//
		$option_name   = sprintf( '%s-register-info', $item_id );
		$data          = get_option( $option_name );
		$purchase_code = $data['purchase_code'] ?? '';
		//
		$product_type   = 'plugin';
		$product_folder = $item_id;
		$active_plugin  = true;
		//
		$urls = [
			'https://core.getwpshield.com/%group%/v1/%action%',
			//'https://core-cf.getwpshield.com/%group%/v1/%action%',
			'http://core.getwpshield.com/%group%/v1/%action%',
			//'http://core-cf.getwpshield.com/%group%/v1/%action%',
		];

		return compact( 'item_id', 'version', 'purchase_code', 'product_type', 'product_folder', 'active_plugin', 'urls' );
	}
}
