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

use \BetterFrameworkPackage\Component\Control\Typography\{
	Helpers,
	old as TypographyClasses
};

/**
 * Better Framework Font Manager
 *
 * @see      http://www.betterstudio.com
 * @author   BetterStudio <info@betterstudio.com>
 * @access   public
 * @package  BetterFramework
 */
class BF_Fonts_Manager {


	/**
	 * Panel ID
	 *
	 * @var string
	 */
	public $option_panel_id = 'better-framework-custom-fonts';


	/**
	 * Inner array of object instances and caches
	 *
	 * @var array
	 */
	protected static $instances = [];


	/**
	 *
	 */
	function __construct() {

		add_filter(
			'better-framework/panel/add',
			[
				$this,
				'panel_add',
			],
			100
		);

		add_filter(
			'better-framework/panel/' . $this->option_panel_id . '/config',
			[
				$this,
				'panel_config',
			],
			100
		);

		add_filter(
			'better-framework/panel/' . $this->option_panel_id . '/fields',
			[
				$this,
				'panel_fields',
			],
			100
		);

		add_filter(
			'better-framework/panel/' . $this->option_panel_id . '/std',
			[
				$this,
				'panel_std',
			],
			100
		);

		// Callback for resetting data
		add_filter( 'better-framework/panel/reset/result', [ $this, 'callback_panel_reset_result' ], 10, 2 );

		// Callback for importing data
		add_filter( 'better-framework/panel/import/result', [ $this, 'callback_panel_import_result' ], 10, 3 );

		// Callback changing save result
		add_filter( 'better-framework/panel/save/result', [ $this, 'callback_panel_save_result' ], 10, 2 );

		// Adds fonts file types to WP uploader
		if ( is_admin() ) {
			add_filter( 'upload_mimes', [ $this, 'filter_upload_mimes_types' ], 1000 );
		}

		// Output custom css for custom fonts
		add_action( 'template_redirect', [ $this, 'admin_custom_css' ], 1 );

		// Prints TypeKit font head js code
		add_action( 'wp_head', 'BF_Fonts_Manager::print_typekit_head_code' );
	}


	public static function ajax_add_custom_font( $data ) {

		$POST = [];
		wp_parse_str( urldecode( $data ), $POST );

		if ( ! isset( $POST['fonts'] ) || ! isset( $POST['font-name'] ) ) {
			return false;
		}

		$valid_keys = [
			'woff'  => '',
			'woff2' => '',
			'ttf'   => '',
			'svg'   => '',
			'eot'   => '',
			'otf'   => '',
		];
		$font       = array_intersect_key( $POST['fonts'], $valid_keys ); // Filter sent data
		$font['id'] = sanitize_text_field( $POST['font-name'] );          // Font name

		$instance = BF_Fonts_Manager::factory();

		// Update options
		$options = get_option( $instance->option_panel_id );
		if ( ! isset( $options['custom_fonts'] ) ) {
			$options['custom_fonts'] = [];
		}
		array_push( $options['custom_fonts'], $font );
		$success = update_option( $instance->option_panel_id, $options );

		// clear cache
		BF_Options::$values[ $instance->option_panel_id ] = null;

		$new_font_id = $font['id'];

		return compact( 'success', 'new_font_id' );
	}


	/**
	 * Build the required object instance
	 *
	 * @param string $object
	 * @param bool   $fresh
	 * @param bool   $just_include
	 *
	 * @return  null|BF_Fonts_Manager
	 */
	public static function factory( $object = 'self', $fresh = false, $just_include = false ) {

		if ( isset( self::$instances[ $object ] ) && ! $fresh ) {
			return self::$instances[ $object ];
		}

		switch ( $object ) {

			/**
			 * Main BF_Fonts_Manager Class
			 */
			case 'self':
				$class = 'BF_Fonts_Manager';
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

	/**
	 * Used for getting protocol for links of google fonts
	 *
	 * @param string $protocol custom protocol for using outside
	 *
	 * @return string
	 */
	public function get_protocol( $protocol = '' ) {

		if ( empty( $protocol ) ) {
			$protocol = $this->get_option( 'google_fonts_protocol' );
		}

		switch ( $protocol ) {

			case 'http':
				$protocol = 'http://';
				break;

			case 'https':
				$protocol = 'https://';
				break;

			case 'relative':
				$protocol = '//';
				break;

			default:
				$protocol = 'https://';

		}

		return $protocol;
	}


	/**
	 * Used for retrieving options simply and safely for next versions
	 *
	 * @param $option_key
	 *
	 * @return mixed|null
	 */
	public function get_option( $option_key ) {

		return bf_get_option( $option_key, $this->option_panel_id );
	}


	/**
	 * Callback: Output Custom CSS for Custom Fonts
	 *
	 * Filter: template_redirect
	 */
	public function admin_custom_css() {

		// just when custom css requested
		if ( empty( $_GET['better_fonts_manager_custom_css'] ) or intval( $_GET['better_fonts_manager_custom_css'] ) != 1 ) {
			return;
		}

		// Custom font requested
		if ( ! empty( $_GET['custom_font_id'] ) ) {

			$font_id      = $_GET['custom_font_id'];
			$custom_fonts = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper::get_all_fonts();

		}// Google EA Font
		elseif ( ! empty( $_GET['google_ea_font_id'] ) ) {

			$font_id = $_GET['google_ea_font_id'];

			$custom_fonts = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper::get_font( $font_id );

			if ( $custom_fonts ) {

				// Send output with import
				status_header( 200 );
				header( 'Content-type: text/css; charset: utf-8' );
				die( '@import url(' . $custom_fonts['url'] . ');' );
			}
		} // Theme font requested
		elseif ( ! empty( $_GET['theme_font_id'] ) ) {
			$font_id = $_GET['theme_font_id'];

			$custom_fonts = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper::get_all_fonts();
		} else {
			die;
		}

		// If custom font is not valid
		if ( ! isset( $custom_fonts[ $font_id ] ) ) {
			return;
		}

		status_header( 200 );
		header( 'Content-type: text/css; charset: utf-8' );

		$font = $custom_fonts[ $font_id ];
		$src  = [
			'main'  => [],
			'extra' => [],
		];

		$output = " @font-face { font-family: '" . $font_id . "';";

		// .EOT
		if ( ! empty( $font['eot'] ) ) {
			$src['extra'][] = "url('" . $font['eot'] . "')";
			$src['extra'][] = "url('" . $font['eot'] . "?#iefix') format('embedded-opentype')";
		}

		// .WOFF2
		if ( ! empty( $font['woff2'] ) ) {
			$src['main'][] = "url('" . $font['woff2'] . "') format('woff2')";
		}

		// .WOFF
		if ( ! empty( $font['woff'] ) ) {
			$src['main'][] = "url('" . $font['woff'] . "') format('woff')";
		}

		// .TTF
		if ( ! empty( $font['ttf'] ) ) {
			$src['main'][] = "url('" . $font['ttf'] . "') format('truetype')";
		}

		// .SVG
		if ( ! empty( $font['svg'] ) ) {
			$src['main'][] = "url('" . $font['svg'] . '#' . $font_id . "') format('svg')";
		}

		// .OTF
		if ( ! empty( $font['otf'] ) ) {
			$src['main'][] = "url('" . $font['otf'] . '#' . $font_id . "') format('opentype')";
		}

		//
		// Generate SRC attrs
		//
		{
		if ( ! empty( $src['extra'] ) ) {
			foreach ( $src['extra'] as $_src ) {
				$output .= "src: $_src;";
			}
		}

		if ( ! empty( $src['main'] ) ) {
			$output .= 'src: ' . implode( ",\n", $src['main'] ) . ';';
		}
		}

		$output .= '
    font-weight: normal;
    font-style: normal;
}';

		echo $output; // escaped before
		exit;
	}


	/**
	 * Used for getting font when we do not know what type is the font!
	 *
	 * Priority:
	 *  1. Theme Fonts
	 *  2. Custom Fonts
	 *  3. Font Stacks
	 *  4. Google fonts
	 *
	 * @param string $font_name Font ID
	 *
	 * @return bool
	 */
	public static function get_font( $font_name ) {

		return \BetterFrameworkPackage\Component\Control\Typography\Helpers::font( $font_name );
	}

	/**
	 * Callback: Used for adding fonts mimes to WordPress uploader
	 *
	 * Filter: upload_mimes
	 *
	 * @param $mimes
	 *
	 * @return mixed
	 */
	function filter_upload_mimes_types( $mimes ) {

		$mimes['ttf']   = 'application/x-font-ttf';
		$mimes['woff']  = 'application/x-font-woff';
		$mimes['woff2'] = 'application/x-font-woff2';
		$mimes['svg']   = 'image/svg+xml';
		$mimes['eot']   = 'application/vnd.ms-fontobject';
		$mimes['otf']   = 'application/x-font-otf';

		return $mimes;
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
	function panel_add( $panels ) {

		$panels[ $this->option_panel_id ] = [
			'id'    => $this->option_panel_id,
			'style' => false,

		];

		return $panels;
	}


	/**
	 * Callback: Init's BF options
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param $panels
	 *
	 * @return mixed
	 */
	public function panel_config( $panels ) {

		include BF_PATH . 'core-deprecated/fonts-manager/panel-config.php';

		return $panels;
	}


	/**
	 * Callback: Init's BF options
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function panel_fields( $fields ) {

		include BF_PATH . 'core-deprecated/fonts-manager/panel-fields.php';

		return $fields;
	}


	/**
	 * Callback: Init's BF options
	 *
	 * Filter: better-framework/panel/options
	 *
	 * @param $fields
	 *
	 * @return mixed
	 */
	public function panel_std( $fields ) {

		include BF_PATH . 'core-deprecated/fonts-manager/panel-std.php';

		return $fields;
	}


	/**
	 * Filter callback: Used for resetting current language on resetting panel
	 *
	 * @param   $options
	 * @param   $result
	 *
	 * @return array
	 */
	function callback_panel_reset_result( $result, $options ) {

		// check panel
		if ( $options['id'] != $this->option_panel_id ) {
			return $result;
		}

		// change messages
		if ( $result['status'] == 'succeed' ) {
			$result['msg'] = __( 'Font manager reset to default.', 'better-studio' );
		} else {
			$result['msg'] = __( 'An error occurred while resetting font manager.', 'better-studio' );
		}

		return $result;
	}


	/**
	 * Filter callback: Used for changing current language on importing translation panel data
	 *
	 * @param $result
	 * @param $data
	 * @param $args
	 *
	 * @return array
	 */
	function callback_panel_import_result( $result, $data, $args ) {

		// check panel
		if ( $args['panel-id'] != $this->option_panel_id ) {
			return $result;
		}

		// change messages
		if ( $result['status'] == 'succeed' ) {
			$result['msg'] = __( 'Font manager options imported successfully.', 'better-studio' );
		} else {
			if ( $result['msg'] == __( 'Imported data is not for this panel.', 'better-studio' ) ) {
				$result['msg'] = __( 'Imported translation is not for fonts manager.', 'better-studio' );
			} else {
				$result['msg'] = __( 'An error occurred while importing font manager options.', 'better-studio' );
			}
		}

		return $result;
	}


	/**
	 * Filter callback: Used for changing save translation panel result
	 *
	 * @param $output
	 * @param $args
	 *
	 * @return string
	 */
	function callback_panel_save_result( $output, $args ) {

		// change only for translation panel
		if ( $args['id'] == $this->option_panel_id ) {
			if ( $output['status'] == 'succeed' ) {
				$output['msg'] = __( 'Fonts saved.', 'better-studio' );
			} else {
				$output['msg'] = __( 'An error occurred while saving fonts.', 'better-studio' );
			}
		}

		return $output;
	}


	/**
	 * Prints TypeKit head js code
	 *
	 * @hooked wp_head
	 *
	 * @since  2.10.0
	 */
	public static function print_typekit_head_code() {

		if ( $code = bf_get_option( 'typekit_code', Better_Framework::fonts_manager()->option_panel_id ) ) {
			echo $code;
		}

	}

}
