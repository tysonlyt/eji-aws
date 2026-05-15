<?php

namespace BetterFrameworkPackage\Utils\Validator;

use BetterFrameworkPackage\Core;

/**
 * Validator rule object factory.
 *
 * @since   1.0.0
 * @package BetterStudio\Utils\Validator
 * @format  Core Module
 */
final class Factory {

	/**
	 * @since 1.0.0
	 * @var array
	 */
	protected static $registered = [];

	/**
	 * @param string $rule
	 *
	 * @since 1.0.0
	 * @throws Core\Exception
	 * @return Rules\Rule|null
	 */
	public static function rule( string $rule ) {

		$instance = null;
		/**
		 * @var Rules\Rule $instance
		 */

		if ( isset( static::$registered[ $rule ] ) ) {

			if ( is_string( static::$registered[ $rule ] ) ) {

				$class_name = static::$registered[ $rule ];
				$instance   = new $class_name;

			} else {

				$instance = static::$registered[ $rule ];
			}

		} else {

			$class = sprintf( '%s\\Rules\\%s', __NAMESPACE__, ucfirst( $rule ) );

			if ( class_exists( $class ) ) {

				$instance = new $class();
			}
		}

		return $instance;
	}

	/**
	 * @param string            $id
	 * @param string|Rules\Rule $class class name or instance.
	 *
	 * @return bool true on success
	 */
	public static function register( $id, $class )
	: bool {

		static::$registered[ $id ] = $class;

		return true;
	}
}