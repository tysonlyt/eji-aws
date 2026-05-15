<?php

namespace BetterFrameworkPackage\Component\Integration\Block;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

// use core APIs
use \BetterFrameworkPackage\Core\{
	Module
};

// use asset-loader APIs
use \BetterFrameworkPackage\Asset\{
	Enqueue
};

class Setup implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	use \BetterFrameworkPackage\Core\Module\Singleton;

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

		do_action( 'better-studio/blocks/setup' );

		return true;
	}


	/**
	 * @since 1.0.0
	 */
	public function integrate(): void {

		/**
		 * @var string|object $integration
		 */

		foreach ( apply_filters( 'better-studio/blocks/integration/list', [] ) as $integration ) {

			$this->register(
				\is_object( $integration ) ? $integration :
					new $integration(
						\BetterFrameworkPackage\Asset\Enqueue\EnqueueScript::instance(),
						\BetterFrameworkPackage\Asset\Enqueue\EnqueueStyle::instance()
					)
			);
		}
	}

	/**
	 * @param BlockStandards\BlockIntegrationInterface $integration
	 *
	 * @since 1.0.0
	 */
	public function register( \BetterFrameworkPackage\Component\Standard\Block\BlockIntegrationInterface $integration ): void {

		$registrar = new \BetterFrameworkPackage\Component\Integration\Block\IntegrationFireUp( $integration );

		$registrar->register( \BetterFrameworkPackage\Component\Standard\Block\BlockStorage::blocks() );
	}
}
