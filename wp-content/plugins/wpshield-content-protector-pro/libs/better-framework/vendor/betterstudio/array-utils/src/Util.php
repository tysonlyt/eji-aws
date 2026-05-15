<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

/**
 * Helper methods to work with collections.
 *
 * @package BetterStudio\Utils\ArrayUtil
 */
final class Util {

	/**
	 * Get a value by index.
	 *
	 * @param array      $array
	 * @param string|int $index
	 * @param mixed      $default
	 *
	 * @since 1.0.0
	 * @return mixed null on failure other type on success.
	 */
	public static function get( array $array, $index, $default = null ) {

		$value = \BetterFrameworkPackage\Utils\ArrayUtil\CollectionRef::instance( $array )->get( $index );

		return $value ?? $default;
	}

	/**
	 * Store value in given index.
	 *
	 * @param array      $array
	 * @param string|int $index
	 * @param mixed      $value
	 *
	 * @since 1.0.0
	 */
	public static function set( array &$array, $index, $value ): void {

		\BetterFrameworkPackage\Utils\ArrayUtil\CollectionRef::instance( $array )->set( $index, $value );
	}

	public static function parent_index( string $index ): string {

		preg_match( '#(.+)\.[^.]+$#', $index, $match );

		return $match[1] ?? '';
	}

	public static function root_index( string $index ): string {

		preg_match( '#^([^.]+)#', $index, $match );

		return $match[1] ?? '';
	}

	public static function base_index( string $index ) {

		preg_match( '#([^.]+)$#', $index, $match );

		$return = $match[1] ?? '';

		if ( ( $int = filter_var( $return, FILTER_VALIDATE_INT ) ) !== false ) {

			return $int;

		}

		if ( ( $float = filter_var( $return, FILTER_VALIDATE_FLOAT ) ) !== false ) {

			return $float;
		}

		return $return;
	}

	public static function to_array( $item ) {

		if ( $item instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			return $item->to_array();
		}

		if ( is_iterable( $item ) ) {

			return array_map( [ self::class, 'to_array' ], $item );
		}

		return $item;
	}
}
