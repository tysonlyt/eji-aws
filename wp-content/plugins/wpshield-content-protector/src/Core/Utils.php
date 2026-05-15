<?php


namespace WPShield\Plugin\ContentProtector\Core;

use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core\Utils as ProUtils;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Core
 */
class Utils {

	/**
	 * Retrieve hosts as array.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_hosts(): array {

		$host = wp_parse_url( site_url( '/' ), PHP_URL_HOST );

		if ( 0 === strpos( $host, 'www.' ) ) {

			$host = substr( $host, 4 );
		}

		return [ $host ];
	}

	/**
	 * Retrieve just current component fields.
	 *
	 * Pattern detector for Example: "${component_identifier}\/.*".
	 *
	 * @param string $component_id
	 * @param array  $fields
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_component_fields( string $component_id, array $fields ): array {

		$keys = array_keys( $fields );

		foreach ( $keys as $key ) {

			$is_match = preg_match(
				wp_sprintf(
					'/%1$s|%1$s\/.*/i',
					$component_id
				),
				$key,
				$m
			);

			//If this condition occurs, it will be recognized as an invalid field.
			if ( ! $is_match ) {

				#Unset invalid filter field from arguments.
				unset( $fields[ $key ] );
			}
		}

		return $fields;
	}

	/**
	 * This is the opponent of JavaScripts decodeURIComponent()
	 * @link http://stackoverflow.com/questions/1734250/what-is-the-equivalent-of-javascripts-encodeuricomponent-in-php
	 *
	 * @param string $content
	 *
	 * @return string
	 */
	public static function encode_uri_components( string $content ): string {

		$revert = [
			'%21' => '!',
			'%2A' => '*',
			'%27' => "'",
			'%28' => '(',
			'%29' => ')',
		];

		return strtr( rawurlencode( $content ), $revert );
	}

	/**
	 * Filter protector operations with conditions extension.
	 *
	 * @param string $protector
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function is_filtered_with_conditions( string $protector ): bool {

		if ( ! defined( 'WPSHIELD_CPP_CORE_FAC__FILE__' ) || ! is_callable( ProUtils::class, 'filter_condition' ) ) {

			return false;
		}

		$filters           = wpshield_cp_option( wp_sprintf( '%s/filters', $protector ) );
		$filter_components = get_option( 'filter-and-conditions/handle/details', [] );

		if ( ! $filters || ! isset( $filter_components[ $protector ] ) ) {

			return false;
		}

		return ProUtils::running_filter_module( $filters, $filter_components[ $protector ] );
	}

	/**
	 * Retrieve the current page url
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function get_current_page_url(): string {

		return "http" . ( ( $_SERVER['SERVER_PORT'] == 443 ) ? "s" : "" ) . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
}
