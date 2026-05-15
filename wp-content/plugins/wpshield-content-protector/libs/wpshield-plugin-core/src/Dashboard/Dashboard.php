<?php

namespace WPShield\Core\PluginCore\Dashboard;

use BetterStudio\Core\Module\Singleton;
use WPShield\Core\PluginCore\Dashboard\Menus\Upgrade\UpgradeMenu;
use WPShield\Core\PluginCore\ManagerBase;
use WPShield\Core\PluginCore\PluginSetup;

/**
 * Class Dashboard
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Dashboard
 */
class Dashboard {

	/**
	 * Implementation Singleton Design Pattern
	 *
	 * @since 1.0.0
	 */
	use Singleton;

	/**
	 * Store instance of PluginManager.
	 *
	 * @var ManagerBase
	 */
	protected $manager;

	/**
	 * Store the array of arguments.
	 *
	 * @var array|string[]
	 *
	 * FIXME: menu icon & notice icon.
	 */
	protected $args = [
		'notice-icon'   => '',
		'menu_icon'     => '',
		'menu_position' => '58.090',
		'product_type'  => 'plugin',
	];

	/**
	 * Dashboard constructor.
	 *
	 * @param ManagerBase $manager
	 * @param array       $args
	 *
	 * @since 1.0.0
	 */
	public function __construct( ManagerBase $manager, array $args = [] ) {

		// Set arguments
		$this->args = $args;

		//Set manager
		$this->manager = $manager;

		$this->apply_hooks();
	}

	/**
	 * Apply dashboard hooks.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected function apply_hooks(): void {

		//Plugin actions.
		add_action( 'wp_ajax_install_plugin', array( $this, 'install_plugin' ) );
		add_action( 'wp_ajax_update_plugin', array( $this, 'install_plugin' ) );

		//Parent Page (Dashboard).
		add_filter( 'better-framework/product-pages/wpshield-settings-item/handler', [ $this, 'setup_dashboard_handler' ] );
		add_filter( 'better-framework/product-pages/page/wpshield-settings/config', [ $this, 'dashboard_config' ] );

		//License Page.
		add_filter( 'better-framework/product-pages/wpshield-license-item/handler', [ $this, 'setup_license_handler' ] );
		add_filter( 'better-framework/product-pages/page/wpshield-license/config', [ $this, 'license_config' ] );

		//Upgrade Page.
		add_filter( 'better-framework/product-pages/wpshield-upgrade-item/handler', [ $this, 'setup_upgrade_handler' ] );
		add_filter( 'better-framework/product-pages/page/wpshield-upgrade/config', [ $this, 'upgrade_config' ] );

		// Product Pages Config.
		add_filter( 'better-framework/product-pages/config', [ $this, 'config_product_pages' ] );

		add_filter( 'better-framework/product-pages/modules/list', [ $this, 'register_modules' ] );

		// Callback for enqueue BF admin pages style
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		//After BF Setup paradigm.
		add_action( 'better-framework/after_setup', [ $this, 'bf_init' ] );
	}

	/**
	 * Handle BetterFramework after setup.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function bf_init(): void {

		$have_license = apply_filters( 'wpshield-plugin-core/dashboard/pages/have-license', false );

		//when product license key is registered!
		if ( $have_license && function_exists( 'bf_is_product_registered' ) && \bf_is_product_registered( PluginSetup::PRODUCT_ITEM_ID ) ) {

			return;
		}

		if ( is_admin() ) {

			///
			/// TODO: remove this line because must be create upgrade internal page!
			///
			// Upgrade to PRO Menu (Direct Link)
			new UpgradeMenu( $this->upgrade_config() );
		}
	}

	/**
	 * Install plugin by ajax request
	 *
	 * @hooked 'wp_ajax_plugin-core-install'
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function install_plugin(): void {

		if ( ! isset( $_POST['nonce'] ) ) {

			wp_send_json_error(
				[
					'message' => __( '403 Forbidden! Unauthorized Token.', 'wpshield' ),
				]
			);
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'dashboard-nonce' ) ) {

			wp_send_json_error(
				[
					'message' => __( '403 Forbidden! Unauthorized Token.', 'wpshield' ),
				]
			);
		}

		ob_start();

		try {

			if ( ! isset( $_POST['slug'] ) ) {

				throw new \Exception( __( 'Missing slug field!', 'wpshield' ) );
			}

			$plugin = sanitize_text_field( wp_unslash( $_POST['slug'] ) );

			//Backward Compatible with BF old code base!
			update_option( sprintf( '%s-item-id', $plugin ), PluginSetup::PRODUCT_ITEM_ID );

			$upgrade = isset( $_POST['action'] ) && 'update_plugin' === sanitize_text_field( wp_unslash( $_POST['action'] ) );

			$handler = new \BF_Product_Plugin_Installer();
			$handler->download_plugins( [ $plugin ], $upgrade, PluginSetup::PRODUCT_ITEM_ID );

			$this->upgrader_status = false;

		} catch ( \Exception $exception ) {

			wp_send_json_error(
				[
					'file'    => $exception->getFile(),
					'line'    => $exception->getLine(),
					'code'    => $exception->getCode(),
					'message' => $exception->getMessage(),
				]
			);
		}

		$data = ob_get_clean();

		if ( false !== strpos( $data, 'Installation package not available.' ) ) {

			wp_send_json_error(
				[
					'message' => __( 'Installation package not available.', 'wpshield' ),
				]
			);
		}

		wp_send_json_success( $data );
	}

	/**
	 * Register handler for product dashboard page.
	 *
	 * @since 1.0.0
	 * @return Menus\Settings\Settings
	 */
	public function setup_dashboard_handler(): Menus\Settings\Settings {

		return new Menus\Settings\Settings( $this->dashboard_config() );
	}

	/**
	 * Register and retrieve dashboard config.
	 *
	 * @param array $config
	 *
	 * @sicne 1.0.0
	 * @return array
	 */
	public function dashboard_config( array $config = [] ): array {

		return [
			'type'           => 'wpshield-settings',
			'panel-pre-name' => __( 'Make WordPress Secure', 'wpshield' ),
			'panel-name'     => __( 'WP Shield', 'wpshield' ),
			'panel-desc'     => __( 'Ultimate WordPress Security Plugins and Tutorials', 'wpshield' ),
			'panel-logo'     => $this->manager->url( 'Dashboard/assets/images/wpshield-thumbnail.svg' ),
			'name'           => __( 'Settings', 'wpshield' ),
			'menu_title'     => __( 'Settings', 'wpshield' ),
			'menu_icon'      => bf_get_icon_tag( 'bsai-publisher', '', [ 'base64' => true ] ),
			'id'             => 'settings',
			'menu_position'  => '58.091',
			'dir-uri'        => $this->manager->url( 'Dashboard/Menus/Settings' ),
			'class'          => 'hide-notices',
			'version'        => $this->args['version'] ?? '1.0.0',
			'item_id'        => PluginSetup::PRODUCT_ITEM_ID,
		];
	}

	/**
	 * Register handler for product license page.
	 *
	 * @since 1.0.0
	 * @return Menus\License\Manager
	 */
	public function setup_license_handler(): Menus\License\Manager {

		return new Menus\License\Manager( $this->license_config() );
	}

	/**
	 * Retrieve license page config.
	 *
	 * @param array $config
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function license_config( array $config = [] ): array {

		return [
			'panel-logo'      => $this->manager->url( 'Dashboard/assets/images/wpshield-thumbnail.svg' ),
			'panel-pre-name'  => _x( 'WP Shield', 'Panel title', 'wpshield' ),
			'menu_title'      => __( 'Your License', 'wpshield' ),
			'panel-name'      => __( 'Your License', 'wpshield' ),
			'panel-sec-title' => __( 'WP Shield License', 'wpshield' ),
			'panel-desc'      => '<p>' . __( 'License manager to unlock all premium features', 'wpshield' ) . '</p>',
			'name'            => __( 'Your License', 'wpshield' ),
			'id'              => 'wpshield-license',
			'type'            => 'wpshield-license',
			'menu_position'   => '58.150',
			'version'         => $this->args['version'] ?? '1.0.0',
			'item_id'         => PluginSetup::PRODUCT_ITEM_ID,
		];
	}

	/**
	 * Register handler for product upgrade page.
	 *
	 * @since 1.0.0
	 * @return Menus\Upgrade\UpgradeMenu
	 */
	public function setup_upgrade_handler(): Menus\Upgrade\UpgradeMenu {

		return new Menus\Upgrade\UpgradeMenu( $this->Upgrade_config() );
	}

	/**
	 * Retrieve upgrade page config.
	 *
	 * @param array $config
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function upgrade_config( array $config = [] ): array {

		return [
			'panel-logo'     => '',
			'panel-pre-name' => _x( 'Upgrade to PRO', 'Panel title', 'wpshield' ),
			'menu_title'     => __( 'Upgrade to PRO', 'wpshield' ),
			'panel-name'     => __( 'Upgrade to PRO', 'wpshield' ),
			'panel-desc'     => '',
			'name'           => __( 'Upgrade to PRO', 'wpshield' ),
			'id'             => 'wpshield-upgrade',
			'type'           => 'wpshield-upgrade',
			'menu_position'  => '58.150',
			'version'        => $this->args['version'] ?? '1.0.0',
			'item_id'        => PluginSetup::PRODUCT_ITEM_ID,
			'dir-uri'        => $this->manager->url( 'Dashboard/Menus/Upgrade' ),
		];
	}

	/**
	 * Configuration for pages in BS Product Pages
	 *
	 * @param array $config
	 *
	 * @return array
	 */
	public function config_product_pages( array $config ): array {

		$array_merge = array_merge( $this->args,
			[
				'menu_icon'    => bf_get_icon_tag( 'bsfi-wpshield-menu-icon', '', [ 'base64' => true ] ),
				'product_type' => 'plugin',
				'pages'        => [
					'wpshield-settings' => $this->dashboard_config(),
					'wpshield-license'  => $this->license_config(),
					'wpshield-upgrade'  => $this->upgrade_config(),
				],
			]
		);

		$have_license = apply_filters( 'wpshield-plugin-core/dashboard/pages/have-license', false );

		if ( ! $have_license ) {

			unset( $array_merge['pages']['wpshield-license'] );
		}

		if ( ! function_exists( 'bf_is_product_registered' ) ) {

			include $this->manager->get_libs_dir( 'better-framework/product-pages/license/functions.php' );
		}

		//when product license key is registered!
		if ( $have_license && \bf_is_product_registered( PluginSetup::PRODUCT_ITEM_ID ) ) {

			unset( $array_merge['pages']['wpshield-upgrade'] );
		}

		$config[] = $array_merge;

		return $config;
	}

	/**
	 * Register modules.
	 *
	 * @param array $list
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_modules( array $list ): array {

		$list[] = 'wpshield-license';
		$list[] = 'wpshield-upgrade';
		$list[] = 'wpshield-settings';

		return $list;
	}

	/**
	 * Callback: Used for enqueue scripts in WP backend
	 *
	 * Action: admin_enqueue_scripts
	 *
	 * @since   1.0.0
	 * @return void
	 */
	public function admin_enqueue_scripts(): void {

		wp_enqueue_script( 'jquery' );

		global $hook_suffix;

		$panel_position  = strpos( $hook_suffix, $this->args['panel-id'] );
		$prefix_position = strpos( $hook_suffix, 'product-pages-wpshield-settings' );

		if ( false === $panel_position && false === $prefix_position && ( false === strpos( $hook_suffix, 'admin_page_wpshield/' ) ) ) {

			return;
		}

		wp_enqueue_script(
			'dashboard-asset',
			$this->manager->url( 'Dashboard/assets/js/dashboard.min.js' ),
			[],
			$this->args['version'] ?? '1.0.0',
			true
		);

		wp_localize_script(
			'dashboard-asset',
			'DashboardL10n',
			[
				'endpoint'     => admin_url( 'admin-ajax.php' ),
				'settingsIcon' => bf_get_icon_tag( 'bsai-admin-settings' ),
				'nonce'        => wp_create_nonce( 'dashboard-nonce' ),
				'adminPage'    => sprintf( '%sadmin.php', admin_url() ),
				'referrer'     => sprintf( 'bs-product-page-%s', $this->args['panel-id'] ),
			]
		);

		bf_register_product_enqueue_scripts();
	}
}
