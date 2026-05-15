<?php

namespace BetterFrameworkPackage\Component\Control\Typography\old;

/**
 * Used For retrieving Font Stacks.
 */
class BF_FM_Font_Stacks_Helper {


	/**
	 * Contain array of all font stacks
	 *
	 * @var array
	 */
	private static $fonts_list = null;


	/**
	 * Used for Retrieving list of all Google Fonts
	 */
	public static function get_all_fonts() {

		if ( self::$fonts_list != null ) {
			return self::$fonts_list;
		}

		if ( ! function_exists( 'bf_get_option' ) ) {

			return [];
		}

		// load option
		$font_stacks = bf_get_option( 'font_stacks', 'better-framework-custom-fonts' );

		// create array
		$_font_stacks = [];

		foreach ( (array) $font_stacks as $stack ) {

			$_font_stacks[ $stack['id'] ]['stack'] = $stack['stack'];
			$_font_stacks[ $stack['id'] ]['type']  = 'font-stack';

		}

		// save to cache
		return self::$fonts_list = $_font_stacks;
	}


	/**
	 * Used for retrieving single font info
	 *
	 * @param $font_name
	 *
	 * @return bool|array
	 */
	public static function get_font( $font_name ) {

		$fonts = self::get_all_fonts();

		if ( isset( $fonts[ $font_name ] ) ) {
			return $fonts[ $font_name ];
		} else {
			return false;
		}
	}


	/**
	 * Generate and return Option elements of all font for select element
	 *
	 * @param string $active_font  Family name of selected font in options
	 * @param bool   $option_group Active or selected font
	 *
	 * @return  string
	 */
	public static function get_fonts_family_option_elements( string $active_font = '', bool $option_group = true ) {

		$output = '';

		if ( $option_group ) {
			$output .= '<optgroup label="' . __( 'Font Stacks', 'better-studio' ) . '">';
		}

		foreach ( self::get_all_fonts() as $key => $font ) {
			$output .= '<option value="' . esc_attr( $key ) . '" ' . ( $key == $active_font ? 'selected' : '' ) . '>' . esc_html( $key ) . '</option>';
		}

		if ( $option_group ) {
			$output .= '</optgroup>';
		}

		return $output;
	}


	/**
	 * Generate and return Option elements of font variants
	 *
	 * @param string $font_variant Active or selected variant
	 *
	 * @return string
	 */
	public static function get_font_variants_option_elements( string $font_variant = '' ): string {

		if ( empty( $font_variant ) ) {

			$font_variant = '400';
		}

		$output = '';

		$variants = [
			'100'       => __( 'Ultra-Light 100', 'better-studio' ),
			'300'       => __( 'Book 300', 'better-studio' ),
			'400'       => __( 'Normal 400', 'better-studio' ),
			'500'       => __( 'Medium 500', 'better-studio' ),
			'700'       => __( 'Bold 700', 'better-studio' ),
			'900'       => __( 'Ultra-Bold 900', 'better-studio' ),
			'100italic' => __( 'Ultra-Light 100 Italic', 'better-studio' ),
			'300italic' => __( 'Book 300 Italic', 'better-studio' ),
			'400italic' => __( 'Normal 400 Italic', 'better-studio' ),
			'500italic' => __( 'Medium 500 Italic', 'better-studio' ),
			'700italic' => __( 'Bold 700 Italic', 'better-studio' ),
			'900italic' => __( 'Ultra-Bold 900 Italic', 'better-studio' ),
		];

		foreach ( $variants as $variant_id => $variant_name ) {
			$output .= '<option value="' . $variant_id /* escaped before */ . '" ' . ( $variant_id == $font_variant ? 'selected' : '' ) . '>' . esc_html( $variant_name ) . '</option>';
		}

		return $output;
	}


	/**
	 * Generate and return Option elements of font subsets
	 *
	 * @return string
	 */
	public static function get_font_subset_option_elements(): string {

		return '<option value="unknown">' . esc_html__( 'Unknown', 'better-studio' ) . '</option>';
	}

}
