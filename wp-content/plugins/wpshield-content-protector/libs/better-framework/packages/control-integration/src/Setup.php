<?php

namespace BetterFrameworkPackage\Component\Integration\Control;

// use core APIs
use \BetterFrameworkPackage\Core\{
	Module
};

class Setup {

	use \BetterFrameworkPackage\Core\Module\Singleton;

	/**
	 * Store list of controls instances or class names.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected static $controls = [];

	/**
	 * Store all controls integrations module instances.
	 *
	 * @var ControlIntegration[]
	 *
	 * @since 1.0.0
	 */
	protected static $instances;

	/**
	 * Setup module.
	 *
	 * @since 1.0.0
	 */
	public static function setup(): bool {

		if ( is_admin() ) { // in admin or ajax requests

			add_action( 'init', [ static::instance(), 'integrate' ] );

		} else {

			add_action( 'template_redirect', [ static::instance(), 'integrate' ] );
		}

		do_action( 'better-studio/controls/setup' );

		return true;
	}

	/**
	 * Get control integration instance if it's already registered.
	 *
	 * @param string $integration_id
	 *
	 * @since 1.0.0
	 * @return ControlIntegration|null
	 */
	public static function factory( string $integration_id ): ?\BetterFrameworkPackage\Component\Integration\Control\ControlIntegration {

		return static::$instances[ $integration_id ] ?? null;
	}

	/**
	 * @since 1.0.0
	 */
	public function integrate(): void {

		foreach ( apply_filters( 'better-studio/controls/integration/list', [] ) as $integration_id => $integration ) {

			if ( ! $integration::is_enable() ) {

				continue;
			}

			static::$instances[ $integration_id ] = \is_object( $integration ) ? $integration :
				new $integration();

			$this->register_controls( static::$instances[ $integration_id ] );
		}
	}

	/**
	 * @param ControlIntegration $integration
	 *
	 * @since 1.0.0
	 */
	public function register_controls( \BetterFrameworkPackage\Component\Integration\Control\ControlIntegration $integration ): void {

		foreach ( \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::controls() as $control_type => $control ) {

			$control = \is_string( $control ) ? \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory( $control_type ) : $control;

			$control && $integration->register(
				$control
			);
		}
	}
}
