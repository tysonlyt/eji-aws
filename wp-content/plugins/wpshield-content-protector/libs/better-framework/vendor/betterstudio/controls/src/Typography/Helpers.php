<?php

namespace BetterFrameworkPackage\Component\Control\Typography;

class Helpers {

	/**
	 * @var object[]
	 */
	protected static $instances = [];

	/**
	 * @param $font_name
	 *
	 * @return array|bool
	 */
	public static function font( $font_name ) {

		// Get from theme fonts
		$font = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper::get_font( $font_name );

		if ( $font !== false ) {
			return $font;
		}

		// Get from custom fonts
		$font = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper::get_font( $font_name );

		if ( $font !== false ) {
			return $font;
		}

		// Get from font stacks
		$font = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Font_Stacks_Helper::get_font( $font_name );

		if ( $font !== false ) {
			return $font;
		}

		// Get from TypeKit Fonts
		$font = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Typekit_Fonts_Helper::get_font( $font_name );

		if ( $font !== false ) {
			return $font;
		}

		// Get from font Google EA fonts
		$font = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper::get_font( $font_name );

		if ( $font !== false ) {
			return $font;
		}

		// Get from google fonts
		$font = \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_Fonts_Helper::get_font( $font_name );

		if ( $font !== false ) {
			return $font;
		}

		return false;
	}


	/**
	 * Get font variants HTML option tags
	 *
	 * @param string|array $font            Font ID
	 * @param string       $current_variant Active or Selected Variant ID
	 */
	public static function font_variants_option_elements( $font, $current_variant = '' ): string {

		switch ( $font['type'] ?? '' ) {

			// Theme fonts variants
			case 'theme-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper::get_font_variants_option_elements( $current_variant ); // escaped before

				break;

			// Custom fonts variants
			case 'custom-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper::get_font_variants_option_elements( $current_variant ); // escaped before

				break;

			// Font stacks variants
			case 'font-stack':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Font_Stacks_Helper::get_font_variants_option_elements( $current_variant ); // escaped before

				break;

			// TypeKit Font variants
			case 'typekit-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Typekit_Fonts_Helper::get_font_variants_option_elements( $current_variant ); // escaped before

				break;

			// Google fonts variants
			case 'google-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_Fonts_Helper::get_font_variants_option_elements( $font, $current_variant ); // escaped before

				break;

			// Google EA fonts variants
			case 'google-ea-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper::get_font_variants_option_elements( $font, $current_variant ); // escaped before

				break;

		}

		return '';
	}


	/**
	 * Get font subsets HTML option tags
	 *
	 * @param string|array $font           Font ID
	 * @param string       $current_subset Active or Selected Subset ID
	 */
	public static function font_subset_option_elements( $font, $current_subset = '' ): string {

		switch ( $font['type'] ?? '' ) {

			case 'theme-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper::get_font_subset_option_elements(); // escaped before

				break;

			case 'custom-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper::get_font_subset_option_elements(); // escaped before

				break;

			case 'font-stack':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Font_Stacks_Helper::get_font_subset_option_elements(); // escaped before

				break;

			case 'typekit-fonts':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Typekit_Fonts_Helper::get_font_subset_option_elements(); // escaped before

				break;

			case 'google-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_Fonts_Helper::get_font_subset_option_elements( $font, $current_subset ); // escaped before

				break;

			case 'google-ea-font':
				return \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper::get_font_subset_option_elements( $font, $current_subset ); // escaped before

				break;

		}

		return '';
	}

	/**
	 * @return old\BF_FM_Theme_Fonts_Helper
	 */
	public static function theme_fonts(): \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper {

		if ( ! isset( self::$instances['theme-fonts'] ) ) {

			self::$instances['theme-fonts'] = new \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper();
		}

		return self::$instances['theme-fonts'];
	}

	/**
	 * @return old\BF_FM_Custom_Fonts_Helper
	 */
	public static function custom_fonts(): \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper {

		if ( ! isset( self::$instances['custom-fonts'] ) ) {

			self::$instances['custom-fonts'] = new \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper();
		}

		return self::$instances['custom-fonts'];
	}

	/**
	 * @return old\BF_FM_Font_Stacks_Helper
	 */
	public static function font_stacks(): \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Font_Stacks_Helper {

		if ( ! isset( self::$instances['font-stacks'] ) ) {

			self::$instances['font-stacks'] = new \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Font_Stacks_Helper();
		}

		return self::$instances['font-stacks'];
	}

	/**
	 * @return old\BF_FM_Typekit_Fonts_Helper
	 */
	public static function typekit_fonts(): \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Typekit_Fonts_Helper {

		if ( ! isset( self::$instances['typekit-fonts'] ) ) {

			self::$instances['typekit-fonts'] = new \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Typekit_Fonts_Helper();
		}

		return self::$instances['typekit-fonts'];
	}

	/**
	 * @return old\BF_FM_Google_Fonts_Helper
	 */
	public static function google_fonts(): \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_Fonts_Helper {

		if ( ! isset( self::$instances['google-fonts'] ) ) {

			self::$instances['google-fonts'] = new \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_Fonts_Helper();
		}

		return self::$instances['google-fonts'];
	}

	/**
	 * @return old\BF_FM_Google_EA_Fonts_Helper
	 */
	public static function google_ea_fonts(): \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper {

		if ( ! isset( self::$instances['google-ea-fonts'] ) ) {

			self::$instances['google-ea-fonts'] = new \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper();
		}

		return self::$instances['google-ea-fonts'];
	}
}
