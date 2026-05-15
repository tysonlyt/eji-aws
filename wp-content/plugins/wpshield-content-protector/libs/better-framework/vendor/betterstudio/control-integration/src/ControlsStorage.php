<?php

namespace BetterFrameworkPackage\Component\Integration\Control;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class ControlsStorage {

	/**
	 * Store active controls instance.
	 *
	 * @var ControlStandard\StandardControl[]
	 *
	 * @since 1.0.0
	 */
	protected static $controls = [];

	/**
	 * Get the control instance.
	 *
	 * @param string $control_type the control ID.
	 *
	 * @since 1.0.0
	 * @return ControlStandard\StandardControl|null object on success.
	 */
	public static function factory( string $control_type, array $options = [] ): ?\BetterFrameworkPackage\Component\Standard\Control\StandardControl {

		$control = static::$controls[ $control_type ] ?? null;

		if ( $control instanceof \BetterFrameworkPackage\Component\Standard\Control\StandardControl ) {

			$control->load_options( $options );

			return $control;
		}

		return \is_string( $control ) ? new $control( $options ) : null;
	}

	/**
	 * Is control registered.
	 *
	 * @param string $control_type
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function exists( string $control_type = '' ): bool {

		return ! empty( static::$controls[ $control_type ] );
	}

	/**
	 * @param string                                 $control_type
	 * @param ControlStandard\StandardControl|string $control
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function register( string $control_type, $control ): bool {

		if ( ! isset( static::$controls[ $control_type ] ) ) {

			static::$controls[ $control_type ] = $control;

			return true;
		}

		return false;
	}


	/**
	 * Clear registered controls.
	 *
	 * @since 1.0.0
	 */
	public static function flush(): void {

		static::$controls = [];
	}

	/**
	 * List of registered controls.
	 *
	 * @since 1.0.0
	 * @return ControlStandard\StandardControl[]|string[]
	 */
	public static function controls(): array {

		return apply_filters( 'better-studio/controls/list', static::$controls );
	}
}
