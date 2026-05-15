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

use BetterFrameworkPackage\Framework;

// Load general handy functions
require BF_PATH . 'functions/path.php';
require BF_PATH . 'functions/query.php';
require BF_PATH . 'functions/content.php';
require BF_PATH . 'functions/other.php';
require BF_PATH . 'functions/cache.php';
require BF_PATH . 'functions/enqueue.php';
require BF_PATH . 'functions/shortcodes.php';
require BF_PATH . 'functions/archive.php';
require BF_PATH . 'functions/sidebar.php';
require BF_PATH . 'functions/menu.php';
require BF_PATH . 'functions/options.php';
require BF_PATH . 'functions/multilingual.php';
require BF_PATH . 'functions/block.php';


/**
 * Handy Function for accessing to BetterFramework
 *
 * @return Better_Framework
 */
function Better_Framework() {

	return Better_Framework::self();
}

// Fire Up BetterFramework
Better_Framework()->init();


/**
 * Class Better_Framework
 */
class Better_Framework {

	/**
	 * Version of BF
	 *
	 * @var string
	 */
	public $version = BF_VERSION;


	/**
	 * Defines which sections should be include in BF
	 *
	 * @var array
	 * @since  1.0
	 * @access public
	 */
	public $sections = [
		'admin_panel'            => true,    // Theme option panel generator
		'admin-page'             => true,    // Theme option panel generator
		'admin-menus'            => true,    // Theme option panel generator
		'meta_box'               => true,    // Meta box generator
		'user-meta-box'          => true,    // User meta box generator
		'taxonomy_meta_box'      => false,   // Taxonomy meta box generator
		'load_in_frontend'       => false,   // For loading all BF in frontend, disable this for better performance
		'better-menu'            => false,   // Includes better menu
		'custom-css-fe'          => true,    // BF Front End Custom CSS Generator
		'custom-css-be'          => true,    // BF Back End ( WP Admin ) Custom CSS Generator
		'custom-css-pages'       => false,   // BF Pages Custom CSS
		'custom-css-users'       => true,    // BF Users Custom CSS
		'assets_manager'         => true,    // BF custom css generator
		'page-builder'           => false,   // Page builder functionality extender
		'vc-extender'            => false,   // Deprecated: for backward compatibility
		'woocommerce'            => false,   // WooCommerce functionality
		'bbpress'                => false,   // bbPress functionality
		'product-pages'          => false,   // Products Page
		'fonts-manager'          => false,    // Fonts manager
		'content-injector'       => false,   // Post content injection
		'json-ld'                => false,   // JSON-LD schema generator
		'version-compatibility'  => false,   // Version compatibility manager
		'template-compatibility' => false,   // Files compatibility manager
		'editor-shortcodes'      => false,   // Editor shortcodes
	];

	/**
	 * Inner array of instances
	 *
	 * @var array
	 */
	protected static $instances = [];


	/**
	 * PHP Constructor Function
	 *
	 * @param array $sections default features
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init( $sections = [] ) {

		do_action( 'better-framework/before-init' );

		// define features of BF
		$this->sections = bf_merge_args( $sections, $this->sections );
		$this->sections = apply_filters( 'better-framework/sections', $this->sections );

		$this->setup_modules();

		/**
		 * Fonts Manager
		 */
		if ( $this->sections['fonts-manager'] === true ) {
			self::factory( 'fonts-manager' );
		}
		/**
		 * BF General Functionality For Both Front End and Back End
		 */
		self::factory( 'general' );

		self::factory( 'assets-manager' );

		/**
		 * Content Injector
		 */
		if ( $this->sections['content-injector'] === true ) {
			self::factory( 'content-injector' );
		}

		/**
		 * BF BetterMenu For Improving WP Menu Features
		 */
		if ( true === $this->sections['better-menu'] ) {
			self::factory( 'better-menu' );
		}

		/**
		 * BF Widgets Manager
		 */
		self::factory( 'widgets-manager' );

		/**
		 * BF Shortcodes Manager
		 */
		if ( true === $this->sections['vc-extender'] || true === $this->sections['page-builder'] ) {

			self::factory( 'shortcodes-manager' );
		}

		/**
		 * Editor Shortcodes
		 */
		if ( true === $this->sections['editor-shortcodes'] ) {
			self::factory( 'editor-shortcodes' );
		}

		/**
		 * BF Custom Generator For Front End
		 */
		if ( $this->sections['custom-css-fe'] ) {
			self::factory( 'custom-css-fe' );
		}

		/**
		 * BF Pages and Posts Front End Custom Generator
		 */
		if ( $this->sections['custom-css-pages'] ) {
			self::factory( 'custom-css-pages' );
		}

		/**
		 * BF Users Front End Custom Generator
		 */
		if ( $this->sections['custom-css-users'] ) {
			self::factory( 'custom-css-users' );
		}

		/**
		 * BF Custom Generator For Back End
		 */
		if ( $this->sections['custom-css-be'] ) {
			self::factory( 'custom-css-be' );
		}

		/**
		 * BF WooCommerce
		 */
		if ( $this->sections['woocommerce'] === true && function_exists( 'is_woocommerce' ) ) {
			self::factory( 'woocommerce' );
		}

		/**
		 * BF bbPress
		 */
		if ( $this->sections['bbpress'] === true && class_exists( 'bbpress' ) ) {
			self::factory( 'bbpress' );
		}

		/**
		 * BF Admin Page
		 */
		if ( $this->sections['admin-page'] === true ) {
			self::factory( 'admin-page', false, true );
		}

		/**
		 * BF Admin Panel Generator
		 */
		if ( $this->sections['admin_panel'] === true ) {
			self::factory( 'admin-panel' );
		}

		/**
		 * Products Page
		 */
		if ( $this->sections['product-pages'] === true ) {
			self::factory( 'product-pages' );
		}

		/**
		 * Json-LD Schema Generator
		 */
		if ( $this->sections['json-ld'] === true ) {
			self::factory( 'json-ld' );
		}

		$hook = is_admin() ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';
		add_action( $hook, 'Better_Framework::register_assets', 1 );

		/**
		 * Version compatibility manager
		 */
		if ( $this->sections['version-compatibility'] === true ) {
			self::factory( 'version-compatibility' );
		}

		/**
		 * Files compatibility manager
		 */
		if ( $this->sections['template-compatibility'] === true ) {
			self::factory( 'template-compatibility' );
		}

		/**
		 * Disable Loading BF Fully in Front End
		 */

		if ( ! $this->fully_load() ) {
			return;
		}

		/**
		 * BF Admin Menus
		 */
		if ( $this->sections['admin-menus'] === true ) {
			self::factory( 'admin-menus' );
		}

		/**
		 * BF Core Functionality That Used in Back End
		 */
		self::factory( 'admin-notice' );
		self::factory( 'core', false, true );
		self::factory( 'color' );
		self::factory( 'icon-factory' );

		/**
		 * BF Taxonomy Meta Box Generator
		 */
		if ( $this->sections['taxonomy_meta_box'] === true ) {
			self::factory( 'taxonomy-meta' );
		}

		/**
		 * BF Post & Page Meta Box Generator
		 */
		if ( $this->sections['meta_box'] === true ) {
			self::factory( 'meta-box' );
		}

		/**
		 * BF Post & Page Meta Box Generator
		 */
		if ( $this->sections['user-meta-box'] === true ) {
			self::factory( 'user-meta-box' );
		}

		/**
		 * BF Visual Composer Extender
		 */
		if ( $this->sections['vc-extender'] === true ) {
			self::factory( 'vc-extender' );
		}

		// Admin style and scripts
		if ( is_admin() ) {
			// Hook BF admin assets enqueue
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 100 );
			add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 100 );
			// add_action( 'elementor/frontend/before_enqueue_styles', array( $this, 'admin_enqueue_scripts' ), 100 );

			// Hook BF admin ajax requests
			add_action( 'wp_ajax_bf_ajax', [ $this, 'admin_ajax' ] );
		}
	}

	public function fully_load(): bool {

		return is_admin() || bf_is_rest_request() || $this->sections['load_in_frontend'];
	}

	/**
	 * Build the required object instance
	 *
	 * @param string $object
	 * @param bool   $fresh
	 * @param bool   $just_include
	 *
	 * @return null
	 */
	public static function factory( $object = 'options', $fresh = false, $just_include = false ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {
			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main BetterFramework Class
			 */
			case 'self':
				$class = 'Better_Framework';
				break;

			/**
			 * General Helper Functions
			 */
			case 'helper':
				$class = 'BF_Helper';
				break;

			/**
			 * Query Helper Functions
			 */
			case 'query-helper':
				$class = 'BF_Query';
				break;

			/**
			 * Custom Fonts Manager
			 */
			case 'fonts-manager':
				$class = 'BF_Fonts_Manager';

				break;

			/**
			 * BF General Functionality For Both Front End and Back End
			 */
			case 'general':
				self::factory( 'helper' );

				include BF_PATH . 'metabox/functions.php';      // Post meta box public functions
				include BF_PATH . 'taxonomy/functions.php';     // Taxonomy public functions
				include BF_PATH . 'user-metabox/functions.php'; // Taxonomy public functions
				include BF_PATH . 'admin-panel/functions.php';  // Admin Panel public functions
				self::factory( 'options' );

				return true;
				break;

			/**
			 * BF_Options Used For Retrieving Theme Panel Options
			 */
			case 'options':
				BF_Options::init();

				$class = 'BF_Options';
				break;

			/**
			 * BF BetterMenu For Improving WP Menu Features
			 */
			case 'better-menu':
				$class = 'BF_Menus';
				break;

			/**
			 * BF Post & Page Meta Box Generator
			 */
			case 'meta-box':
				BF_Metabox_Core::init_metabox();

				$class = 'BF_Metabox_Core';
				break;

			/**
			 * BF Users Meta Box Generator
			 */
			case 'user-meta-box':
				$class = 'BF_User_Metabox_Core';
				break;

			/**
			 * BF Taxonomy Meta Box Generator
			 */
			case 'taxonomy-meta':
				$class = 'BF_Taxonomy_Core';
				break;

			/**
			 * BF Admin Panel Generator
			 */
			case 'admin-panel':
				self::factory( 'admin-menus' );

				$class = 'BF_Admin_Panel';
				break;

			/**
			 * BF Admin Page
			 */
			case 'admin-page':
				self::factory( 'admin-menus' );

				$class = 'BF_Admin_Page';
				break;

			/**
			 * BF Admin Menus
			 */
			case 'admin-menus':
				$class = 'BF_Admin_Menus';
				break;

			/**
			 * BF Shortcodes Manager
			 */
			case 'shortcodes-manager':
				$class = 'BF_Shortcodes_Manager';
				break;

			/**
			 * BF Widgets
			 */
			case 'widgets-manager':
				$class = 'BF_Widgets_Manager';
				break;

			/**
			 * BF Widgets Field Generator
			 */
			case 'widgets-field-generator':
				return true;
				break;

			/**
			 * BF Core Functionality That Used in Back End
			 */
			case 'admin-notice':
				$class = 'BF_Admin_Notices';
				break;

			/**
			 * BF Core Functionality That Used in Back End
			 */
			case 'core':
				include BF_PATH . 'core-deprecated/field-generator/functions.php';

				return true;
				break;

			/**
			 * BF Custom Generator For Front End
			 */
			case 'custom-css':
				return true;
				break;

			/**
			 * BF Custom Generator For Front End
			 */
			case 'custom-css-fe':
				self::factory( 'custom-css' );

				$class = 'BF_Front_End_CSS';
				break;

			/**
			 * BF Custom Generator For Back End
			 */
			case 'custom-css-be':
				self::factory( 'custom-css' );

				$class = 'BF_Back_End_CSS';
				break;

			/**
			 * BF Custom Generator Pages and Posts in Front end
			 */
			case 'custom-css-pages':
				self::factory( 'custom-css' );

				$class = 'BF_Pages_CSS';
				break;

			/**
			 * BF Custom Generator Pages and Posts in Front end
			 */
			case 'custom-css-users':
				$class = 'BF_Users_CSS';
				break;

			/**
			 * BF Color Used For Retrieving User Color Schema and Some Helper Functions For Changing Colors
			 */
			case 'color':
				$class = 'BF_Color';
				break;

			/**
			 * BF Color Used For Retrieving User Color Schema and Some Helper Functions For Changing Colors
			 */
			case 'breadcrumb':
				$class = 'BF_Breadcrumb';
				break;

			/**
			 * BF Icon Factory Used For Handling FontIcons Actions
			 */
			case 'icon-factory':
				$class = 'BF_Icons_Factory';
				break;

			/**
			 * Assets Manager
			 */
			case 'assets-manager':
				$class = 'BF_Assets_Manager';
				break;

			/**
			 * Products Manager
			 */
			case 'product-pages':
				include BF_PATH . 'product-pages/init.php';

				return true;

				break;

			/**
			 * editor-shortcodes
			 */
			case 'editor-shortcodes':
				BF_Editor_Shortcodes::Run();

				$class = 'BF_Editor_Shortcodes';

				return true;

				break;

			/**
			 * Content Injector
			 */
			case 'content-injector':
				BF_Content_Inject::init();

				$class = 'BF_Content_Inject';

				break;

			/**
			 * Json-LD
			 */
			case 'json-ld':
				BF_Json_LD_Generator::init();

				$class = 'BF_Json_LD_Generator';

				break;

			case 'version-compatibility':
				BF_Version_Compatibility::init();

				$class = 'BF_Version_Compatibility';

				break;

			case 'template-compatibility':
				BF_Template_Compatibility::init();

				$class = 'BF_Template_Compatibility';

				break;

			case 'htaccess-editor':
				$class = 'BF_Htaccess_Editor';

				break;

			case 'http-util':

				if ( ! class_exists( 'BF_Http_Util' ) ) {

					include BF_PATH . 'core/class-bf-http-util.php';
				}

				$class = 'BF_Http_Util';

				break;

			default:
				return null;
		}

		// Just prepare/includes files
		if ( $just_include ) {
			return;
		}

		// don't cache fresh objects
		if ( $fresh ) {
			return new $class();
		}

		self::$instances[ $object ] = new $class();

		return self::$instances[ $object ];
	}


	protected function setup_modules() {

		\BetterFrameworkPackage\Framework\Core\Setup::setup();

		// register icons
		bf_register_icon_family( 'betterstudio-admin-icons', BF_PATH . '/assets/icons/betterstudio-admin-icons', BF_URI . '/assets/icons/betterstudio-admin-icons', 'bsai' );
		bf_register_icon_family( 'betterstudio-icons', BF_PATH . '/assets/icons/betterstudio-icons', BF_URI . '/assets/icons/betterstudio-icons', 'bsfi' );
		bf_register_icon_family( 'font-awesome', BF_PATH . '/assets/icons/font-awesome', BF_URI . '/assets/icons/font-awesome', 'fa' );
		bf_register_icon_family( 'dashicons', BF_PATH . '/assets/icons/dashicons', BF_URI . '/assets/icons/dashicons', 'dashicons' );
	}


	/**
	 * Used for accessing alive instance of Better_Framework
	 *
	 * static
	 *
	 * @since 1.0
	 * @return Better_Framework
	 */
	public static function self() {

		return self::factory( 'self' );
	}


	/**
	 * Used for getting options from BF_Options
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Options
	 */
	public static function options( $fresh = false ) {

		return self::factory( 'options', $fresh );
	}


	/**
	 * Used for accessing shortcodes from BF_Shortcodes_Manager
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Shortcodes_Manager
	 */
	public static function shortcodes( $fresh = false ) {

		return self::factory( 'shortcodes-manager', $fresh );
	}


	/**
	 * Used for accessing taxonomy meta from BF_Taxonomy_Core
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Taxonomy_Core
	 */
	public static function taxonomy_meta( $fresh = false ) {

		return self::factory( 'taxonomy-meta', $fresh );
	}


	/**
	 * Used for accessing post meta from BF_Metabox_Core
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Metabox_Core
	 */
	public static function post_meta( $fresh = false ) {

		return self::factory( 'meta-box', $fresh );
	}


	/**
	 * Used for accessing widget manager from BF_Widgets_Manager
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Widgets_Manager
	 */
	public static function widget_manager( $fresh = false ) {

		return self::factory( 'widgets-manager', $fresh );
	}


	/**
	 * Used for accessing widget manager from BF_Widgets_Manager
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Breadcrumb
	 */
	public static function breadcrumb( $fresh = false ) {

		return self::factory( 'breadcrumb', $fresh );
	}


	/**
	 * Used for accessing BF_Admin_Notices for adding notice to admin panel
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Admin_Notices
	 */
	public static function admin_notices( $fresh = false ) {

		return self::factory( 'admin-notice', $fresh );
	}


	/**
	 * Used for accessing BF_Assets_Manager for enqueue styles and scripts
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Assets_Manager
	 */
	public static function assets_manager( $fresh = false ) {

		return self::factory( 'assets-manager', $fresh );
	}


	/**
	 * Used for accessing BF_Helper
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Helper
	 */
	public static function helper( $fresh = false ) {

		return self::factory( 'helper', $fresh );
	}


	/**
	 * Used for accessing BF_Query
	 *
	 * Deprecated!
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Query
	 */
	public static function helper_query( $fresh = false ) {

		return self::factory( 'query-helper', $fresh );
	}


	/**
	 * Used for accessing BF_Icons_Factory
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Icons_Factory
	 */
	public static function icon_factory( $fresh = false ) {

		return self::factory( 'icon-factory', $fresh );
	}


	/**
	 * Used for accessing BF_Fonts_Manager
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Fonts_Manager
	 */
	public static function fonts_manager( $fresh = false ) {

		return self::factory( 'fonts-manager', $fresh );
	}


	/**
	 * Used for accessing BF_User_Metabox_Core
	 *
	 * @param bool $fresh
	 *
	 * @return BF_User_Metabox_Core
	 */
	public static function user_meta( $fresh = false ) {

		return self::factory( 'user-meta-box', $fresh );
	}


	/**
	 * Used for accessing Better_Admin_Panel
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Admin_Panel
	 */
	public static function admin_panel( $fresh = false ) {

		return self::factory( 'admin-panel' );
	}


	/**
	 * Used for accessing Better_Admin_Page
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Admin_Page
	 */
	public static function admin_page( $fresh = false ) {

		return self::factory( 'admin-page' );
	}


	/**
	 * Used for accessing BF_Admin_Menus
	 *
	 * @param bool $fresh
	 *
	 * @return BF_Admin_Menus
	 */
	public static function admin_menus( $fresh = false ) {

		return self::factory( 'admin-menus' );
	}


	/**
	 * Gets a WP_Theme object for a theme.
	 *
	 * @param bool $parent
	 * @param bool $fresh
	 * @param bool $cache_this
	 *
	 * @return  WP_Theme
	 */
	public static function theme( $parent = true, $fresh = false, $cache_this = true ) {

		if ( isset( self::$instances['theme'] ) && ! $fresh ) {
			return self::$instances['theme'];
		}

		$theme = wp_get_theme();

		if ( $parent && ( '' != $theme->get( 'Template' ) ) ) {
			$theme = wp_get_theme( $theme->get( 'Template' ) );
		}

		if ( $cache_this === true ) {
			return self::$instances['theme'] = $theme;
		} else {
			return $theme;
		}

	}


	/**
	 * Reference To HTML Generator Class
	 *
	 * static
	 *
	 * @since 1.0
	 * @return BF_HTML_Generator
	 */
	public static function html() {

		return new BF_HTML_Generator();
	}


	/**
	 * Callback: Handle BF Admin Enqueue's
	 *
	 * Action: admin_enqueue_scripts
	 *
	 * @since   1.0
	 *
	 * @return  object
	 */
	public function admin_enqueue_scripts() {
		global $pagenow;

		if ( stristr( current_filter(), 'elementor' ) ) {

			self::register_assets();
		}

		/*
		 * enqueue admin-scripts in all pages
		 *
		// enqueue scripts if features enabled
		if( $this->sections['admin_panel'] == true  ||
			$this->sections['meta_box'] == true     ||
			$this->sections['better-menu'] == true  ||
			$this->sections['taxonomy_meta_box'] == true
		){
			if( $this->get_current_page_type() != '' ){*/

		$this->enqueue_media();

		// BetterFramework Admin scripts
		wp_enqueue_script( 'better-framework-admin' );
		wp_enqueue_script( 'better-framework-admin-panel' );

		if ( ( $type = $this->get_current_page_type() ) == '' ) {
			$type = '0';
		}

		$better_framework_loc = [
			'bf_ajax_url'      => admin_url( 'admin-ajax.php' ),
			'nonce'            => wp_create_nonce( 'bf_nonce' ),
			'type'             => $type,
			'lang'             => bf_get_current_lang(),
			'use_block_editor' => function_exists( 'use_block_editor_for_post' ) && use_block_editor_for_post( null ),

			// Localized Texts
			'translation'      => [
				'reset_panel'  => [
					'header'     => __( 'Reset options', 'better-studio' ),
					'title'      => __( 'Are you sure to reset options?', 'better-studio' ),
					'body'       => __( 'With resetting panel all your changes will be lost and will be replaced with default settings.', 'better-studio' ),
					'button_yes' => __( 'Yes, Reset options', 'better-studio' ),
					'button_no'  => __( 'No', 'better-studio' ),
					'resetting'  => __( 'Resetting options', 'better-studio' ),
				],
				'import_panel' => [
					'prompt' => __( 'Do you really wish to override your current settings?', 'better-studio' ),
				],
				'icon_modal'   => [
					'custom_icon' => __( 'Custom icon', 'better-studio' ),
				],
				'show_all'     => __( '… See all', 'better-studio' ),
				'widgets'      => [
					'save' => __( 'Save', 'better-studio' ),
				],
			],

			'loading'          => '<div class="bf-loading-wrapper"><div class="bf-loading-anim"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>',
			'term_select'      => [
				'make_primary' => __( 'Make Primary', 'better-studio' ),
				'excluded'     => __( 'Excluded', 'better-studio' ),
			],

			'on_error'         => [
				'button_ok'       => __( 'Ok', 'better-studio' ),
				'default_message' => __( 'Cannot complete ajax request.', 'better-studio' ),
				'body'            => __( 'please try again several minutes later or contact better studio team support.', 'better-studio' ),
				'header'          => __( 'ajax request failed', 'better-studio' ),
				'title'           => __( 'an error occurred', 'better-studio' ),
				'display_error'   => '<div class="bs-pages-error-section"><a href="#" class="btn bs-pages-error-copy" data-copied="' . esc_attr__( 'Copied !', 'better-studio' ) . '">' . bf_get_icon_tag( 'fa-files-o' ) . __( 'Copy', 'better-studio' ) . '</a>  <textarea> ' . __( 'Error', 'better-studio' ) . ':  %ERROR_CODE% %ERROR_MSG% </textarea></div>',
				'again'           => __( 'Error: please try again', 'better-studio' ),
			],

			'fields'           => [
				'select_popup'         => [
					'header'            => '%%name%%',
					'search'            => __( 'Search...', 'better-studio' ),
					'btn_label'         => __( 'Choose', 'better-studio' ),
					'btn_label_active'  => __( 'Current', 'better-studio' ),

					'filter_cat_title'  => __( 'Category', 'better-studio' ),
					'categories'        => [],

					'filter_type_title' => __( 'Type', 'better-studio' ),
					'all_l10n'          => __( 'All', 'better-studio' ),

					'types'             => [],
				],

				'select_popup_confirm' => [
					'header'        => __( 'Do you want to change %%name%%?', 'better-studio' ),
					'button_ok'     => __( 'Yes, Change', 'better-studio' ),
					'button_cancel' => __( 'Cancel', 'better-studio' ),

					'caption'       => '%s',
				],
			],
		];

		/**
		 * This hook to turn on "use_widgets_block" of theme support option to loading custom block types!
		 *
		 * @hook 'better-framework/l10n/use_widgets_block'
		 *
		 * @since 4.0.0
		 */
		$better_framework_loc['use_widgets_block'] = apply_filters(
			'better-framework/l10n/use_widgets_block',
			$pagenow === 'widgets.php' &&
			function_exists( 'wp_use_widgets_block_editor' )
			&& wp_use_widgets_block_editor()
		);

		wp_localize_script( 'better-framework-admin', 'better_framework_loc', apply_filters( 'better-framework/localized-items', $better_framework_loc ) );

		// BetterFramework admin style
		wp_enqueue_style( 'better-framework-admin' );

		if ( $this->get_current_page_type() == 'metabox' ) {
			bf_enqueue_modal( 'icon' ); // safe enqueue for fixing visual composer bug
		}

		bf_enqueue_style( 'bf-icon' );
	}


	/**
	 * Enqueue wp media JS APIs.
	 *
	 * @since 3.15.1
	 */
	protected function enqueue_media() {

		// FIX: performance issue
		// skip database query by adding these filters.
		add_filter( 'media_library_show_audio_playlist', '__return_empty_array' );
		add_filter( 'media_library_show_video_playlist', '__return_empty_array' );
		add_filter( 'media_library_months_with_files', '__return_empty_array' );
				wp_enqueue_media();
				remove_filter( 'media_library_show_audio_playlist', '__return_empty_array' );
		remove_filter( 'media_library_show_video_playlist', '__return_empty_array' );
		remove_filter( 'media_library_months_with_files', '__return_empty_array' );
	}


	/**
	 * Used for finding current page type
	 *
	 * @return string
	 */
	public function get_current_page_type() {

		global $pagenow;

		$type = '';

		switch ( $pagenow ) {

			case 'post-new.php':
			case 'post.php':
				if ( isset( $_GET['action'] ) && $_GET['action'] === 'elementor' ) {

					$type = 'elementor';

				} else {

					$type = 'metabox';
				}

				break;

			case 'term.php':
			case 'edit-tags.php':
				$type = 'taxonomy';
				break;

			case 'widgets.php':
				$type = 'widgets';
				break;

			case 'nav-menus.php':
				$type = 'menus';
				break;

			case 'profile.php':
			case 'user-new.php':
			case 'user-edit.php':
				$type = 'users';
				break;

			case 'index.php':
				$type = 'dashboard';
				break;

			default:
				$pattern = '/^\w+\/.*|^\w+-(?:\w+-|\w+\/.*)/';

				if ( isset( $_GET['page'] ) && ( preg_match( $pattern, $_GET['page'] ) || preg_match( $pattern, $_GET['page'] ) ) ) {
					$type = 'panel';
				}
		}

		return $type;
	}

	public static function callback_token( $callback ) {

		return wp_create_nonce( sprintf( 'bf-custom-callback:%s', $callback ) );
	}


	/**
	 * Handle All Ajax Requests in Back-End
	 *
	 * @since 1.0
	 * @return mixed
	 */
	public function admin_ajax() {

		// Check Nonce
		if ( ! isset( $_REQUEST['nonce'], $_REQUEST['reqID'] ) ) {
			die(
				wp_json_encode(
					[
						'status' => 'error',
						'msg'    => __( 'Security Error!', 'better-studio' ),
					]
				)
			);
		}

		$_nonce = wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'bf_nonce' );

		// Check Nonce
		if ( false === $_nonce ) {
			die(
				wp_json_encode(
					[
						'status' => 'error',
						'msg'    => __( 'Security Error!', 'better-studio' ),
					]
				)
			);
		}

		try {

			switch ( $_REQUEST['reqID'] ) {

				// Option Panel, Save Settings
				case ( 'save_admin_panel_options' ):
					$options = bf_parse_str( ltrim( rtrim( stripslashes( $_REQUEST['data'] ), '&' ), '&' ) );

					$data = [
						'id'   => sanitize_text_field( wp_unslash( $_REQUEST['panelID'] ?? '' ) ),
						'lang' => sanitize_text_field( wp_unslash( $_REQUEST['lang'] ?? '' ) ),
						'data' => $options,
					];

					if ( ! current_user_can( $this->panel_capability( $data['id'] ) ) ) {

						die(
						wp_json_encode(
							[
								'status' => 'error',
								'msg'    => __( 'Security Error!', 'better-studio' ),
							]
						)
						);
					}

					do_action( 'better-framework/panel/save', $data );
					break;

				// Option Panel, Reset Settings
				case ( 'reset_options_panel' ):

					$panel_id = sanitize_text_field( wp_unslash( $_REQUEST['panelID'] ?? '' ) );

					if ( ! current_user_can( $this->panel_capability( $panel_id ) ) ) {

						die(
						wp_json_encode(
							[
								'status' => 'error',
								'msg'    => __( 'Security Error!', 'better-studio' ),
							]
						)
						);
					}

					/**
					 * Fires for handling panel reset
					 *
					 * @param string $args reset panel data
					 *
					 * @since 1.0.0
					 */
					do_action(
						'better-framework/panel/reset',
						[
							'lang' => sanitize_text_field( wp_unslash( $_REQUEST['lang'] ?? '' ) ),
						//phpcs:disable
						'options' => $_REQUEST['to_reset'],
						//phpcs:enable
							'id'      => $panel_id,
						]
					);
					break;

				// Option Panel, Ajax Action
				case ( 'ajax_action' ):
					$callback = sanitize_text_field( wp_unslash( $_REQUEST['callback'] ?? '' ) );
					//phpcs:ignore
					$args          = $_REQUEST['args'] ?? '';
					$error_message = sanitize_text_field( wp_unslash( $_REQUEST['error-message'] ?? '' ) ) ?? __( 'An error occurred while doing action.', 'better-studio' );

					// Security issue fix
					if ( empty( $_REQUEST['bf_call_token'] ) || self::callback_token( $callback ) !== $_REQUEST['bf_call_token'] ) {

						echo wp_json_encode(
							[
								'status' => 'error',
								'msg'    => __( 'the security token is not valid!', 'better-studio' ),
							]
						);

						return;
					}
					if ( ! empty( $callback ) && is_callable( $callback ) ) {

						if ( is_array( $args ) ) {

							$to_return = call_user_func_array( $callback, $args );

						} else {

							$to_return = call_user_func( $callback, $args );

						}

						if ( is_array( $to_return ) ) {
							echo wp_json_encode( $to_return );
						} else {
							echo wp_json_encode(
								[
									'status' => 'error',
									'msg'    => $error_message,
								]
							);
						}
					} else {
						echo wp_json_encode(
							[
								'status' => 'error',
								'msg'    => $error_message,
							]
						);
					}
					break;

				// Option Panel, Ajax Field
				case ( 'ajax_field' ):
					if ( isset( $_REQUEST['callback'] ) && is_callable( $_REQUEST['callback'] ) ) {

						$cb = sanitize_text_field( wp_unslash( $_REQUEST['callback'] ) );

						$cb_args = [
							sanitize_text_field( wp_unslash( $_REQUEST['key'] ?? '' ) ),
							sanitize_text_field( wp_unslash( $_REQUEST['exclude'] ?? '' ) ),
						];

						$to_return = call_user_func_array( $cb, $cb_args );

						if ( is_array( $to_return ) ) {
							echo 0 === count( $to_return ) ? - 1 : wp_json_encode( $to_return );
						}
					}

					break;

				// Option Panel, Import Settings
				case ( 'import' ):
					//phpcs:ignore
					$data = $_FILES['bf-import-file-input'];

					/**
					 * Fires for handling panel import
					 *
					 * @param string $data contain import file data
					 * @param string $args contain import arguments
					 *
					 * @since 1.1.0
					 */
					do_action( 'better-framework/panel/import', $data, $_REQUEST );

					break;

				case 'fetch-deferred-field':
					if (
						! empty( $_REQUEST['sectionID'] ) &&
						! empty( $_REQUEST['panelID'] ) &&
						is_string( $_REQUEST['sectionID'] )
					) {  // panel field

						do_action(
							'better-framework/panel/ajax-panel-field',
							sanitize_text_field( wp_unslash( $_REQUEST['panelID'] ) ),
							sanitize_text_field( wp_unslash( $_REQUEST['sectionID'] ) )
						);
					} elseif ( ! empty( $_REQUEST['sectionID'] ) &&
							   ! empty( $_REQUEST['metabox'] ) &&
							   ! empty( $_REQUEST['metabox_id'] ) &&
							   is_string( $_REQUEST['sectionID'] )
					) { // metabox field

						$type      = sanitize_text_field( wp_unslash( $_REQUEST['type'] ?? '' ) );
						$object_id = sanitize_text_field( wp_unslash( $_REQUEST['object_id'] ?? '' ) );

						if ( 'taxonomy' === $type ) {

							$hook = 'better-framework/taxonomy/metabox/ajax-tab';

						} elseif ( 'users' === $type ) {

							$hook = 'better-framework/user-metabox/ajax-tab';

						} else {

							$hook = 'better-framework/metabox/ajax-tab';
						}

						do_action(
							$hook,
							sanitize_text_field( wp_unslash( $_REQUEST['sectionID'] ) ),
							sanitize_text_field( wp_unslash( $_REQUEST['metabox_id'] ) ),
							$object_id
						);
					}

					break;

				case 'fetch-mce-view-fields':
					if ( ! empty( $_REQUEST['shortcode'] ) ) {

						do_action(
							'better-framework/shortcodes/tinymce-fields',
							sanitize_text_field( wp_unslash( $_REQUEST['shortcode'] ) ),
							$_REQUEST
						);
					}

					break;

				case 'fetch-mce-view-shortcode':
					if ( ! empty( $_REQUEST['shortcodes'] ) ) {
						do_action(
							'better-framework/shortcodes/tinymce-view-shortcode',
							sanitize_text_field(
								wp_unslash( $_REQUEST['shortcodes'] )
							),
							$_REQUEST
						);
					}

					break;

				case 'fetch-controls-view':
					if ( ! empty( $_REQUEST['id'] ) ) {

						do_action( 'better-framework/controls-view', sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) );
					}

					break;
			}
		} catch ( Exception $e ) {

			$result = [
				'error_message' => $e->getMessage(),
				'error_code'    => $e->getCode(),
				'is_error'      => true,
			];

			echo wp_json_encode( compact( 'result' ) );
		}

		die;
	}

	protected function panel_capability( string $panel_id ): string {

		$config = apply_filters( "better-framework/panel/{$panel_id}/config", [] );

		return $config['config']['capability'] ?? 'manage_options';
	}


	public static function register_assets() {

		self::register_scripts();
		self::register_styles();
	}


	public static function register_scripts() {

		//phpcs:ignore
		$prefix  = ! bf_is( 'dev' ) ? '.min' : '';

		// Element Query
		bf_register_script(
			'element-query',
			BF_URI . 'assets/js/element-query.min.js',
			[ 'jquery' ],
			BF_PATH . 'assets/js/element-query.min.js',
			BF_VERSION
		);

		// PrettyPhoto
		bf_register_script(
			'pretty-photo',
			BF_URI . 'assets/js/pretty-photo' . $prefix . '.js',
			[ 'jquery' ],
			BF_PATH . 'assets/js/pretty-photo' . $prefix . '.js',
			BF_VERSION
		);

		//
		// Admin Scripts
		//
		bf_register_script(
			'bf-show-on',
			BF_URI . 'packages/showon/dist/show-on.js',
			bf_enqueue_dependencies( BF_PATH . 'packages/showon/dist/show-on.js' ),
			BF_VERSION
		);

		bf_register_script(
			'bf-admin-plugins',
			BF_URI . 'assets/js/admin-plugins' . $prefix . '.js',
			[
				'jquery',
				'bf-show-on',
			],
			BF_PATH . 'assets/js/admin-plugins' . $prefix . '.js',
			BF_VERSION
		);

		// Ace Code Editor
		if ( is_admin() ) {
			add_action( 'admin_footer', 'BF_Assets_Manager::print_ace_editor_oldie_js' );
		} elseif ( is_user_logged_in() ) {
			add_action( 'wp_footer', 'BF_Assets_Manager::print_ace_editor_oldie_js' );
		}

		// TinyMCE Shortcode View
		bf_register_script(
			'tinymce-addon',
			BF_URI . 'assets/js/tinymce-addon' . $prefix . '.js',
			[ 'jquery' ],
			BF_PATH . 'assets/js/tinymce-addon' . $prefix . '.js',
			BF_VERSION
		);

		bf_register_script(
			'bf-gutenberg-fields',
			BF_URI . 'packages/controls/dist/controls-gutenberg.js',
			bf_enqueue_dependencies( BF_PATH . 'packages/controls/dist/controls-gutenberg.js' ),
			BF_PATH . 'packages/controls/dist/controls-gutenberg.js',
			BF_VERSION
		);

		bf_register_script(
			'bf-gutenberg',
			BF_URI . 'assets/bundles/gutenberg.js',
			array_merge(
				[
					'bf-gutenberg-fields',
					'wp-api-request',
				],
				bf_enqueue_dependencies( BF_PATH . 'assets/bundles/gutenberg.js' )
			),
			BF_PATH . 'assets/bundles/gutenberg.js',
			BF_VERSION
		);

		// BetterFramework admin script
		bf_register_script(
			'better-framework-admin',
			BF_URI . 'assets/js/admin-scripts' . $prefix . '.js',
			[
				'jquery',
				'jquery-ui-core',
				'jquery-ui-widget',
				'jquery-ui-slider',
				'jquery-ui-sortable',
				'bf-admin-plugins',
				'ace-editor-script',
			],
			BF_PATH . 'assets/js/admin-scripts' . $prefix . '.js',
			BF_VERSION
		);
		// BetterFramework admin panel script
		bf_register_script(
			'better-framework-admin-panel-deprecated',
			BF_URI . 'assets/js/admin-panel-deprecated' . $prefix . '.js',
			[
				'jquery',
			],
			BF_PATH . 'assets/js/admin-panel' . $prefix . '.js',
			BF_VERSION
		);

		// todo: add min version
		bf_register_script(
			'better-studio-controls-dependencies',
			BF_URI . 'packages/controls/dist/controls-dependencies.js',
			bf_enqueue_dependencies( BF_PATH . 'packages/controls/dist/controls-dependencies.js' ),
			BF_PATH . 'packages/controls/dist/controls-dependencies.js',
			BF_VERSION
		);
		bf_register_script(
			'better-studio-controls',
			BF_URI . 'packages/controls/dist/controls-script.js',
			bf_enqueue_dependencies(BF_PATH . 'packages/controls/dist/controls-script.js', 'better-studio-controls-dependencies'),
			BF_PATH . 'packages/controls/dist/controls-script.js',
			BF_VERSION
		);
		unset( $dependencies );

		bf_register_script(
			'better-framework-admin-panel',
			BF_URI . 'assets/js/admin-panel' . $prefix . '.js',
			[
				'jquery',
				'better-framework-admin',
				'better-framework-admin-panel-deprecated',
				'better-studio-controls',
			],
			BF_PATH . 'assets/js/admin-panel' . $prefix . '.js',
			BF_VERSION
		);

		//phpcs:ignore
		bf_call_func( 'wp' . '_' . 'deregister' . '_' . 'script', 'ace-editor' ); // remove VC troubled script
		wp_register_script(
			'ace-editor-script',
			'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.8/ace.js',
			[],
			BF_VERSION,
			true
		);

		// Slick carousel
		bf_register_script(
			'bf-slick',
			BF_URI . 'assets/js/slick' . $prefix . '.js',
			[ 'jquery' ],
			BF_PATH . 'assets/js/slick' . $prefix . '.js',
			BF_VERSION
		);
	}

	public static function register_styles() {

		$prefix = ( is_rtl() ? '.rtl' : '' ) . ( ! bf_is( 'dev' ) ? '.min' : '' );

		// BF Icon
		bf_register_style(
			'bf-icon',
			BF_URI . 'assets/css/bf-icon' . ( ! bf_is( 'dev' ) ? '.min' : '' ) . '.css',
			[],
			BF_PATH . 'assets/css/bf-icon' . ( ! bf_is( 'dev' ) ? '.min' : '' ) . '.css',
			BF_VERSION
		);

		// Pretty Photo
		bf_register_style(
			'pretty-photo',
			BF_URI . 'assets/css/pretty-photo' . $prefix . '.css',
			[],
			BF_PATH . 'assets/css/pretty-photo' . $prefix . '.css',
			BF_VERSION
		);

		//
		// Admin Styles
		//

		// BF Used Plugins CSS
		bf_register_style(
			'bf-admin-plugins',
			BF_URI . 'assets/css/admin-plugins' . $prefix . '.css',
			[],
			BF_PATH . 'assets/css/admin-plugins' . $prefix . '.css',
			BF_VERSION
		);

		// todo: add min version
		bf_register_style(
			'better-studio-controls-dependencies',
			BF_URI . 'packages/controls/dist/controls-dependencies.css',
			[],
			BF_PATH . 'packages/controls/dist/controls-dependencies.css',
			BF_VERSION
		);
		bf_register_style(
			'better-studio-controls',
			BF_URI . 'packages/controls/dist/controls-style.css',
			[ 'better-studio-controls-dependencies' ],
			BF_PATH . 'packages/controls/dist/controls-style.css',
			BF_VERSION
		);

		bf_register_style(
			'better-framework-admin',
			BF_URI . 'assets/css/admin-style' . $prefix . '.css',
			[
				'better-studio-controls',
				'bf-admin-plugins',
			],
			BF_PATH . 'assets/css/admin-style' . $prefix . '.css',
			BF_VERSION
		);

		// Slick carousel
		bf_register_style(
			'bf-slick',
			BF_URI . 'assets/css/slick' . $prefix . '.css',
			[],
			BF_PATH . 'assets/css/slick' . $prefix . '.css',
			BF_VERSION
		);
	}

	public static function get_core_cache( $key, $default = null ) {

		$options = get_option( 'bf-core-cache' );

		return $options[ $key ] ?? $default;
	}


	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return bool False if value was not updated and true if value was updated.
	 */
	public static function set_core_cache( $key, $value ) {

		$options = get_option( 'bf-core-cache', [] );

		$options[ $key ] = $value;

		return update_option( 'bf-core-cache', $options );
	}
}
