<?php

namespace BetterFrameworkPackage\Utils\HTMLUtil;

use Laminas\{
	Escaper,
};

class Sanitize {

	/**
	 * Sanitizes a string key.
	 *
	 * Keys are used as internal identifiers. Lowercase alphanumeric characters,
	 * dashes, and underscores are allowed.
	 *
	 * @param string $key String key.
	 *
	 * @return string Sanitized key.
	 */
	public static function sanitize_key( string $key ): string {

		$sanitized_key = '';

		if ( is_scalar( $key ) ) {

			$sanitized_key = strtolower( $key );
			$sanitized_key = preg_replace( '/[^a-z0-9_\-]/', '', $sanitized_key );
		}

		/**
		 * Filters a sanitized key string.
		 *
		 * @param string $sanitized_key Sanitized key.
		 * @param string $key           The key prior to sanitization.
		 *
		 * @since 3.0.0
		 *
		 */
		return $sanitized_key;
	}

	public static function esc_attr( string $attribute ): string {

		return ( new Escaper\Escaper( 'utf-8' ) )->escapeHtmlAttr( $attribute );
	}

	public static function sanitize_html_classes( string $classes ): string {

		// Strip out any %-encoded octets.
		$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $classes );

		// Limit to A-Z, a-z, 0-9, '_', '-'. and space
		return preg_replace( '/[^\sA-Za-z0-9_-]/', '', $sanitized );
	}
}
