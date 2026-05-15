<?php

namespace BetterFrameworkPackage\Framework\Core\Integration;

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

/**
 * @since 4.0.0
 */
class ControlsIntegration implements \BetterFrameworkPackage\Component\Integration\Control\ControlIntegration {

	/**
	 * Store BlockAssets instance
	 *
	 * @var ControlStandard\ControlAssets
	 * @since 4.0.0
	 */
	protected $assets;


	/**
	 * @since 4.0.0
	 */
	public function __construct() {

		$this->assets = new \BetterFrameworkPackage\Component\Standard\Control\ControlAssets( \BetterFrameworkPackage\Framework\Core\Integration\EnqueueScript::instance(), \BetterFrameworkPackage\Framework\Core\Integration\EnqueueStyle::instance() );
	}

	/**
	 * @param ControlStandard\StandardControl $control
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public function register( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): bool {

		$this->assets->enqueue_js( $control );
		$this->assets->enqueue_css( $control );

		return true;
	}

	/**
	 * @since 4.0.0
	 * @return bool
	 */
	public static function is_enable(): bool {

		return is_admin();
	}
}
