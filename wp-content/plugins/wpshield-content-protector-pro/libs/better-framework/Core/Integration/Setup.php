<?php

namespace BetterFrameworkPackage\Framework\Core\Integration;

// use core APIs
use \BetterFrameworkPackage\Core\{
	Module
};

use \BetterFrameworkPackage\Component\Integration\{
	Block as BlockIntegration,
	Control as ControlIntegration
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BLockStandard
};


// use controls lists
use BetterFrameworkPackage\Component\Control;

// use old fashion APIs
use BF_Shortcodes_Manager;

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

		add_filter( 'better-studio/blocks/integration/list', [ self::class, 'introduce_blocks_integrations' ] );
		add_filter( 'better-studio/controls/integration/list', [ self::class, 'introduce_fields_integrations' ] );

		// introduce old shortcode classes to new block API.
		add_action( 'better-framework/shortcodes/loaded', [ self::class, 'register_blocks' ] );

		// Integrate controls, blocks with page builder support.
		\BetterFrameworkPackage\Component\Integration\Block\Setup::setup();
		\BetterFrameworkPackage\Component\Integration\Control\Setup::setup();

		// Register standard controls
		\BetterFrameworkPackage\Component\Control\Setup::setup();
		\BetterFrameworkPackage\Framework\Core\Integration\Elementor\Setup::setup();
		\BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\Setup::setup();

		return true;
	}

	/**
	 * Introduce BF block/shortcode integration classes.
	 *
	 * @hooked better-studio/blocks/integration/list
	 * @since  4.0.0
	 */
	public static function introduce_blocks_integrations( array $integration ): array {

		$integration['bf-shortcodes'] = \BetterFrameworkPackage\Framework\Core\Integration\ShortcodesIntegration::class;

		return $integration;
	}


	/**
	 * Introduce BF controls/fields integration class.
	 *
	 * @hooked better-studio/controls/integration/list
	 * @since  4.0.0
	 */
	public static function introduce_fields_integrations( array $integration ): array {

		$integration['bf-shortcodes'] = \BetterFrameworkPackage\Framework\Core\Integration\ControlsIntegration::class;

		return $integration;
	}

	/**
	 * @since 4.0.0
	 */
	public static function register_blocks(): void {

		foreach ( BF_Shortcodes_Manager::shortcodes_list() as $shortcode_id => $_ ) {

			\BetterFrameworkPackage\Component\Standard\Block\BlockStorage::register(
				$shortcode_id,
				new \BetterFrameworkPackage\Framework\Core\Integration\BlockAdapter( $shortcode_id )
			);
		}
	}
}
