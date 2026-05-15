<?php

namespace BetterFrameworkPackage\Component\Integration\Control;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

interface ControlIntegration {

	/**
	 * Register a control.
	 *
	 * @param ControlStandard\StandardControl $control
	 *
	 * @since 1.0.0
	 * @return bool true on success or false otherwise.
	 */
	public function register( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): bool;

	/**
	 * @since 1.0.0
	 * @return bool
	 */
	public static function is_enable(): bool;
}
