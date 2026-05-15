<?php

namespace BetterFrameworkPackage\Utils\Http;

/**
 * Helper class for working with URLs.
 *
 * @since   1.0.0
 * @package BetterStudio/Core/Http
 * @format  Core Module
 */
final class Helper {

	/**
	 * Parse the given url.
	 *
	 * @param string $url
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function parse_url( string $url ): array {

		$pattern = '"^
			(?P<scheme> https?://)?   	# capture url scheme, Ex: https://
			(?P<www> w{3}.)?			# capture www.
 			(?P<domain> [^/]+) 			# capture url upto the first slash
 			(?P<remaining> .*?)			# capture remaining string after the slash
 		$"ixs';

		if ( ! preg_match( $pattern, $url, $parse ) ) {

			return [];
		}

		if ( ! strpos( $parse['domain'], '.' ) ) { // the domain name must have a dot character

			return [];
		}

		return array_filter( $parse, function ( $key ) {

			return ! is_int( $key );
		}, ARRAY_FILTER_USE_KEY );
	}

	/**
	 * Sanitize the given url.
	 *
	 * todo: add support for internationalized domain names
	 *
	 * @param string $url
	 * @param array  $options {
	 *
	 * @type bool    $trim_w
	 * @type bool    $trim_path
	 * @type bool    $trim_scheme
	 *
	 * }
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function sanitize_url( string $url, $options = [] ): string {

		if ( ! $parse = static::parse_url( $url ) ) {

			return '';
		}

		$options = wp_parse_args( $options, [
			'trim_w'      => false,
			'trim_path'   => false,
			'trim_scheme' => false,
		] );

		$url = '';
		//
		if ( ! $options['trim_scheme'] ) { // append scheme://

			$url .= $parse['scheme'];
		}

		if ( ! $options['trim_w'] ) { // append www.

			$url .= $parse['www'];
		}

		$url .= $parse['domain'];  // append domain name

		if ( ! $options['trim_path'] ) { // append other string

			$url .= $parse['remaining'];
		}

		return $url;
	}
}
