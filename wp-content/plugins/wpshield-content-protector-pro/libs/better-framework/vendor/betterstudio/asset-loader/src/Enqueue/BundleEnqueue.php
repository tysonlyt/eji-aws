<?php

namespace BetterFrameworkPackage\Asset\Enqueue;

use BetterFrameworkPackage\Core\Module;

/**
 * Register styles/script to combine
 * and load bundle in single request.
 *
 * @since   1.0.0
 * @package BetterStudio\Asset
 */
final class BundleEnqueue {

	/**
	 * Store the registered scripts.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected static $scripts = [];

	/**
	 * Store the registered styles.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected static $styles = [];

	/**
	 * Register an script file.
	 *
	 * @param string $id       Unique identifier.
	 * @param string $rel_path Relative path to the script file.
	 *
	 * @since 1.0.0
	 * @throws Module\Exception
	 * @return bool
	 */
	public static function script( string $id, string $rel_path ): bool {

		if ( strpos( $id, ':' ) === false ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Invalid ID' );
		}

		[ $module_id, $script_id ] = explode( ':', $id );

		self::$scripts[$module_id][ $script_id ] = compact( 'rel_path', 'module_id', 'script_id' );

		return true;
	}

	/**
	 * Register an style file.
	 *
	 * @param string $id       Unique identifier.
	 * @param string $rel_path Relative path to the style file.
	 *
	 * @since 1.0.0
	 * @throws Module\Exception
	 * @return bool
	 */
	public static function style( string $id, string $rel_path ): bool {

		if ( strpos( $id, ':' ) === false ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Invalid ID' );
		}

		[ $module_id, $style_id ] = explode( ':', $id );

		self::$styles[$module_id][ $style_id ] = compact( 'rel_path', 'module_id', 'style_id' );

		return true;
	}

	/**
	 * Get registered scripts list.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function scripts(): array {

		return self::$scripts;
	}


	/**
	 * Get registered styles list.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function styles(): array {

		return self::$styles;
	}

	/**
	 * Flush registered items.
	 *
	 * @param bool $scripts
	 * @param bool $styles
	 *
	 * @since 1.0.0
	 */
	public static function flush( $scripts = true, $styles = true ): void {

		if ( $scripts ) {
			self::$scripts = [];
		}

		if ( $styles ) {
			self::$styles = [];
		}
	}
}
