<?php

namespace BetterStudio\Framework\Pro\Booster;

/**
 * BetterStudio Speed Booster
 *
 *
 * @see      http://www.betterstudio.com
 * @author   BetterStudio <info@betterstudio.com>
 * @access   public
 * @package  BetterFramework
 */
class Booster {

	/**
	 * Panel ID
	 *
	 * @var string
	 */
	public static $option_panel_id = 'bs-booster';

	/**
	 * Inner array of object instances and caches
	 *
	 * @var array
	 */
	protected static $instances = array();

	function __construct() {

		add_filter( 'better-framework/panel/add', [ $this, 'panel_add' ], 100 );

		add_filter( 'better-framework/panel/' . self::$option_panel_id . '/config', [ $this, 'panel_config' ], 100 );

		add_filter( 'better-framework/panel/' . self::$option_panel_id . '/fields', [ $this, 'panel_fields' ], 100 );

		add_filter( 'better-framework/panel/' . self::$option_panel_id . '/std', [ $this, 'panel_std' ], 100 );

		// Callback for resetting data
		add_filter( 'better-framework/panel/reset/result', [ $this, 'callback_panel_reset_result' ], 10, 2 );

		// Callback for importing data
		add_filter( 'better-framework/panel/import/result', [ $this, 'callback_panel_import_result' ], 10, 3 );

		// Callback changing save result
		add_filter( 'better-framework/panel/save/result', [ $this, 'callback_panel_save_result' ], 10, 2 );

		self::cache_modules();

		add_action( 'init', [ $this, 'icon_sprite_module' ] );
	}


	/**
	 * Setup icon booster module.
	 *
	 * @since 3.16.0
	 */
	public function icon_sprite_module(): void {

		if ( is_admin() || bf_is_block_render_request() ) {

			return;
		}

		if ( ! self::get_option( 'combine-whole-icons' ) ) {

			return;
		}

		add_action( 'wp_body_open', [ $this, 'body_open' ], 9 );
		add_action( 'wp_footer', [ $this, 'body_close' ], 990 );
	}


	/**
	 * Build the required object instance
	 *
	 * @param string $object
	 * @param bool   $fresh
	 *
	 * @return  null|Booster
	 */
	public static function factory( string $object = 'self', bool $fresh = false ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {
			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main BF_Booster Class
			 */
			case 'self':
				$class = __CLASS__;
				break;


			default:
				return null;
		}

		// don't cache fresh objects
		if ( $fresh ) {
			return new $class;
		}

		self::$instances[ $object ] = new $class;

		return self::$instances[ $object ];
	}


	/**
	 * Used for retrieving options simply and safely for next versions
	 *
	 * @param string $option_key
	 *
	 * @return mixed
	 */
	public static function get_option( string $option_key ) {

		return bf_get_option( $option_key, self::$option_panel_id );
	}

	/**
	 * Callback: Setup panel
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param array $panels
	 *
	 * @return array
	 */
	public function panel_add( array $panels ): array {

		$panels[ self::$option_panel_id ] = array(
			'id'    => self::$option_panel_id,
			'style' => false,
		);

		return $panels;
	}


	/**
	 * Callback: Init's BF options
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param array $panels
	 *
	 * @return array
	 */
	public function panel_config( array $panels ): array {

		include __DIR__ . '/panel-config.php';

		return $panels;
	}


	/**
	 * Callback: Init's BF options
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param array $fields
	 *
	 * @return mixed
	 */
	public function panel_fields( array $fields ): array {

		include __DIR__ . '/panel-fields.php';

		return $fields;
	}


	/**
	 * Callback: Init's BF options
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public function panel_std( array $fields ): array {

		include __DIR__ . '/panel-std.php';

		return $fields;
	}


	/**
	 * Filter callback: Used for resetting current language on resetting panel
	 *
	 * @param array $options
	 * @param array $result
	 *
	 * @return array
	 */
	function callback_panel_reset_result( array $result, array $options ): array {

		// check panel
		if ( $options['id'] !== self::$option_panel_id ) {

			return $result;
		}

		// change messages
		if ( $result['status'] === 'succeed' ) {

			$result['msg'] = __( 'BS Booster options reset to default.', 'better-studio' );

		} else {

			$result['msg'] = __( 'An error occurred while resetting BS Booster.', 'better-studio' );
		}

		return $result;
	}


	/**
	 * Filter callback: Used for changing current language on importing translation panel data
	 *
	 * @param array $result
	 * @param mixed $data
	 * @param array $args
	 *
	 * @return array
	 */
	function callback_panel_import_result( array $result, $data, array $args ): array {

		// check panel
		if ( $args['panel-id'] !== self::$option_panel_id ) {
			return $result;
		}

		// change messages
		if ( $result['status'] === 'succeed' ) {

			$result['msg'] = __( 'BS Booster options imported successfully.', 'better-studio' );

		} else if ( $result['msg'] === __( 'Imported data is not for this panel.', 'better-studio' ) ) {

			$result['msg'] = __( 'Imported translation is not for BS Booster.', 'better-studio' );

		} else {

			$result['msg'] = __( 'An error occurred while importing BS Booster options.', 'better-studio' );
		}

		return $result;
	}


	/**
	 * Filter callback: Used for changing save translation panel result
	 *
	 * @param array $output
	 * @param array $args
	 *
	 * @return string
	 */
	function callback_panel_save_result( array $output, array $args ): array {

		// change only for translation panel
		if ( $args['id'] !== self::$option_panel_id ) {

			return $output;
		}

		if ( $output['status'] === 'succeed' ) {

			$output['msg'] = __( 'Booster settings saved.', 'better-studio' );

		} else {

			$output['msg'] = __( 'An error occurred while saving booster!', 'better-studio' );
		}

		return $output;
	}


	public static function reset_cache_cb(): array {

		// remove all cached css and js files
		Minify::clear_cache( 'all' );

		// remove mega-menus and widgets cache
		BoosterCache::flush_cache();

		return [
			'status' => 'succeed',
			'msg'    => __( 'BS Booster cache cleared.', 'better-studio' ),
		];
	}


	/**
	 * Returns state of Booster sections
	 *
	 * @param string $section
	 *
	 * @return bool
	 */
	public static function is_active( string $section = '' ): bool {

		if ( empty( $section ) ) {

			return false;
		}

		static $state;

		if ( isset( $state ) ) {

			return $state[ $section ] ?? false;
		}

		$state = array(
			'minify-css' => self::get_option( 'minify' ),
			'minify-js'  => self::get_option( 'minify' ),
		);

		if ( is_admin() || bf_is( 'dev' ) ) {
			$state['minify-css'] = false;
			$state['minify-js']  = false;
		}

		$state = apply_filters( 'better-framework/booster/active', $state );

		return $state[ $section ] ?? false;
	}


	/**
	 * Include booster sub-modules
	 */
	public static function cache_modules(): void {

		MenuCache::Run();
		WidgetCache::Run();
		ShortcodeCache::Run();

		// Reset caches
		self::register_clear_cache_hooks();
	}


	/**
	 * Register hooks to clear widget and menu cache
	 */
	public static function register_clear_cache_hooks(): void {

		add_action( 'wp_update_nav_menu', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'delete_category', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'edit_category', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'add_category', [ BoosterCache::class, 'flush_cache' ] );

		add_action( 'delete_attachment', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'edit_attachment', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'untrashed_post', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'trashed_post', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'deleted_post', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'save_post', [ BoosterCache::class, 'flush_cache' ] );

		add_action( 'delete_term', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'edit_terms', [ BoosterCache::class, 'flush_cache' ] );

		add_action( 'wp_set_comment_status', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'untrashed_comment', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'unspammed_comment', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'deleted_comment', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'spammed_comment', [ BoosterCache::class, 'flush_cache' ] );

		add_action( 'upgrader_process_complete', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'switch_theme', [ BoosterCache::class, 'flush_cache' ] );

		add_action( 'deactivated_plugin', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'activated_plugin', [ BoosterCache::class, 'flush_cache' ] );

		add_action( 'better-framework/version-compatibility/checked', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'better-framework/template-compatibility/done', [ BoosterCache::class, 'flush_cache' ] );
		add_action( 'better-framework/panel/save', [ BoosterCache::class, 'flush_cache' ] );

		// WP rocket compatibility
		add_action( 'after_rocket_clean_cache_busting', [ BoosterCache::class, 'flush_cache' ] );

		// WP super cache compatibility
		add_action( 'pre_update_option_supercache_stats', [ self::class, 'wpsc_clean_cache' ] );

		// W3 Total Cache Compatibility
		add_action( 'w3tc_flush_all', [ BoosterCache::class, 'flush_cache' ] );

		// WP fastest cache compatibility
		add_action( 'wp_ajax_wpfc_delete_cache', [ self::class, 'wpfc_toolbar_clean_cache' ] );
		add_action( 'wp_ajax_wpfc_delete_current_page_cache', [ self::class, 'wpfc_toolbar_clean_cache' ] );
		add_action( 'wp_ajax_wpfc_delete_cache_and_minified', [ self::class, 'wpfc_toolbar_clean_cache' ] );
		//
		add_action( 'pre_option_WpFastestCachePreLoad', [ self::class, 'wpfc_admin_clean_cache' ] );
	}


	/**
	 * Clear booster cache when wp super cache delete.
	 *
	 * @return bool true on success
	 */
	public static function wpsc_clean_cache(): bool {

		if ( ! isset( $_POST['wp_delete_cache'] ) ) {

			return false;
		}

		if ( ! function_exists( 'wpsupercache_site_admin' ) || ! wpsupercache_site_admin() ) {

			return false;
		}

		if ( ! current_user_can( 'manage_options' ) ) {

			return false;
		}

		return BoosterCache::flush_cache();
	}


	/**
	 * Check user access whether is allow to clear wpfc plugin cache
	 *
	 * @return bool
	 */
	public static function can_user_clean_wpfc_cache(): bool {

		if ( ! is_user_logged_in() ) {

			return false;
		}

		$is_user_valid = current_user_can( 'manage_options' ) || current_user_can( 'edit_others_pages' );

		if ( ! $is_user_valid && defined( 'WPFC_TOOLBAR_FOR_AUTHOR' ) && WPFC_TOOLBAR_FOR_AUTHOR ) {
			$is_user_valid = current_user_can( 'delete_published_posts' ) || current_user_can( 'edit_published_posts' );
		}

		return $is_user_valid;
	}


	/**
	 * Clear booster cache when user clicked on WPFC => "delete cache" on admin toolbar
	 */
	public static function wpfc_toolbar_clean_cache(): void {

		if ( self::can_user_clean_wpfc_cache() ) {

			BoosterCache::flush_cache();
		}
	}


	/**
	 * Clear booster cache when user clicked on "delete cache" on WPFC admin panel
	 */
	public static function wpfc_admin_clean_cache(): void {

		if ( isset( $_POST['wpFastestCachePage'] ) && $_POST['wpFastestCachePage'] === 'deleteCache' ) {

			self::can_user_clean_wpfc_cache() && BoosterCache::flush_cache();
		}
	}


	/**
	 * @since 3.16.0
	 */
	public function body_open(): void {

		ob_start();
	}

	/**
	 * @since 3.16.0
	 */
	public function body_close(): void {

		$content = ob_get_clean();

		$converter = new IconConverter();
		$converter->load_html( $content );
		$converter->convert();

		echo $converter->output_html();
	}
}
