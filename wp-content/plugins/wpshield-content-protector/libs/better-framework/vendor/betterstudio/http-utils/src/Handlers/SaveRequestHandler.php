<?php

namespace BetterFrameworkPackage\Utils\Http\Handlers;

use BetterFrameworkPackage\Utils\Http;
use BetterFrameworkPackage\Core\Queue;

/**
 * Class SaveRequestHandler
 *
 * @since   1.0.0
 * @package BetterStudio\Utils\Http\Handlers
 * @format  L-Level Module
 */
class SaveRequestHandler {

	/**
	 * Store list of registered modules instances.
	 *
	 * @since 1.0.0
	 * @var Http\Contracts\ShouldSaveData[]
	 */
	protected static $modules = [];


	public static function register( \BetterFrameworkPackage\Utils\Http\Contracts\ShouldSaveData $module ) {

		static::$modules[ get_class( $module ) ] = $module;

		\BetterFrameworkPackage\Core\Queue\DefferCall::queue(
			$module->save_hook(),
			[
				'callback'          => [ static::class, 'save' ],
				'params'            => [ $module ],
				'merge_hook_params' => true,
			]
		);
	}

	/**
	 * Flush all registered modules.
	 *
	 * @since 1.0.0
	 */
	public static function flush() {

		static::$modules = [];
	}

	/**
	 * Get all registered modules.
	 *
	 * @since 1.0.0
	 * @return Http\Contracts\ShouldSaveData[]
	 */
	public static function modules() {

		return static::$modules;
	}

	/**
	 * Fire-up the save_data method.
	 *
	 * @param Http\Contracts\ShouldSaveData $module
	 *
	 * @since 1.0.0
	 * @todo  handle error
	 */
	public static function save( \BetterFrameworkPackage\Utils\Http\Contracts\ShouldSaveData $module,...$params ) {

		if ( $module->save_permission() ) {

			$module->save_data( new \BetterFrameworkPackage\Utils\Http\HttpRequest(), ...$params );
		}
	}
}
