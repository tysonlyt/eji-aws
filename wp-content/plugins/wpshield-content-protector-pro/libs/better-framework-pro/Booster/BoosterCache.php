<?php

namespace BetterStudio\Framework\Pro\Booster;

abstract class BoosterCache {

	/**
	 * Name for transient cache storage
	 *
	 * Special names: the following name will replace to
	 *
	 * {group} > the group of cache
	 * {ID}    > the ID of cache
	 *
	 * @var string
	 */
	public static $cache_name_format = 'bf-booster-{group}-{ID}';

	/**
	 * Determine the durations of the cache
	 *
	 * @var array
	 */
	protected static $cache_intervals = array(
		'long'  => 36000, // 10 Hour
		'short' => 1800, // 30 Minute
	);


	/**
	 * Detect is any cache plugin enabled on site
	 *
	 * @return bool true if enabled
	 */
	public static function have_cache_plugin(): bool {

		if ( defined( 'WP_CACHE' ) && WP_CACHE ) {
			return true;
		}

		// Detect Fastest cache

		return in_array( WP_PLUGIN_DIR . '/wp-fastest-cache/wpFastestCache.php', wp_get_active_and_valid_plugins() );
	}

	/****
	 *
	 *
	 * Cache Methods
	 *
	 *
	 ***/

	/***
	 * Fetch cache from cache storage
	 *
	 * @param string $cache_key   unique label for the cache
	 * @param string $cache_group optional. for categorizing the caches.
	 *
	 * @return mixed cached data success or void on failure.
	 */
	public static function get_cache( string $cache_key, string $cache_group = 'default' ) {

		$transient_name = self::get_cache_name( $cache_key, $cache_group );

		if ( $group_cached = get_transient( $transient_name ) ) {
			if ( isset( $group_cached[ $cache_key ] ) ) {
				return $group_cached[ $cache_key ];
			}
		}
	}


	/**
	 * Save Data in Cache Storage
	 *
	 * @param string   $cache_key   name of the cache
	 * @param mixed    $data2cache  data to cache
	 * @param string   $cache_group optional. cache group
	 * @param int|null $expiration  optional. cache expiration time
	 *
	 * @return bool true on success or false otherwise
	 */
	public static function set_cache( string $cache_key, string $data2cache, string $cache_group = 'default', int $expiration = null ): bool {

		$transient_name = self::get_cache_name( $cache_key, $cache_group );

		$current_data = get_transient( $transient_name );
		if ( ! $current_data ) {
			$current_data = array();
		}
		$current_data = (array) $current_data;

		$new_data               = &$current_data;
		$new_data[ $cache_key ] = $data2cache;

		return set_transient( $transient_name, $new_data, $expiration );
	}


	/**
	 * Get unique name for the cache
	 *
	 * @param string $cache_key
	 * @param string $cache_group
	 *
	 * @return string cache name
	 */
	public static function get_cache_name( string $cache_key, string $cache_group = 'default' ): string {

		$replacement = array(
			'{ID}'    => $cache_key,
			'{group}' => $cache_group,
		);

		return str_replace( array_keys( $replacement ), array_values( $replacement ), self::$cache_name_format );
	}


	/**
	 * Delete a specific cache
	 *
	 * @param string $cache_key
	 * @param string $cache_group
	 *
	 * @return bool true on success or false on failure.
	 */
	public static function delete_cache( string $cache_key, string $cache_group = 'default' ): bool {

		$transient_name = self::get_cache_name( $cache_key, $cache_group );
		$cache_data     = get_transient( $transient_name );

		if ( ! $cache_data ) {

			return false;
		}

		$cache_data = (array) $cache_data;

		unset( $cache_data[ $cache_key ] );

		if ( empty( $cache_data ) ) {

			return delete_transient( $transient_name );
		}

		return set_transient( $transient_name, $cache_data ); # When don't pass the third parameter, it will not change
	}


	/**
	 * Flush all of the data in the cache storage
	 *
	 * it doesn't master the cache belong to which group this method will delete all data in any group
	 *
	 * @global \wpdb $wpdb wordpress database object
	 *
	 * @return bool true if cache was successfully cleared or false otherwise.
	 */
	public static function flush_cache(): bool {

		global $wpdb;

		$transient_option_prefix  = '_transient_';          # WordPress transient prefix
		$transient_timeout_prefix = '_transient_timeout_';  # Prefix for transient timeout on db

		$transient_name = str_replace( array( '{ID}', '{group}' ), '%', self::$cache_name_format );
		$affected       = 0;

		$affected += $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options where option_name LIKE %s", "$transient_option_prefix$transient_name" ) );
		$affected += $wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options where option_name LIKE %s", "$transient_timeout_prefix$transient_name" ) );

		return (bool) $affected;
	}


	/**
	 * Get cache durations interval
	 *
	 * @return array
	 */
	public static function cache_intervals(): array {

		return apply_filters( 'better-framework/booster/cache-intervals', self::$cache_intervals );
	}
}