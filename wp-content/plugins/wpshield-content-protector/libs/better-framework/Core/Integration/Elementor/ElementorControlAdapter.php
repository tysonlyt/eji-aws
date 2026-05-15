<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

use Elementor;

class ElementorControlAdapter extends Elementor\Base_Data_Control {

	/**
	 * Store the standard control instance.
	 *
	 * @var ControlStandard\StandardControl
	 * @since 4.0.0
	 */
	protected $control;

	/**
	 * @param ControlStandard\StandardControl $control
	 *
	 * @since 4.0.0
	 */
	public function __construct( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ) {

		parent::__construct();

		$this->control = $control;
	}

	/**
	 * Retrieve the control type.
	 *
	 * @since 4.0.0
	 */
	public function get_type():string {

		return 'bf-' . $this->control->control_type();
	}

	/**
	 * Get the control Underscore JS template.
	 *
	 * @since 4.0.0
	 */
	public function content_template():void {

		if ( $this->control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveUnderscoreTemplate ) {

			echo $this->control->underscore_template();
		}
	}

	/**
	 * Get the standard control instance.
	 *
	 * @since 4.0.0
	 * @return ControlStandard\StandardControl
	 */
	public function control_instance(): \BetterFrameworkPackage\Component\Standard\Control\StandardControl {

		return $this->control;
	}
}
