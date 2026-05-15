<?php

namespace BetterFrameworkPackage\Component\Control\IconSelect;

class Helpers {

	public static function icon( $icon ) {

		return bf_get_icon_tag( $icon );
	}

	public static function print_attributes( array $attributes ): void {

		foreach ( $attributes as $attribute_name => $attribute_value ) {

			printf( ' %s="%s"', $attribute_name, esc_attr( $attribute_value ) );
		}
	}
}
