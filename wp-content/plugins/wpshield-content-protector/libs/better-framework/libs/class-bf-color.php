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


/**
 * Handy Functions for Color
 */
class BF_Color {

	/**
	 * Contains User profile Colors
	 *
	 * @var array
	 */
	private static $user_profile_color = null;


	function __construct() {

		add_action( 'admin_enqueue_scripts', [ $this, 'get_user_colors' ], 1 );
	}


	/**
	 * Used for reliving current user color schema informations
	 */
	function get_user_colors() {

		global $_wp_admin_css_colors;

		$user_color = get_user_option( 'admin_color' );
		if ( empty( $user_color ) ) {
			return '';
		}

		$user_color = $_wp_admin_css_colors[ $user_color ];

		if ( empty( $user_color ) || ! is_object( $user_color ) ) {
			return '';
		}

		self::$user_profile_color['color-1'] = $user_color->colors[0]; // background
		self::$user_profile_color['color-2'] = $user_color->colors[1]; // lighter background
		self::$user_profile_color['color-3'] = $user_color->colors[2]; // active color
		self::$user_profile_color['color-4'] = $user_color->colors[3]; // hover active color

		switch ( get_user_option( 'admin_color' ) ) {
			case 'light':
				self::$user_profile_color['color-3'] = '#888';
				break;
			case 'midnight':
				self::$user_profile_color['color-3'] = '#e14d43';
				break;
		}

	}


	/**
	 * Used for Retrieving User Profile Color
	 *
	 * color-1 => background
	 * color-2 => lighter background
	 * color-3 => active color
	 * color-4 => hover active color
	 *
	 * @param $color_type
	 *
	 * @return array
	 */
	public static function get_user_profile_color( $color_type ) {

		if ( is_null( self::$user_profile_color ) ) {
			// todo why i did this?!!?
			return 'NOOOOO';

		}

		return self::$user_profile_color[ $color_type ];
	}


	/**
	 * Change Color Brighter or Darker
	 *
	 * Steps should be between -255 and 255. Negative = darker, positive = lighter
	 *
	 * @param $hexCode
	 * @param $adjustPercent
	 *
	 * @return string
	 */
	static function change_color( $hexCode, $adjustPercent ) {

		if ( false !== strpos( $hexCode, 'rgb(' ) ) {

			$hexCode = ncm_rgb_to_hex( $hexCode );
		}

		if ( empty( $adjustPercent ) ) {

			return $hexCode;
		}

		$adjustPercent /= 100;
		$hexCode        = ltrim( $hexCode, '#' );

		if ( strlen( $hexCode ) === 3 ) {
			$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
		}

		$hexCode = array_map( 'hexdec', str_split( $hexCode, 2 ) );

		foreach ( $hexCode as & $color ) {

			$adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
			$adjustAmount    = ceil( $adjustableLimit * $adjustPercent );

			$color = str_pad( dechex( $color + $adjustAmount ), 2, '0', STR_PAD_LEFT );
		}

		return '#' . implode( $hexCode );
	}


	public static function hex_to_rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		if ( empty( $color ) ) {
			return $default;
		}

		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		if ( strlen( $color ) == 6 ) {
			$hex = [ $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] ];
		} elseif ( strlen( $color ) == 3 ) {
			$hex = [ $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] ];
		} else {
			return $default;
		}

		$rgb = array_map( 'hexdec', $hex );

		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}

			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}

		return $output;
	}

	/**
	 * Convert rgb color code to hexadecimal code.
	 *
	 * @param string $color
	 *
	 * @since 4.0.0
	 * @return string|void
	 */
	public static function rgb_to_hex( string $color ) {

		$default = '#000';

		if ( empty( $color ) ) {

			return $default;
		}

		if ( is_int( strpos( $color, '#' ) ) ) {

			return $color;
		}

		$rgb     = str_replace( [ 'rgb(', ')' ], '', $color );
		$rgb_arr = explode( ',', $rgb, 3 );

		if ( count( $rgb_arr ) < 3 ) {

			return $default;
		}

		return sprintf( '#%02x%02x%02x', $rgb_arr[0], $rgb_arr[1], $rgb_arr[2] );
	}
}
