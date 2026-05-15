<?php

namespace BetterFrameworkPackage\Framework\Core;

// use core APIs
use \BetterFrameworkPackage\Core\{
	Module
};

/**
 * Setup better framework core modules.
 *
 * @since 4.0.0
 */
class Setup implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	/**
	 * Setup better framework core modules.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public static function setup(): bool {

		\BetterFrameworkPackage\Framework\Core\Integration\Setup::setup();

		self::default_filters();

		return true;
	}

	public static function default_filters(): void {

		add_filter( 'better-framework/field-generator/field', 'bs_sanitize_field_props', 3, 2 );
	}
}
