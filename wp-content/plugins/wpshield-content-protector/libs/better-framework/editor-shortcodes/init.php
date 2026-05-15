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

class BF_Editor_Shortcodes {

	/**
	 * Contains configuration's of shortcodes
	 *
	 * @var array
	 */
	public static $config = [];


	/**
	 * Contains alive instance of class
	 *
	 * @var  self
	 */
	protected static $instance;


	/**
	 * BF_Editor_Shortcodes instance
	 *
	 * @var BF_Editor_Shortcodes
	 */
	protected static $editor_instance;


	/**
	 * All registered shortcodes
	 *
	 * @var array
	 */
	private static $shortcodes = [];


	protected $stack;

	/**
	 * [ create and ] Returns life version
	 *
	 * @return \BF_Editor_Shortcodes
	 */
	public static function Run() {

		if ( ! self::$instance instanceof self ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}


	/**
	 * Handy function used to get custom value from config
	 *
	 * @param        $id
	 * @param string $default
	 *
	 * @return string
	 */
	public static function get_config( $id = null, $default = '' ) {

		if ( is_null( $id ) ) {
			return $default;
		}

		/**
		 * Calculates numbers of 2 column layout
		 */
		if ( $id === 'layout-2-col' ) {

			if ( ! isset( self::$config[ $id ] ) ) {
				return self::$config[ $id ] = [
					'width'      => 1180,
					'content'    => 790.6,
					'primary'    => 389.4,
					'calculated' => true,
				];
			}

			if ( isset( self::$config[ $id ]['calculated'] ) ) {
				return self::$config[ $id ];
			}

			self::$config[ $id ]['content'] = ( self::$config[ $id ]['content'] * 0.01 ) * self::$config[ $id ]['width'];
			self::$config[ $id ]['primary'] = ( self::$config[ $id ]['primary'] * 0.01 ) * self::$config[ $id ]['width'];

			self::$config[ $id ]['calculated'] = true;

			return self::$config[ $id ];
		}

		/**
		 * Calculates numbers of 3 column layout
		 */
		if ( $id === 'layout-3-col' ) {

			if ( ! isset( self::$config[ $id ] ) ) {
				return self::$config[ $id ] = [
					'width'      => 1300,
					'content'    => 754,
					'primary'    => 325,
					'secondary'  => 221,
					'calculated' => true,
				];
			}

			if ( isset( self::$config[ $id ]['calculated'] ) ) {
				return self::$config[ $id ];
			}

			self::$config[ $id ]['content']   = ( self::$config[ $id ]['content'] * 0.01 ) * self::$config[ $id ]['width'];
			self::$config[ $id ]['primary']   = ( self::$config[ $id ]['primary'] * 0.01 ) * self::$config[ $id ]['width'];
			self::$config[ $id ]['secondary'] = ( self::$config[ $id ]['secondary'] * 0.01 ) * self::$config[ $id ]['width'];

			self::$config[ $id ]['calculated'] = true;

			return self::$config[ $id ];
		}

		return isset( self::$config[ $id ] ) ? self::$config[ $id ] : $default;
	}


	/**
	 * @return array
	 */
	public static function get_shortcodes() {

		return self::$shortcodes;
	}


	/**
	 * @param array $shortcodes
	 */
	public static function set_shortcodes( $shortcodes ) {

		self::$shortcodes = $shortcodes;
	}


	/**
	 * Get BF url
	 *
	 * @param string $append optional.
	 *
	 * @return string
	 */
	public static function url( $append = '' ) {

		return bf_get_uri( 'editor-shortcodes/' . ltrim( $append, '/' ) );
	}


	/**
	 * Get library path
	 *
	 * @param string $append optional.
	 *
	 * @return string
	 */
	public static function path( $append = '' ) {

		return bf_get_dir( 'editor-shortcodes/' . ltrim( $append, '/' ) );
	}


	/**
	 * Register Hooks
	 */
	public function init() {

		add_action( 'better-framework/after_setup', [ $this, 'setup_shortcodes' ] );
	}


	/**
	 * Print dynamic editor css
	 */
	public function load_editor_css() {

		if ( isset( $_GET['bf-editor-shortcodes'] ) ) {

			@header( 'Content-Type: text/css; charset=UTF-8' );

			ob_start();

			// IF post ID was bigger than 0 == valid post
			if ( intval( $_GET['bf-editor-shortcodes'] ) > 0 ) {
				if ( ! empty( self::$config['editor-style'] ) ) {
					@include self::$config['editor-style'];
				} else {
					include self::path( '/assets/css/editor-style.php' );
				}

				// Injects dynamics generated CSS codes from PHP files outside of library
				if ( ! empty( self::$config['editor-dynamic-style'] ) ) {

					if ( is_array( self::$config['editor-dynamic-style'] ) ) {

						foreach ( self::$config['editor-dynamic-style'] as $_file ) {
							@include $_file;
						}
					} else {
						@include self::$config['editor-dynamic-style'];
					}
				}
			}

			$output = ob_get_clean();
			$fonts  = '';

			// Move all @import to the beginning of generated CSS
			{
				preg_match_all( '/@import .*/', $output, $matched );

				if ( ! empty( $matched[0] ) ) {
					foreach ( $matched[0] as $item ) {
						$fonts .= $item . "\n\n";

						$output = str_replace( $item, '', $output );
					}
				}
			}

			echo $fonts;
			echo $output;

			exit;
		}

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
		     in_array( $GLOBALS['pagenow'], [ 'post.php', 'post-new.php' ] ) ) {

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

		if ( get_option( 'classic-editor-allow-users' ) === 'allow' ) {

			$user_options = get_user_option( 'classic-editor-settings' );

			if ( $user_options === 'block' || $user_options === 'classic' ) {

				$default_editor = $user_options;
			}
		}

		return $default_editor;
	}


	/**
	 * Adds custom dynamic editor css
	 *
	 * @param array $stylesheets list of stylesheets uri
	 *
	 * @return array
	 */
	public function prepare_editor_style_uri( $stylesheets = [] ) {

		// Detect current active editor
		{
			$editor = 'tinymce';

			if ( self::is_gutenberg_active() ) {
				$editor = 'gutenberg';
			}
		}

		$url = home_url( '?bf-editor-shortcodes=' . bf_get_admin_current_post_id() . '&editor=' . $editor . '&cacheid=' . md5( json_encode( self::$config ) ) );
		$url = set_url_scheme( $url );

		// Add dynamic css file
		$stylesheets[] = $url;

		return $stylesheets;
	}


	/**
	 * Add custom editor script
	 */
	public function append_editor_script() {

		$js_prefix = ! bf_is( 'dev' ) ? '.min' : '';
		wp_enqueue_script(
			'bf-editor-script',
			$this->url( 'assets/js/edit-post-script' . $js_prefix . '.js' ),
			'',
			BF_VERSION
		);
	}


	/**
	 * Used for retrieving instance
	 *
	 * @param $fresh
	 *
	 * @return mixed
	 */
	public static function editor_instance( $fresh = false ) {

		if ( self::$editor_instance != null && ! $fresh ) {
			return self::$editor_instance;
		}

		return self::$editor_instance = new BF_Editor_Shortcodes_TinyMCE();
	}


	/**
	 *
	 */
	public function setup_shortcodes() {

		/**
		 * Retrieves configurations
		 *
		 * @param string $args reset panel data
		 *
		 * @since 1.0.0
		 *
		 */
		self::$config = apply_filters( 'better-framework/editor-shortcodes/config', self::$config );

		// injects all our custom styles to TinyMCE
		add_filter( 'editor_stylesheets', [ $this, 'prepare_editor_style_uri' ], 100 );

		// Register style for the Gutenberg
		add_action( 'enqueue_block_editor_assets', [ $this, 'gutenberg_styles' ] );

		// Prints dynamic custom css if needed
		add_action( 'template_redirect', [ $this, 'load_editor_css' ], 1 );
		add_action( 'admin_init', [ $this, 'load_editor_css' ], 1 );

		$this->load_all_shortcodes();

		// registers shortcodes
		add_action( 'init', [ $this, 'register_all_shortcodes' ], 50 );

		global $pagenow;
		// Initiate custom shortcodes only in post edit editor
		if ( is_admin() && ( bf_is_doing_ajax() || in_array( $pagenow, [ 'post-new.php', 'post.php' ] ) ) ) {
			add_action( 'load-post.php', [ $this, 'append_editor_script' ] );
			add_action( 'load-post-new.php', [ $this, 'append_editor_script' ] );

			self::editor_instance();
		}
	}


	/**
	 * Loads all active shortcodes
	 */
	public function load_all_shortcodes() {

		self::set_shortcodes( apply_filters( 'better-framework/editor-shortcodes/shortcodes-array', [] ) );

	}


	/**
	 * Register shortcode from nested array
	 *
	 * @param $shortcode_key
	 * @param $shortcode
	 */
	public function register_shortcode( $shortcode_key, $shortcode ) {

		// Menu
		if ( isset( $shortcode['type'] ) && $shortcode['type'] == 'menu' ) {

			foreach ( (array) $shortcode['items'] as $_shortcode_key => $_shortcode_value ) {

				$this->register_shortcode( $_shortcode_key, $_shortcode_value );

			}

			return;
		}

		// Do not register shortcode
		if ( isset( $shortcode['register'] ) && $shortcode['register'] === false ) {
			return;
		}

		/**
		 * FIXME: remove and test the add_shortcode
		 */
		// External callback
		// if ( isset( $shortcode['external-callback'] ) && $shortcode['external-callback'] ) {
		// call_user_func( 'add' . '_' . 'shortcode', $shortcode_key, $shortcode['external-callback'] );
		// } elseif ( isset( $shortcode['callback'] ) ) {
		// call_user_func( 'add' . '_' . 'shortcode', $shortcode_key, array( $this, $shortcode['callback'] ) );
		// }
	}


	/**
	 * Registers all active shortcodes
	 */
	public function register_all_shortcodes() {

		foreach ( (array) self::get_shortcodes() as $shortcode_key => $shortcode ) {

			$this->register_shortcode( $shortcode_key, $shortcode );

		}
	}


	/**
	 * Enqueue WordPress theme styles within Gutenberg
	 */
	function gutenberg_styles() {

		$list = $this->prepare_editor_style_uri();

		foreach ( $list as $k => $style ) {
			wp_enqueue_style( "bf-gutenberg-$k", $style, false, BF_VERSION, 'all' );
		}
	}


	/**
	 * Shortcode: Columns
	 */
	public function columns( $atts, $content = null ) {

		$atts = shortcode_atts( [ 'class' => '' ], $atts );

		$classes = [ 'row', 'bs-row-shortcode' ];

		if ( ! empty( $atts['class'] ) ) {
			$classes = array_merge( $classes, explode( ' ', $atts['class'] ) );
		}

		$output = '<div class="' . implode( ' ', $classes ) . '">';

		$this->stack['columns'] = [];

		// parse nested shortcodes and collect data
		do_shortcode( $content );

		foreach ( $this->stack['columns'] as $column ) {
			$output .= $column;
		}

		unset( $this->stack['columns'] );

		return $output . '</div>';
	}


	/**
	 * Shortcode Helper: Column
	 */
	public function column( $atts, $content = null ) {

		$atts = shortcode_atts( [
			'size'       => '1/1',
			'class'      => '',
			'text_align' => '',
		], $atts );

		$classes = [ 'column' ];

		if ( ! empty( $atts['class'] ) ) {
			$classes = array_merge( $classes, explode( ' ', $atts['class'] ) );
		}

		if ( ! empty( $atts['size'] ) && stristr( $atts['size'], '/' ) ) {

			$size = str_replace(
				[
					'1/1',
					'1/2',
					'1/3',
					'1/4',
				],
				[
					'col-lg-12',
					'col-lg-6',
					'col-lg-4',
					'col-lg-3',
				],
				$atts['size']
			);

		} else {
			$size = 'col-lg-6';
		}

		// Add size to column classes
		$classes[] = $size;

		// Add style such as text-align
		$style = '';
		if ( ! empty( $atts['text_align'] ) && in_array( $atts['text_align'], [ 'left', 'center', 'right' ] ) ) {

			$classes[] = esc_attr( strip_tags( $atts['text_align'] ) );
		}

		$this->stack['columns'][] = $column = '<div class="' . implode( ' ', $classes ) . '"' . $style . '>' . do_shortcode( $content ) . '</div>';

		return $column;
	}


	/**
	 * Shortcode: List
	 */
	public function list_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			[
				'style' => 'check',
				'class' => '',
			],
			$atts
		);

		$this->stack['list_style'] = $atts['style'] ?? '';

		// parse nested shortcodes and collect data
		$content = do_shortcode( $content );
		$content = preg_replace( '#^<\/p>|<div>|<\/div>|<p>$#', '', $content );
		$content = preg_replace( '#<\/li><br \/>#', '</li>', $content );
		// no list?
		if ( ! preg_match( '#<(ul|ol)[^<]*>#i', $content ) ) {

			$content = '<ul>' . $content . '</ul>'; // escaped before

		}

		$content = preg_replace( '#<ul><br \/>#', '<ul>', $content );

		return '<div class="bs-shortcode-list list-style-' . esc_attr( $atts['style'] ?? '' ) . ( $atts['class'] ?? '' ) . '">' . $content . '</div>';
	}


	/**
	 * Shortcode Helper: List item
	 */
	public function list_item( $atts, $content = null ) {

		$icon = bf_get_icon_tag( 'fa-' . $this->stack['list_style'] );

		return '<li>' . $icon . do_shortcode( $content ) . '</li>';

	}


	/**
	 * Shortcode: Button
	 */
	public function button( $atts, $content = null ) {

		$atts = bf_merge_args(
			$atts,
			[
				'style'      => 'default',
				'link'       => '#link',
				'size'       => 'medium',
				'target'     => '',
				'background' => '',
				'color'      => '',
			]
		);

		$atts['size'] = str_replace(
			[
				'large',
				'medium',
				'small',
			],
			[
				'lg',
				'sm',
				'xs',
			],
			$atts['size']
		);

		$style = '';

		if ( ! empty( $atts['background'] ) ) {
			$style .= 'background:#' . ltrim( $atts['background'], '#' ) . ' !important;';
		}

		if ( ! empty( $atts['color'] ) ) {
			$style .= 'color:#' . ltrim( $atts['color'], '#' ) . ' !important;';
		}

		return '<a class="btn btn-' . $atts['style'] . ' btn-' . $atts['size'] . ' btn-shortcode" href="' . $atts['link'] . '" target="' . $atts['target'] . '" ' . ( $style ? 'style="' . $style . '"' : '' ) . '>' . do_shortcode( $content ) . '</a>';
	}

}
