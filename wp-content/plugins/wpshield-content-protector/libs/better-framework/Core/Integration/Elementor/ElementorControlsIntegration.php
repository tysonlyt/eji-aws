<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandards
};

use \BetterFrameworkPackage\Framework\Core\Integration\{
	EnqueueStyle,
	EnqueueScript
};

use Elementor as ElementorPlugin;

class ElementorControlsIntegration implements \BetterFrameworkPackage\Component\Integration\Control\ControlIntegration {

	/**
	 * Store BlockAssets instance
	 *
	 * @var ControlStandards\ControlAssets
	 * @since 4.0.0
	 */
	protected $assets;

	/**
	 * Store control instances.
	 *
	 * @var ControlStandards\StandardControl[]
	 * @since 4.0.0
	 */
	protected $controls = [];

	/**
	 * Setup module.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {

		if ( ! has_action( 'elementor/controls/controls_registered', [ $this, 'register_elementor_control' ] ) ) {

			add_action( 'elementor/controls/controls_registered', [ $this, 'register_elementor_control' ] );
		}

		$this->assets = new \BetterFrameworkPackage\Component\Standard\Control\ControlAssets( \BetterFrameworkPackage\Framework\Core\Integration\EnqueueScript::instance(), \BetterFrameworkPackage\Framework\Core\Integration\EnqueueStyle::instance() );
	}

	/**
	 * @param ControlStandards\StandardControl $control
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public function register( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): bool {

		$this->controls[] = $control;

		$this->assets->enqueue_js( $control );
		$this->assets->enqueue_css( $control );

		return true;
	}


	/**
	 * Register custom controls type.
	 *
	 * @param ElementorPlugin\Controls_Manager $controls_manager
	 *
	 * @since 4.0.0
	 */
	public function register_elementor_control( ElementorPlugin\Controls_Manager $controls_manager ): void {

		foreach ( $this->controls as $control ) {

			$controls_manager->register_control(
				'bf-' . $control->control_type(),
				$this->elementor_control( $control )
			);
		}
	}

	/**
	 * Get elementor compatible of the standard control instance.
	 *
	 * @param ControlStandards\StandardControl $control
	 *
	 * @since 4.0.0
	 * @return ElementorControlAdapter
	 */
	public function elementor_control( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): \BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorControlAdapter {

		return new \BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorControlAdapter( $control );
	}

	/**
	 * Is elementor plugin active?
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public static function is_enable(): bool {

		return \defined( 'ELEMENTOR_VERSION' ) && ELEMENTOR_VERSION;
	}
}
