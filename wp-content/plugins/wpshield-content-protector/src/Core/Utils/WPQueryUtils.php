<?php

namespace WPShield\Plugin\ContentProtector\Core\Utils;

/**
 * Class WpQueryUtils
 *
 * @since   1.0.1
 *
 * @package WPShield\Plugin\ContentProtector\Core\Utils
 */
class WPQueryUtils {

	/**
	 * Returns the global WP_Query object.
	 *
	 * @return \WP_Query The WP_Query object.
	 */
	public static function get_query(): \WP_Query {

		return $GLOBALS['wp_query'];
	}

	/**
	 * Returns the global main WP_Query object.
	 *
	 * @return \WP_Query The WP_Query object.
	 */
	public static function get_main_query(): \WP_Query {

		return $GLOBALS['wp_the_query'];
	}

	/**
	 * Sets the global WP_Query object.
	 *
	 * @param \WP_Query $wp_query The WP Query.
	 */
	public static function set_query( \WP_Query $wp_query ): void {

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride -- This is a deliberate action.
		$GLOBALS['wp_query'] = $wp_query;
	}

	/**
	 * Resets the global WP_Query object.
	 */
	public static function reset_query(): void {

		\wp_reset_query();
	}

}