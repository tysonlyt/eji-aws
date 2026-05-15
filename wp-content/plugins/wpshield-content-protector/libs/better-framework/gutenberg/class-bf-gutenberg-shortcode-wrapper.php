<?php

class BF_Gutenberg_Shortcode_Wrapper {


	/**
	 * Store list of blocks for gutenberg.
	 *
	 * @var array
	 *
	 * @since 3.9.0
	 */
	protected static $gutenberg_blocks = [];


	/**
	 * Cache storage for gutenberg_blocks_attributes method
	 *
	 * @var array
	 */
	protected static $blocks_attributes = [];


	/**
	 * Cache storage for gutenberg_blocks_attributes method
	 *
	 * @var array
	 */
	protected static $additional_attributes = [];


	/**
	 * @since 3.9.1
	 * @return self
	 */
	public static function instance() {

		$instance = new self();
		$instance->init();

		return $instance;
	}


	/**
	 * Initialize
	 */
	public function init() {
		global $wp_version;

		//
		// Gutenberg Compatibility
		//
		//
		// add_action( 'init', array( $this, 'register_gutenberg_blocks' ) );

		add_action( 'admin_footer', [ $this, 'enqueue_gutenberg_scripts' ] );
		add_action( 'customize_controls_print_footer_scripts', [ $this, 'enqueue_gutenberg_scripts' ] );

		if ( version_compare( $wp_version, '5.8-alpha', '>=' ) ) {

			add_filter( 'block_categories_all', [ $this, 'register_custom_categories' ] );
		} else {

			add_filter( 'block_categories', [ $this, 'register_custom_categories' ] );
		}

		// add_action( 'better-framework/fields/custom', array( $this, 'render_custom_field' ) );

		$this->early_load_all_shortcodes();

	}


	/**
	 * Enqueue gutenberg static dependencies.
	 *
	 * @since 3.9.0
	 * @return bool true when enqueue fired.
	 */
	public function enqueue_gutenberg_scripts(): bool {

		if ( ! $this->is_gutenberg_active() ) {
			return false;
		}

		if ( function_exists( 'is_gutenberg_page' ) && ! is_gutenberg_page() ) {
			return false;
		}

		bf_enqueue_script( 'bf-gutenberg' );

		bf_localize_script(
			'bf-gutenberg',
			'BF_Gutenberg',
			[

				'shortcodes'       => self::$gutenberg_blocks,
				'shortcodesFields' => self::gutenberg_blocks_fields(),
				'stickyFields'     => self::gutenberg_sticky_fields(),
				'extraAttributes'  => self::$additional_attributes,
			]
		);

		return true;
	}


	/**
	 * Is gutenberg active?
	 *
	 * @since 3.9.0
	 * @return bool
	 */
	public static function is_gutenberg_active() {

		if ( ! is_admin() ||
			 bf_is_doing_ajax( 'bf_ajax' ) ||
			 bf_is_block_render_request() ||
			 in_array( $GLOBALS['pagenow'], [ 'post.php', 'post-new.php', 'widgets.php', 'customize.php' ], true ) ) {

			if ( function_exists( 'disable_gutenberg' ) ) {

				return ! disable_gutenberg();
			}

			if ( class_exists( 'Classic_Editor' ) ) {

				return self::active_editor() === 'block';
			}

			return function_exists( 'register_block_type' );
		}

		return false;
	}


	/**
	 * Check default active editor in 'Classic Editor' plugin.
	 *
	 * @since 3.10.6
	 * @return string
	 */
	protected static function active_editor() {

		if ( ! class_exists( 'Classic_Editor' ) ) {
			return '';
		}

		$status = bf_call_static_method( 'Classic_Editor', 'is_classic' );

		if ( is_bool( $status ) ) {

			return $status ? 'classic' : 'block';
		}

		$default_editor = get_option( 'classic-editor-replace' );

		if ( 'allow' === get_option( 'classic-editor-allow-users' ) ) {

			$user_options = get_user_option( 'classic-editor-settings' );

			if ( 'block' === $user_options || 'classic' === $user_options ) {

				$default_editor = $user_options;
			}
		}

		return $default_editor;
	}


	/**
	 * @param string $shortcode
	 * @param array  $settings
	 */
	public static function register( $shortcode, $settings = [] ) {

		$settings['id'] = $shortcode;

		if ( isset( $settings['category'] ) ) {
			$settings['category'] = self::sanitize_gutenberg_category( $settings['category'] );
		}

		if ( ! isset( $settings['icon_url'] ) ) {

			$settings['icon_url'] = BF_URI . 'assets/img/pixel.png';
		}

		self::$gutenberg_blocks[ $shortcode ] = $settings;
	}


	/**
	 * @return array
	 */
	public static function gutenberg_blocks_fields() {

		return BF_Gutenberg_Fields_Transformer::instance()->prepare_blocks_fields(
			array_keys( self::$gutenberg_blocks )
		);
	}

	public static function the_sticky_fields() {

		$fields = $stds = [];

		apply_filters_ref_array( 'better-framework/gutenberg/sticky-fields', [ &$fields ] );
		apply_filters_ref_array( 'better-framework/gutenberg/sticky-stds', [ &$stds ] );

		return [ $fields, $stds ];
	}


	/**
	 * @return array
	 */
	public static function gutenberg_sticky_fields() {

		list( $fields, $stds ) = self::the_sticky_fields();

		if ( empty( $fields ) ) {
			return [];
		}

		$converter = new BF_Fields_To_Gutenberg(
			$fields,
			$stds
		);

		self::$additional_attributes = array_merge(
			$converter->list_attributes(),
			self::$additional_attributes
		);

		return $converter->transform();
	}


	/**
	 * @return array
	 */
	public static function gutenberg_blocks_attributes() {

		$transformer = BF_Gutenberg_Fields_Transformer::instance();

		if ( bf_is( 'dev' ) ) { // Don't use cache in development

			return $transformer->prepare_blocks_attributes(
				array_keys( self::$gutenberg_blocks )
			);
		}

		$blocks_attributes = [];
		$expiration        = HOUR_IN_SECONDS * 6;

		foreach ( self::$gutenberg_blocks as $block_id => $options ) {

			$version   = $options['_version'] ?? '1';
			$cache_key = sprintf( '%s_%s', $block_id, $version );

			$attributes          = bf_cache_get( $cache_key, 'bf-block-attributes' );
			$should_update_cache = false === $attributes;

			if ( $should_update_cache ) {

				$attributes = $transformer->block_attributes( $block_id );
			}

			$blocks_attributes[ $block_id ] = $attributes;
			$should_update_cache && bf_cache_set( $cache_key, $attributes, 'bf-block-attributes', $expiration );

		}

		return $blocks_attributes;
	}

	/**
	 * @param string $shortcode the shortcode unique ID
	 */
	public static function register_block( $shortcode ) {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$block_id = str_replace( '_', '-', $shortcode );
		$block_id = strtolower( $block_id ); // uppercase letters are not allowed

		if ( ! self::$blocks_attributes ) {

			self::$blocks_attributes = self::gutenberg_blocks_attributes();
		}

		$render_callback = "BF_Gutenberg_Shortcode_Render::$shortcode";
		$attributes      = self::$blocks_attributes[ $shortcode ] ?? [];

		if ( ! empty( $attributes ) ) {
			$attributes['className'] = [ 'type' => 'string' ];
		}

		$attributes['_updated'] = [ 'type' => 'integer' ];

		$args = array_filter( compact( 'render_callback', 'attributes' ) );

		register_block_type( "better-studio/$block_id", apply_filters( 'better-framework/gutenberg/block-type-args', $args, $shortcode ) );
	}


	/**
	 * @param array $categories
	 *
	 * @return array
	 */
	public function register_custom_categories( $categories ) {

		$pushed_categories = [];

		foreach ( $categories as $category ) {
			$pushed_categories[] = $category['slug'];
		}

		foreach ( self::$gutenberg_blocks as $block ) {

			if ( empty( $block['category'] ) ) {
				continue;
			}

			$slug = self::sanitize_gutenberg_category( $block['category'] );

			if ( ! in_array( $slug, $pushed_categories, true ) ) {

				$categories[]        = [
					'title'     => $block['category'],
					'slug'      => $slug,
					'_priority' => 2,
				];
				$pushed_categories[] = $slug;
			}
		}

		// Register default category
		if ( ! in_array( 'better-studio', $pushed_categories, true ) ) {

			$categories[] = [
				'slug'  => 'better-studio',
				'title' => __( 'BetterStudio', 'better-studio' ),
			];
		}

		usort( $categories, [ $this, 'sort_array' ] );

		return $categories;
	}


	protected function sort_array( $a, $b ) {

		$priority_a = $a['_priority'] ?? 10;
		$priority_b = $b['_priority'] ?? 10;

		if ( $priority_a === $priority_b ) {

			return 0;
		}

		return $priority_a > $priority_b ? 1 : - 1;
	}


	/**
	 * @param string $category
	 *
	 * @return string
	 */
	public static function sanitize_gutenberg_category( $category ) {

		$slug = sanitize_title_with_dashes( trim( $category ) );

		if ( 'embeds' === $slug ) {

			return 'embed';
		}

		return $slug;
	}

	public function early_load_all_shortcodes() {

		if ( bf_is_block_render_request() ) {

			BF_Shortcodes_Manager::init_shortcodes( true );
		}
	}
}
