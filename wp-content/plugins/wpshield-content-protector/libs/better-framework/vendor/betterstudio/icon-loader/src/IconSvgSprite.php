<?php

namespace BetterFrameworkPackage\Utils\Icons;

use Generator;

class IconSvgSprite {

	/**
	 * Store icon stack instance.
	 *
	 * @var IconStack
	 * @since 3.16.0
	 */
	protected $stack;

	public function __construct( \BetterFrameworkPackage\Utils\Icons\IconStack $stack ) {

		$this->stack = $stack;
	}

	/**
	 * @since 3.16.0
	 * @return Generator
	 */
	public function export(): Generator {

		yield '<svg width="0" height="0" class="hidden">';

		foreach ( $this->stack->export() as $icon ) {

			yield self::convert_to_symbol( $icon );
		}

		yield '</svg>';
	}

	/**
	 * @since 3.16.0
	 * @return string
	 */
	public static function convert_to_symbol( array $icon ): string {

		if ( ! empty( $icon['icon_code'] ) ) {
			$svg = $icon['icon_code'];
		} else {
			$svg = file_get_contents( $icon['abs_path'] );
		}

		if ( ! preg_match( '/\<\s*svg([^\>]+)>(.+)<\s*\/\s*svg\s*>/is', $svg, $match ) ) {

			return '';
		}

		return sprintf( '<symbol %s>', self::symbol_attribute( $match[1], $icon ) ) . $match[2] . '</symbol>';
	}

	protected static function symbol_attribute( string $str_attributes, array $icon ): string {

		$attributes = \BetterFrameworkPackage\Utils\Icons\IconUtil::xml_attributes_parse( $str_attributes );
		//
		unset( $attributes['height'], $attributes['width'] );
		$attributes['id'] = self::the_id( $icon );

		return \BetterFrameworkPackage\Utils\Icons\IconUtil::xml_attributes_build( $attributes );
	}

	/**
	 * @param array $icon
	 *
	 * @since 3.16.0
	 * @return string
	 */
	public static function the_id( array $icon ): string {

		if ( ! empty( $icon['custom_id'] ) ) {

			$id = $icon['custom_id'];
		} else {

			$id = sprintf( '%s-%s', $icon['icon_group'] ?? $icon['prefix'], $icon['icon_id'] ?? $icon['id'] );
		}

		return sanitize_html_class( $id );
	}
}