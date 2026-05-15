<?php

namespace BetterStudio\Core\Rest;

use BetterStudio\Core\Module;

/**
 * API to Work with WordPress Rest API.
 *
 * @since   1.0.0
 * @package BetterStudio\Core\Rest
 */
final class RestSetup implements Module\NeedSetup {

	/**
	 * Store the routes list.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected static $routes = [];

	/**
	 * Rest Route Namespace.
	 *
	 * @since 1.0.0
	 */
	public const NAMESPACE = 'betterstudio/v1';

	/**
	 * Initialize the rest module.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function setup(): bool {

		add_action( 'rest_api_init', [ static::class, 'rest_init' ], 20 );

		return true;
	}

	/**
	 * Registers a REST API route.
	 *
	 * @param string|RestHandler $handler Handler classname or instance.
	 *
	 * @since 1.0.0
	 *
	 * @return bool true on success, false on error.
	 */
	public static function register( $handler ): bool {

		$classname = $handler instanceof RestHandler ? get_class( $handler ) : $handler;

		if ( ! in_array( $classname, static::$routes ) ) {

			static::$routes[] = $classname;

			return true;
		}

		return false;
	}

	/**
	 * Fires when preparing to serve an API request.
	 *
	 * @since 1.0.0
	 */
	public static function rest_init() {

		/**
		 * @var string|RestHandler $handler Handler classname or instance
		 */
		foreach ( static::$routes as $handler ) {

			if ( ! $handler instanceof RestHandler ) {

				$handler = $handler::instance();
			}

			register_rest_route(
				static::NAMESPACE,
				$handler->rest_end_point(),
				[
					'methods'             => $handler->methods(),
					'callback'            => [ $handler, 'rest_response' ],
					'permission_callback' => [ $handler, 'rest_permission' ],
					'allow_batch'         => $handler instanceof AllowBatch ? $handler->allow_batch() : [],

				],
				true
			);
		}
	}

	/**
	 * Flush registered routes.
	 *
	 * @since 1.0.0
	 */
	public static function flush() {

		static::$routes = [];
	}


	/**
	 * Get all registered routes.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function routes() {

		return static::$routes;
	}
}
