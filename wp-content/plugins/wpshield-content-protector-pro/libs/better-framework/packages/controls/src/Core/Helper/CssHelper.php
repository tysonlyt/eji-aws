<?php

namespace BetterFrameworkPackage\Component\Control\Core\Helper;

class CssHelper {

	/**
	 * Increases or decreases the brightness of a color by a percentage of the current brightness.
	 *
	 * @param string $hex_code       Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
	 * @param float  $adjust_percent A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
	 *
	 * @return  string
	 *
	 * @author    maliayas
	 * @copyright https://gist.github.com/stephenharris/5532899#gistcomment-2251604
	 */
	public static function adjust_brightness( string $hex_code, float $adjust_percent ): string {

		$hex_code = ltrim( $hex_code, '#' );

		if ( strlen( $hex_code ) == 3 ) {
			$hex_code = $hex_code[0] . $hex_code[0] . $hex_code[1] . $hex_code[1] . $hex_code[2] . $hex_code[2];
		}

		$hex_code = array_map( 'hexdec', str_split( $hex_code, 2 ) );

		foreach ( $hex_code as & $color ) {
			$adjustableLimit = $adjust_percent < 0 ? $color : 255 - $color;
			$adjustAmount    = ceil( $adjustableLimit * $adjust_percent );

			$color = str_pad( dechex( $color + $adjustAmount ), 2, '0', STR_PAD_LEFT );
		}

		return '#' . implode( $hex_code );
	}
}
