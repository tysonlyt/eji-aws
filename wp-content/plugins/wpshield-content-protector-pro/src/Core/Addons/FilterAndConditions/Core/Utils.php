<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core;

use WPShield\Core\PluginCore\Core\Contracts\Installable;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core
 */
class Utils {

	/**
	 * Store the current condition filter type.
	 *
	 * @var string
	 */
	protected static $filter_type;

	/**
	 * Store the filter conditions arguments.
	 *
	 * @var array
	 */
	protected static $filter_args;

	/**
	 * Store the global condition details.
	 *
	 * @var array
	 */
	protected static $global_condition;

	/**
	 * Retrieve filtered array by deep method.
	 *
	 * @param array $array
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function array_deep_filter( array $array ): array {

		$new_array = [];

		foreach ( $array as $key => $item ) {

			if ( is_array( $item ) ) {

				$new_array[ $key ] = self::array_deep_filter( $item );

				continue;
			}

			if ( empty( $item ) ) {

				continue;
			}

			$new_array[ $key ] = $item;
		}

		return array_filter( $new_array );
	}

	/**
	 * Retrieve just filter fields.
	 *
	 * Pattern detector for Example: "${anythings}/filters".
	 *
	 * @param array $args
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_filter_fields( array $args ): array {

		$keys = array_keys( $args );

		foreach ( $keys as $key ) {

			$is_match = preg_match( '/.*\/filters/i', $key, $m );

			//If this condition occurs, it will be recognized as an invalid field.
			if ( ! $is_match ) {

				#Unset invalid filter field from arguments.
				unset( $args[ $key ] );
			}
		}

		return $args;
	}

	/**
	 * Retrieve sanitized protector name.
	 *
	 * @param string $protector
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function sanitize_protector( string $protector ): string {

		#Replace suffix with nothing.
		return str_replace( '/filters', '', $protector );
	}

	/**
	 * Retrieve standard l10n object name by identifier.
	 *
	 * @param string $id
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function get_l10n_object_name( string $id ): string {

		$sections = explode( '-', $id );

		return implode( '', array_map( 'ucfirst', $sections ) );
	}

	/**
	 * Handle filters with all conditions support.
	 *
	 * @param array $filters
	 * @param array $args
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function running_filter_module( array $filters, array $args = [] ): bool {

		self::$filter_args = $args;

		self::prepare_the_global_condition( $filters );

		/**
		 * Mapped conditions result as array,
		 * array must be included item have below indexes:
		 * array (
		 *
		 * @param bool   $active
		 * @param string $type
		 *
		 * )
		 */
		$conditions = array_values(
			array_filter(
				array_map( [ self::class, 'map_condition_results' ], $filters )
			)
		);

		if ( empty( $conditions ) ) {

			return ! empty( self::$global_condition['active'] ) && 'exclude' === self::$global_condition['type'];
		}

		///Prepare all filter conditions result as bool.
		return self::get_filter_result( $conditions );
	}

	/**
	 * Setup global condition.
	 *
	 * @param array $filters
	 *
	 * @since 1.0.0
	 * @return void
	 */
	protected static function prepare_the_global_condition( array $filters ): void {

		self::$filter_type = 'global';

		//Prepare the global condition
		$global = array_values(
			array_filter( array_map( [ self::class, 'get_current_conditions_type' ], $filters ?? [] ) )
		);

		$global_filter = end( $global );

		if ( $global_filter && ! empty( $global_filter['in'] ) ) {

			$component = self::$filter_args[ $global_filter['in'] ]['component'] ?? null;

			if ( $component instanceof Installable ) {

				/**
				 * @var BaseModule $component
				 */
				$component->set_filter( $global_filter );

				self::$global_condition = [
					'active' => $component->active(),
					'type'   => $global_filter['type'] ?? 'include',
				];
			}
		}
	}

	/**
	 * Retrieve the current conditions type.
	 *
	 * @param array $condition_result
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_current_conditions_type( array $condition_result ): array {

		if ( ! empty( $condition_result['in'] ) && self::$filter_type !== $condition_result['in'] ) {

			return [];
		}

		return $condition_result;
	}

	/**
	 * Retrieve mapped condition results.
	 *
	 * @param array $filter
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function map_condition_results( array $filter ): array {

		if ( ! isset( $filter['in'] ) ) {

			return [];
		}

		if ( ! isset( self::$filter_args[ $filter['in'] ]['component'] ) ) {

			return [];
		}

		$component = self::$filter_args[ $filter['in'] ]['component'];

		if ( ! $component instanceof Installable ) {

			return [];
		}

		/**
		 * @var BaseModule $component
		 */
		$component->set_filter( $filter );

		$active = $component->active();

		if ( ! $active && 'include' !== $filter['type'] ) {

			return [];
		}

		return [
			'active' => $active,
			'in'     => $filter['in'],
			'type'   => $filter['type'],
		];
	}

	/**
	 * Retrieve filter conditions result.
	 *
	 * @param array $conditions
	 *
	 * @since 1.0.0
	 * @return bool true on filtered execution, false on failure (execution will not be filter).
	 */
	protected static function get_filter_result( array $conditions ): bool {

		/**
		 * Register filter types.
		 *
		 * This is way to creates a list of all the supported filter types.
		 *
		 * External developers can register new filter type by hooking to the
		 * `wpshield/content-protector-pro/condition/filter/types` filter.
		 *
		 * @since 1.0.0
		 */
		$filter_types = apply_filters( 'wpshield/content-protector-pro/condition/filter/types',
			[
				'user',
				'user-role',
				'url',
				'taxonomies',
				'post',
				'global',
			]
		);

		foreach ( $filter_types as $type ) {

			// this line ignore filter when not exists in conditions columns.
			if ( ! in_array( $type, array_column( $conditions ?? [], 'in' ), true ) ) {

				continue;
			}

			// setup current filter type. Like => user,...
			self::$filter_type = $type;

			// prepare all conditions for current filter type.
			$_conditions = array_values(
				array_filter( array_map( [ self::class, 'get_current_conditions_type' ], $conditions ?? [] ) )
			);

			foreach ( $_conditions as $condition ) {

				// this line ignore condition when execution result is false.
				if ( ! $condition['active'] ) {

					continue;
				}

				// the current condition when is filtered execution where condition type is equal with 'exclude' word!
				return 'include' !== $condition['type'];
			}
		}

		return false;
	}
}
