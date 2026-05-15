<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */

use BetterFrameworkPackage\Core\Module;


/**
 * Class BF_Product_Pages
 */
class BF_Product_Pages {

	use BF_Product_Pages_Base;

	/**
	 * Current version number of BS Product Pages
	 *
	 * todo move this to better location
	 *
	 * @var string
	 */
	public static $version = '1.0.0';


	/**
	 * Base menu slug
	 *
	 * @var string
	 */
	public static $menu_slug = 'bs-product-pages';


	/**
	 * Used to get current version number
	 *
	 * @return string
	 */
	public static function get_version() {

		return self::$version;
	}


	/**
	 * @var array
	 */
	protected $instances = [];


	/**
	 * Initialize
	 */
	public static function Run(): self {

		global $bs_theme_pages;

		if ( ! $bs_theme_pages instanceof self ) {
			$bs_theme_pages = new self();
			$bs_theme_pages->init();
		}

		return $bs_theme_pages;
	}


	public static function get_asset_url( $file_path ) {

		return self::$config['URI'] . "/assets/$file_path";
	}


	public static function get_asset_path( $file_path ) {

		return self::$config['path'] . "/assets/$file_path";
	}


	/**
	 * Use to get URL of BS Theme Pages
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	public static function get_url( $append = '' ) {

		return self::$config['URI'] . '/' . $append;
	}


	/**
	 * Use to get path of BS Theme Pages
	 *
	 * @param string $append
	 *
	 * @return string
	 */
	public static function get_path( $append = '' ) {

		return trailingslashit( self::$config['path'] ) . ltrim( $append, '/' );
	}


	public function init() {

		add_action( 'wp_ajax_bs_pages_ajax', [ $this, 'ajax_response' ] );

		add_action( 'after_switch_theme', [ $this, 'show_welcome_page' ], 999 );

		add_action( 'better-framework/controls-view', [ $this, 'controls_view_response' ] );

		$this->load_modules_function_file();
	}


	/**
	 * handle ajax requests
	 */

	public function ajax_response() {

		try {

			if ( ! isset( $_REQUEST['active-page'], $_REQUEST['token'] ) ) {

				throw new Exception( __( 'active-page or token field is empty!', 'better-studio' ) );
			}

			$sub_page_id = sanitize_text_field( wp_unslash( $_REQUEST['active-page'] ) );

			// validate request
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['token'] ) ), 'bs-pages-' . $sub_page_id ) ) {

				throw new Exception( 'Security Error' );
			}

			$settings    = bf_get_product_item_params( $this->get_config(), $sub_page_id );
			$item_params = array_merge( $_REQUEST, $settings );

			$instance = $this->get_instance( $sub_page_id );
			// call ajax_request method of children class
			$response = $instance->ajax_request( $item_params );

			if ( ! $response ) {

				$message = 'invalid request: cannot process your request.';

				if ( bf_is( 'dev' ) ) {

					$message .= sprintf( 'check method: %s::ajax_request', get_class( $instance ) );
				}

				wp_send_json(
					[
						'success' => 0,
						'error'   => $message,
					]
				);
			} else {

				wp_send_json(
					[
						'success' => empty( $response['is_error'] ),
						'result'  => $response,
					]
				);
			}

			$instance = null;

		} catch ( Exception $error ) {

			$response = [
				'is_error'   => true,
				'status'     => 'error',
				'error'      => $error->getMessage(),
				'error_code' => $error->getCode(),
			];

			if ( bf_is( 'dev' ) || bf_is( 'demo-dev' ) ) {

				$response['trace'] = $error->getTraceAsString();
			}

			wp_send_json( $response );
		}

		exit;
	}


	public function plugins_menu_instance() {

		return new BF_Product_Plugin_Manager();
	}


	public function install_demo_menu_instance() {

		require_once $this->get_path( 'install-demo/functions.php' );

		return new BF_Product_Demo_Manager();
	}


	public function support_menu_instance() {

		return new BF_Product_Support();
	}


	public function license_menu_instance() {

		return new BF_Product_License();
	}


	public function welcome_menu_instance() {

		require_once $this->get_path( 'welcome/class-bf-product-welcome.php' );

		return new BF_Product_Welcome();
	}


	public function report_menu_instance() {

		return new BF_Product_Report();
	}


	/**
	 *
	 * @return array list of modules  array {
	 *
	 *  module name (directory name)
	 *  ...
	 * }
	 */
	protected function get_modules_list() {

		return apply_filters(
			'better-framework/product-pages/modules/list',
			[
				'install-plugin',
				'install-demo',
				'support',
				'report',
				'welcome',
				'license',
				'compatibility',
			]
		);
	}


	/**
	 * callback: load modules functions.php file
	 * action: admin_init
	 */

	public function load_modules_function_file() {

		foreach ( $this->get_modules_list() as $dir ) {

			$functions_file = $this->get_path( "$dir/functions.php" );

			if ( file_exists( $functions_file ) ) {
				require_once $functions_file;
			}
		}
	}


	/**
	 *
	 * @param string $handler_name
	 *
	 * @return bool|string
	 */
	public function get_item_handler_instance( $handler_name ) {

		$suffix   = '_menu_instance';
		$method   = str_replace( '-', '_', $handler_name ) . $suffix;
		$callback = [ $this, $method ];

		if ( is_callable( $callback ) ) {

			return call_user_func( $callback );
		}

		return apply_filters( 'better-framework/product-pages/' . $handler_name . '-item/handler', '' );
	}


	/**
	 * return item object instance
	 *
	 * @param $sub_page_id
	 *
	 * @throws Exception
	 * @return BF_Product_Item
	 */
	public function get_instance( $sub_page_id ) {

		if ( isset( $this->instances[ $sub_page_id ] ) ) {
			return $this->instances[ $sub_page_id ];
		}

		$settings = bf_get_product_item_params( $this->get_config(), $sub_page_id );

		if ( empty( $settings ) || ! isset( $settings['type'] ) ) {
			throw new Exception( 'cannot process your request' );
		}

		$instance = $this->get_item_handler_instance( $settings['type'] );

		if ( ! $instance instanceof BF_Product_Item ) {
			throw new Exception( 'Manager Class is not instance of BS_Theme_Pages_Menu Class' );
		}

		$this->instances[ $sub_page_id ] = $instance;

		return $instance;
	}


	/**
	 * @param string $page_slug
	 *
	 * @throws Exception
	 * @return string
	 */

	public function the_sub_page_id( $page_slug ) {

		$prefix = preg_quote( self::$menu_slug );

		if ( preg_match( "/$prefix\-*(.+)$/i", $page_slug, $match ) ) {

			return $match[1];
		}

		return '';
	}


	/**
	 * Callback function for menus & sub menus
	 */
	public function menu_callback() {

		global $page_hook;

		try {

			$sub_page_id = $this->the_sub_page_id( $page_hook );

			if ( ! $sub_page_id ) {

				throw new Exception( 'cannot process your request' );
			}

			$settings    = $this->get_config();
			$item_params = &$settings['pages'][ $sub_page_id ];

			$instance = $this->get_instance( $sub_page_id );

			// display html result to admin user
			$instance->display( /*$item_params*/ );

			$instance = null;

		} catch ( Exception $e ) {

			$this->error( $e->getMessage() );
		}

	}


	/**
	 * callback: Redirect user to welcome if welcome page is available after actived BS Theme
	 *
	 * action: after_switch_theme
	 */
	public function show_welcome_page() {

		global $pagenow;
		if ( $pagenow == 'admin.php' ) {
			return;
		}

		$settings = $this->get_config();
		if ( isset( $settings['pages'] ) && is_array( $settings['pages'] ) ) {

			foreach ( $settings['pages'] as $id => $menu ) {

				if ( $menu['type'] === 'welcome' ) {

					wp_safe_redirect( admin_url( 'admin.php?page=' . self::$menu_slug . "-$id" ) );
					exit;
				}
			}
		}
	}

	public function controls_view_response( $id ) {

		if ( ! preg_match( '/^demo:(.+)$/', $id, $match ) ) {

			return;
		}

		$demo_id = $match[1];
		$demos   = apply_filters( 'better-framework/product-pages/install-demo/config', bf_get_demos_list() );

		if ( empty( $demos[ $demo_id ] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( sprintf( 'invalid request: Demo %s not found.', $demo_id ) );
		}

		[ $fields, $values ] = $this->generate_fields( $demos[ $demo_id ] );
		$items = compact( 'fields' );

		ob_start();
		echo '<form id="fields">';
		$generator = new BF_Simple_Field_Generator( $items, $id, $values );
		$generator->output();
		echo '</form>';

		wp_send_json_success(
			ob_get_clean()
		);
	}

	public function generate_fields( array $demo ): array {

		$fields = $values = [];

		if ( isset( $demo['page_builders'] ) && count( $demo['page_builders'] ) >= 2 ) {

			$fields['page_builder'] = [
				'name'        => __( 'Demo Based on Page Builder', 'better-studio' ),
				'id'          => 'page_builder',
				'type'        => 'advance_select',
				'input_class' => 'input_class',
				'options'     => $demo['page_builders'],
				'vertical'    => true,
			];
			$values['page_builder'] = key( $demo['page_builders'] );
		}

		$fields['contents'] = [
			'name'        => __( 'Demo Contents', 'better-studio' ),
			'id'          => 'contents',
			'type'        => 'advance_select',
			'input_class' => 'input_class',
			'options'     => [
				'1' => [
					'label'  => __( 'Complete Demo Contents', 'better-studio' ),
					'status' => 'active',
				],
				'0' => 'Setting only',
			],
			'vertical'    => true,
		];
		$values['contents'] = '1';

		if ( isset( $demo['languages'] ) && count( $demo['languages'] ) >= 2 ) {

			$languages = bf_array_move_keys( [ 'en' ], $demo['languages'] );

			$fields['language'] = [
				'name'        => __( 'Demo Language', 'better-studio' ),
				'id'          => 'language',
				'type'        => 'advance_select',
				'input_class' => 'input_class',
				'options'     => $languages,
				'vertical'    => true,
			];
			$values['language'] = key( $languages );
		}

		return [ $fields, $values ];
	}

}
