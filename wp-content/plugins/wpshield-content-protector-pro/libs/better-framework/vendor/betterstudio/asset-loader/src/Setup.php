<?php

namespace BetterFrameworkPackage\Asset;

use BetterFrameworkPackage\Core\Module;

class Setup implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	/**
	 * Store register loaders.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected static $modules = [];

	/**
	 * Library version number.
	 *
	 * @link  https://semver.org/
	 * @since 1.0.0
	 */
	public const VERSION = '1.0.7';


	/**
	 * The buffer status.
	 *
	 * @var bool
	 * @since 1.0.0
	 */
	protected static $buffer_status = false;

	/**
	 * Setup the module.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function setup(): bool {

		\BetterFrameworkPackage\Asset\Enqueue\Setup::setup();

		return true;
	}

	/**
	 * Register the loader.
	 *
	 * @param string $module_id unique identifier.
	 * @param string $url       url to module root directory.
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public static function register( string $module_id, string $url ): bool {

		self::$modules[ $module_id ] = compact( 'url', 'module_id' );

		return true;
	}

	/**
	 * Get registered module info.
	 *
	 * @param string $module_id
	 *
	 * @since 1.0.0
	 * @return array empty-array when not exists.
	 */
	public static function info( string $module_id ): array {

		return self::$modules[ $module_id ] ?? [];
	}

	/**
	 * @param bool $enabled
	 *
	 * @since 1.0.0
	 * @return bool previous status
	 */
	public static function buffer_status( bool $enabled = null ): bool {

		$status = self::$buffer_status;

		if ( isset( $enabled ) ) {

			self::$buffer_status = $enabled;
		}

		return $status;
	}
}
