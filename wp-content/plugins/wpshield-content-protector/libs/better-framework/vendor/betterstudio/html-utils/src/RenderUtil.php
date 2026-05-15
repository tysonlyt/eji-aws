<?php

namespace BetterFrameworkPackage\Utils\HTMLUtil;

class RenderUtil {

	public static function render_attributes( array $attributes, bool $return = false ): ?string {

		/**
		 * Note: WordPress Compatibility
		 */
		$sanitize_key          = function_exists( '\sanitize_key' ) ? '\sanitize_key' : [ \BetterFrameworkPackage\Utils\HTMLUtil\Sanitize::class, 'sanitize_key' ];
		$sanitize_attr         = function_exists( '\esc_attr' ) ? '\esc_attr' : [ \BetterFrameworkPackage\Utils\HTMLUtil\Sanitize::class, 'esc_attr' ];

		if ( isset( $attributes['class'] ) ) {

			if ( is_array( $attributes['class'] ) ) {

				$attributes['class'] = implode( ' ', array_filter( $attributes['class'] ) );
			}

			$attributes['class'] = \BetterFrameworkPackage\Utils\HTMLUtil\Sanitize::sanitize_html_classes( $attributes['class'] );
		}

		$output = ' ';

		foreach ( $attributes as $key => $attribute ) {

			if ( is_array( $attribute ) || is_object( $attribute ) ) {

				$attribute = wp_json_encode( $attribute );
			}

			$output .= sprintf( ' %s="%s"', $sanitize_key( $key ), $sanitize_attr( $attribute ) );
		}

		$output = ltrim( $output );

		if ( ! $return ) {

			echo $output;

			return null;
		}

		return $output;
	}
}
