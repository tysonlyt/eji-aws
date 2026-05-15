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

use \BetterFrameworkPackage\Framework\Pro\{
	Booster
};

/**
 * Manage all shortcode element registration
 */
class BF_Shortcodes_Manager {


	/**
	 * Contain All shortcodes
	 *
	 * @var array
	 */
	public static $shortcodes = [];


	/**
	 * Instances of all BetterFramework active shortcode
	 *
	 * @var array
	 */
	private static $shortcode_instances = [];

	/**
	 * Store tinymce view shortcodes
	 *
	 * @var array
	 *
	 * @since 3.0.0
	 */
	static $tinymce_shortcodes = [];

	/**
	 * Store tinymce view shortcodes
	 *
	 * @var array
	 *
	 * @since 3.0.0
	 */
	static $tinymce_extra_enqueues = [];

	/**
	 * @var array
	 */
	static $tinymce_shortcode_info = [];


	function __construct() {

		// Filter active shortcodes
		self::load_shortcodes();

		// Initialize active shortcodes
		self::init_shortcodes();

		// Add widgets
		add_action( 'widgets_init', [ __CLASS__, 'load_widgets' ] );

		add_action( 'better-framework/shortcodes/tinymce-fields', [ __CLASS__, 'print_tinymce_fields' ], 11, 2 );
		add_action(
			'better-framework/shortcodes/tinymce-view-shortcode',
			[
				__CLASS__,
				'tinymce_view_bulk_shortcodes',
			],
			11,
			2
		);

		add_action( 'admin_footer', [ __CLASS__, 'enqueue_tinymce_add_on_scripts' ] );

		// Fire-up gutenberg middleware

		$this->gutenberg_compatibility();

		do_action( 'better-framework/shortcodes/loaded', $this );
	}


	/**
	 * Get active short codes from bf_active_shortcodes filter
	 */
	public static function load_shortcodes() {

		self::$shortcodes = apply_filters( 'better-framework/shortcodes', [] );

	}


	/**
	 * Initialize active shortcodes
	 *
	 * @param bool $instance
	 */
	public static function init_shortcodes( $instance = false ) {

		foreach ( self::$shortcodes as $key => $shortcode ) {

			self::factory( $key, $shortcode, $instance );
		}
	}

	/**
	 * @param string $base_widget_id
	 *
	 * @return bool
	 */
	public static function can_init_vc_shortcodes( $base_widget_id = '' ) {

		$result = false;

		if ( is_admin() ) {
			if ( bf_is_doing_ajax( 'vc_edit_form' ) || ! bf_is_doing_ajax() ) {
				$result = true;
			} elseif ( // is save-widget request
				! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'save-widget' &&
				! empty( $_REQUEST['widget-id'] ) && ! empty( $_REQUEST['id_base'] ) &&
				$_REQUEST['id_base'] === $base_widget_id
			) {
				$result = true;
			}
		} elseif ( bf_get_current_sidebar() ) { // fix for inside sidebar call od factory method
			$result = true;
		} elseif ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) { // Fix for vc inline editor
			$result = true;
		}

		return $result;
	}


	/**
	 * Check is a shortcode registered
	 *
	 * @param string $key
	 *
	 * @since 3.3.3
	 * @return bool
	 */
	public static function shortcode_exists( $key ) {

		return isset( self::$shortcodes[ $key ]['shortcode_class'] );
	}


	/**
	 * Factory For All BF Active Shortcodes
	 *
	 * @param string $key
	 * @param array  $options
	 * @param bool   $instance
	 *
	 * @return \BF_Shortcode|null
	 */
	public static function factory( $key = '', $options = [], $instance = false ) {

		if ( ! $key ) {
			return null;
		}

		$instance = $instance || self::can_init_vc_shortcodes( $key );

		// TODO: we cannot make more than one instance of each shortcode
		// we need it for creating instance with separate attribute
		if ( isset( self::$shortcode_instances[ $key ] ) ) {
			return self::$shortcode_instances[ $key ];
		}

		//
		// Short Code That Haves Specific Handler Out Side Of BF
		//
		if ( isset( self::$shortcodes[ $key ]['shortcode_class'] ) ) {

			// Create instance for shortcode class
			if ( $instance ) {

				$class = self::$shortcodes[ $key ]['shortcode_class'];

				self::$shortcode_instances[ $key ] = new $class( $key, self::$shortcodes[ $key ] );

				return self::$shortcode_instances[ $key ];
			}

			return null;
		}

		//
		// Active Shortcodes In Inner BF
		//
		$class = bf_convert_string_to_class_name( $key, 'BF_', '_Shortcode' );

		if ( ! class_exists( $class ) ) {
			if ( file_exists( bf_get_dir( 'shortcode/shortcodes/class-bf-' . $key . '-shortcode.php' ) ) ) {
				include 'shortcode/shortcodes/class-bf-' . $key . '-shortcode.php';
			} else {

				return null;
			}
		}

		self::$shortcode_instances[ $key ] = new $class( $key, $options );

		return self::$shortcode_instances[ $key ];
	}


	/**
	 * Handle shortcodes wrapper
	 *
	 * @param $atts
	 * @param $content
	 * @param $shortcode_id
	 *
	 * @return string
	 */
	public static function handle_shortcodes( $atts, $content, $shortcode_id ) {

		if ( isset( self::$shortcode_instances[ $shortcode_id ] ) ) {
			return self::$shortcode_instances[ $shortcode_id ]->handle_shortcode( $atts, $content );
		}

		// if this shortcode is not valid
		if ( empty( self::$shortcodes[ $shortcode_id ]['shortcode_class'] ) ) {
			return '';
		}

		$class = self::$shortcodes[ $shortcode_id ]['shortcode_class'];

		self::$shortcode_instances[ $shortcode_id ] = new $class( $shortcode_id, self::$shortcodes[ $shortcode_id ] );

		return self::$shortcode_instances[ $shortcode_id ]->handle_shortcode( $atts, $content );
	}


	/**
	 * Load widget for shortcode
	 */
	public static function load_widgets() {

		$support_block_widgets = function_exists( 'wp_use_widgets_block_editor' ) && wp_use_widgets_block_editor();
		$active_widgets        = self::active_widgets_list();

		foreach ( self::$shortcodes as $shortcode ) {

			if ( ! isset( $shortcode['widget_class'] ) || ! class_exists( $shortcode['widget_class'] ) || ! is_subclass_of( $shortcode['widget_class'], 'WP_Widget' ) ) {

				continue;
			}

			$widget_id_callback = sprintf( '%s::widget_id', $shortcode['widget_class'] );
			$widget_id          = is_callable( $widget_id_callback ) ? $widget_id_callback() : '';

			if ( ! $support_block_widgets || ! empty( $shortcode['widget_always'] ) || empty( $widget_id ) || in_array( $widget_id, $active_widgets, true ) ) {

				register_widget( $shortcode['widget_class'] );
			}
		}
	}


	/**
	 * @return array
	 */
	public static function active_widgets_list() {

		$sidebars_widgets = get_option( 'sidebars_widgets' );

		if ( ! $sidebars_widgets ) {

			return [];
		}

		$active = [];

		unset( $sidebars_widgets['wp_inactive_widgets'], $sidebars_widgets['array_version'] );

		foreach ( $sidebars_widgets as $widgets ) {

			foreach ( $widgets as $widget ) {

				if ( ! preg_match( '/^(.*?)\-\d$/', $widget, $match ) ) {

					continue;
				}

				$active[] = $match[1];
			}
		}

		return array_unique( $active );
	}

	/**
	 * @param string $shortcode
	 * @param array  $settings
	 */
	public static function register_tinymce_addon( $shortcode, $settings = [] ) {

		self::$tinymce_shortcodes[] = $shortcode;
	}


	public static function enqueue_tinymce_add_on_scripts() {

		global $post;

		if ( self::$tinymce_shortcodes ) {

			if ( empty( $post->ID ) || get_post_meta( $post->ID, '_wpb_vc_js_status', true ) !== 'true' ) {

				$shortcodes = [];

				foreach ( self::$tinymce_shortcodes as $shortcode ) {

					if ( isset( self::$shortcode_instances[ $shortcode ] ) ) {

						$settings = self::$shortcode_instances[ $shortcode ]->tinymce_settings();

						$shortcodes[] = compact( 'shortcode', 'settings' );
					}
				}

				if ( version_compare( '5.3', PHP_VERSION, '>' ) ) {
					$doshortcode_steps = 3;
				} elseif ( version_compare( '5.4', PHP_VERSION, '>' ) ) {
					$doshortcode_steps = 4;
				} elseif ( version_compare( '5.5', PHP_VERSION, '>' ) ) {
					$doshortcode_steps = 5;
				} elseif ( version_compare( '7.0', PHP_VERSION, '<=' ) ) {
					$doshortcode_steps = 10;
				} elseif ( version_compare( '5.5', PHP_VERSION, '<=' ) ) {
					$doshortcode_steps = 8;
				} else {
					$doshortcode_steps = 3;
				}

				bf_enqueue_script( 'tinymce-addon' );
				bf_localize_script(
					'tinymce-addon',
					'BF_TinyMCE_View',
					[
						'shortcodes'        => $shortcodes,
						'l10n'              => [
							'modal' => [
								'header' => __( '%shortcode% Settings', 'better-studio' ),
							],
						],
						'doshortcode_steps' => $doshortcode_steps,
					]
				);
			}
		}
	}


	public static function print_tinymce_fields( $main_shortcode, $args ) {

		$instance = self::factory( $main_shortcode, [], true );

		if ( ! $instance || ! $instance->have_tinymce_add_on ) {
			wp_send_json_error();
		}

		$item_values = empty( $args['shortcode_values'] ) ? [] : wp_unslash( $args['shortcode_values'] );

		/**
		 * Fill $item_values with sub-shortcodes information to enable user to edit them
		 */
		$shortcode_settings = $instance->tinymce_settings();

		if ( ! empty( $item_values['innercontent'] ) && ! empty( $shortcode_settings['sub_shortcodes'] ) ) {

			$item_values['innercontent'] = wp_unslash( $item_values['innercontent'] );

			$fields = [];
			foreach ( $instance->get_fields() as $field ) {
				$fields[ $field['id'] ] = $field;
			}

			// Parse shortcodes in inner-content
			if ( preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $item_values['innercontent'], $matches ) ) {
				global $shortcode_tags;

				$shortcode_tags_org = $shortcode_tags;
				$shortcodes_list    = array_unique( $matches[1] ); // list of shortcodes exits in the content
				foreach ( $shortcodes_list as $_shortcode ) {
					//phpcs:ignore
					$shortcode_tags[ $_shortcode ] = array( __CLASS__, 'collect_shortcode_info' );
				}
				$shortcodes_list = null;

				$info = &self::$tinymce_shortcode_info;
				$info = [];
				do_shortcode( $item_values['innercontent'] );
				//phpcs:ignore
				$shortcode_tags = $shortcode_tags_org;

				$shortcode_content_keys = [];

				/**
				 * Append found data to $item_values
				 *
				 * @see BF_Shortcode::tinymce_settings for more information
				 */
				foreach ( $shortcode_settings['sub_shortcodes'] as $field_key => $_shortcode ) {
					if ( empty( $info[ $_shortcode ] ) ) {
						continue;
					}

					if ( ! isset( $shortcode_content_keys[ $field_key ] ) ) {

						if ( ! empty( $fields[ $field_key ]['options'] ) ) {

							foreach ( $fields[ $field_key ]['options'] as $_option ) {
								if ( ! empty( $_option['shortcode_content'] ) ) {
									$shortcode_content_keys[ $field_key ] = $_option['id'];
									break;
								}
							}
						}
					}

					foreach ( $info[ $_shortcode ] as $_shortcode_info ) {
						// Single repeater item value
						$repeater_item_values = $_shortcode_info['atts'];
						if ( ! empty( $shortcode_content_keys[ $field_key ] ) ) {
							$repeater_item_values[ $shortcode_content_keys[ $field_key ] ] = $_shortcode_info['content'];
						}

						$item_values[ $field_key ][] = $repeater_item_values;
					}
				}
			}
		} elseif ( empty( $shortcode_settings['sub_shortcodes'] ) && isset( $item_values['innercontent'] ) ) {

			$item_values['_content']     = $item_values['innercontent'];
			$item_values['innercontent'] = '';

		}

		$items = [
			'fields' => $instance->get_fields(),
		];

		ob_start();
		$generator = new BF_Simple_Field_Generator( $items, $main_shortcode, $item_values );
		$generator->output();

		wp_send_json_success(
			[
				'output'   => ob_get_clean(),
				'settings' => [
					'shortcode_content_fields' => $generator->shortcode_content_fields,
				],
			]
		);
	}


	public static function collect_shortcode_info( $atts, $content, $shortcode ) {

		self::$tinymce_shortcode_info[ $shortcode ][] = compact( 'atts', 'content' );
	}


	/**
	 * @param array $shortcodes
	 * @param array $args
	 */
	public static function tinymce_view_bulk_shortcodes( $shortcodes, $args ) {

		if ( empty( $args['post_id'] ) || ! is_array( $shortcodes ) ) {
			wp_send_json_error();
		}

		ob_start(); // Some stupid third-party plugins print stuff in `wp_enqueue_scripts` hook
		do_action( 'wp_enqueue_scripts' );
		ob_end_clean();

		$output = [];

		foreach ( $shortcodes as $shortcode ) {
			if ( isset( $shortcode['shortcode'] ) && isset( $shortcode['id'] ) ) {
				$output [ $shortcode['id'] ] = self::tinymce_view_shortcode( $shortcode['shortcode'], $args['post_id'] );
			}
		}

		wp_send_json_success( $output );
	}


	public static function tinymce_view_shortcode( $shortcode, $post_id = 0 ) {

		global $post;

		if ( preg_match( '#^\s*\[\s*([^\s\]]+)#', $shortcode, $match ) ) {
			$shortcode_name = $match[1];
		} else {
			$shortcode_name = $shortcode;
		}

		$instance = self::factory( $shortcode_name, [], true );

		if ( ! $instance ) {
			return [];
		}

		if ( $post_id ) {
			$post = get_post( (int) $post_id );
		}

		if ( $post && current_user_can( 'edit_post', $post->ID ) ) {
			setup_postdata( $post );
		}

		$parsed = do_shortcode( wpautop( $shortcode ) );

		if ( empty( $parsed ) ) {
			return [
				'type'    => 'no-items',
				'message' => __( 'No items found.', 'better-studio' ),
			];
		}

		$s = $instance->tinymce_settings();

		if ( isset( self::$tinymce_extra_enqueues['styles'] ) ) {

			if ( ! isset( $s['styles'] ) ) {
				$s['styles'] = [];
			}

			$s['styles'] = array_merge( self::$tinymce_extra_enqueues['styles'], $s['styles'] );
		}
		if ( isset( self::$tinymce_extra_enqueues['scripts'] ) ) {

			if ( ! isset( $s['scripts'] ) ) {
				$s['scripts'] = [];
			}

			$s['scripts'] = array_merge( self::$tinymce_extra_enqueues['scripts'], $s['scripts'] );
		}

		//
		// Handle Scripts
		//
		$scripts_output = '';

		if ( ! empty( $s['scripts'] ) ) {

			if ( bf_booster_is_active( 'minify-js' ) ) {
				bf_scripts()->done = [];
			} else {
				wp_scripts()->done = [];
			}

			ob_start();

			$inline_scripts = '';
			foreach ( $s['scripts'] as $idx => $script ) {

				if ( 'inline' === $script['type'] ) {
					$inline_scripts .= $script['data'];
					$inline_scripts .= "\n";
				} elseif ( 'custom' === $script['type'] ) {
					bf_scripts()->print_script( $script['url'], 'custom-script-' . $idx );
				} elseif ( ! empty( $script['handles'] ) ) {
					bf_print_scripts( (array) $script['handles'] );
				}
			}
			$scripts_output .= ob_get_contents();
			if ( $inline_scripts ) {
				$scripts_output .= ' <script> ' . $inline_scripts . ' </script> ';
			}
			$inline_scripts = null;

			ob_end_clean();
		}

		//
		// Handle Styles
		//
		$styles_output = '';

		if ( ! empty( $s['styles'] ) ) {

			if ( bf_booster_is_active( 'minify-css' ) ) {
				bf_styles()->done = [];
			} else {
				wp_styles()->done = [];
			}

			ob_start();

			$print_styles  = is_callable( \BetterFrameworkPackage\Framework\Pro\Booster\Styles::class . '::print_style' );
			$inline_styles = '';
			foreach ( $s['styles'] as $idx => $style ) {

				if ( 'inline' === $style['type'] ) {
					$inline_styles .= $style['data'];
					$inline_styles .= "\n";
				} elseif ( 'custom' === $style['type'] ) {
					bf_styles()->print_style( $style['url'], 'custom-stylesheet-' . $idx );
				} elseif ( 'extra' === $style['type'] ) {

					foreach ( $style['handles'] as $id ) {

						$css = bf_styles()->get_extra_css( $id );

						if ( $css ) {

							if ( 'file' === $css['type'] ) {
								$print_styles && \BetterFrameworkPackage\Framework\Pro\Booster\Styles::print_style( $css['data'], $id );
							} elseif ( 'inline' === $css['type'] ) {
								$inline_styles .= $css['data'];
								$inline_styles .= "\n";
							}
						}
					}
				} elseif ( ! empty( $style['handles'] ) ) {
					bf_print_styles( $style['handles'] );
				}
			}

			$styles_output .= ob_get_contents();

			if ( $inline_styles ) {
				$styles_output .= ' <style> ' . $inline_styles . ' </style> ';
			}
			$inline_styles = null;

			ob_end_clean();
		}

		// FIX: support for external stylesheet
		if ( stristr( $styles_output, '<link ' ) && ! $scripts_output ) {
			$scripts_output .= '<script></script>';
		}

		return [
			'head' => $styles_output,
			'body' => $parsed . $scripts_output,
		];
	}


	/**
	 * @return array
	 */
	public static function shortcodes_list() {

		return self::$shortcodes;
	}


	/**
	 * Fire-up gutenberg compatibility classes.
	 *
	 * @since 3.9.1
	 */
	public function gutenberg_compatibility() {

		global $pagenow;

		$is_valid_page = in_array( $pagenow, [ 'post.php', 'post-new.php', 'widgets.php', 'customize.php' ], true );

		if ( ! $is_valid_page && is_admin() && ! bf_is_block_render_request() && ! bf_is_doing_ajax( 'bf_ajax' ) ) {
			return;
		}

		BF_Gutenberg_Shortcode_Wrapper::instance();

		add_action( 'init', [ $this, 'gutenberg_register_block' ] );

	}


	public function gutenberg_register_block(): bool {

		if ( ! Better_Framework()->fully_load() && ! Better_Framework()->sections['page-builder'] ) {

			return false;
		}

		foreach ( self::$shortcodes as $shortcode => $_ ) {

			BF_Gutenberg_Shortcode_Wrapper::register_block( $shortcode );
		}

		return true;
	}
}
