<?php

namespace BetterFrameworkPackage\Utils\Icons;

class IconUtil {

	/**
	 * Convert xml attributes string into array.
	 *
	 * @param string $xml_attributes
	 *
	 * @since 3.16.0
	 * @return array
	 */
	public static function xml_attributes_parse( string $xml_attributes ): array {

		preg_match_all( '/
					\s*([^=]+)= 	# capture the attribute key
					([\"\'])?		# find single or double quote
					(.*?)\\2		# capture the attribute value
					/sx', $xml_attributes, $matches, PREG_SET_ORDER );

		foreach ( $matches as $match ) {

			$attributes_list[ $match[1] ] = $match[3];
		}

		return $attributes_list ?? [];
	}

	/**
	 * Convert array into xml attributes string.
	 *
	 * @param array $attributes
	 *
	 * @since 3.16.0
	 * @return string
	 */
	public static function xml_attributes_build( array $attributes ): string {

		$xml_attributes = '';

		foreach ( $attributes as $key => $value ) {

			$xml_attributes .= sprintf( '%s="%s" ', $key, $value );
		}

		return rtrim($xml_attributes, ' ');
	}
}
