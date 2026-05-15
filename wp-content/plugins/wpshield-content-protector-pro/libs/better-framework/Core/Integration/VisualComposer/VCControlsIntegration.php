<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\VisualComposer;

// use integration APIs
use BetterFrameworkPackage\Core\Module\Exception;
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandards
};

// use controls api
use BetterFrameworkPackage\Component\Control;

class VCControlsIntegration implements \BetterFrameworkPackage\Component\Integration\Control\ControlIntegration {


	public function register( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control ): bool {

		return vc_add_shortcode_param(
			'bs-' . $control->control_type(),
			[ $this, 'render' ]
		);
	}


	/**
	 * @return string
	 */
	public function render( $props, $value ): string {

		if ( ! isset( $props['type'] ) || ! preg_match( '/^bs\-(?P<control_type>.+)$/', $props['type'], $match ) ) {

			return '';
		}

		if ( $control = \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory( $match['control_type'] ) ) {

			return $this->render_control( $control, $props ?? [], $value );
		}

		return '';
	}

	/**
	 * @param ControlStandards\StandardControl $control
	 * @param array                            $props
	 * @param mixed                            $value
	 *
	 * @return string
	 */
	protected function render_control( \BetterFrameworkPackage\Component\Standard\Control\StandardControl $control, array $props, $value ): string {

		if ( ! $control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveRenderDynamic ) {

			return '';
		}

		if ( ! isset( $props['input_class'] ) ) {
			$props['input_class'] = '';
		}

		$props['value']        = $value;
		$props['input_name']   = $props['param_name'];
		$props['input_class'] .= ' wpb_vc_param_value';

		if ( ! empty( $props['heading'] ) ) {

			$props['name'] = $props['heading'];
		}

		$section_class = isset( $options['section_class'] ) ? esc_attr( $options['section_class'] ) : '';

		try {

			return sprintf(
				'<div class="bf-section-container vc-input bf-clearfix %s" data-param-type="%s">%s</div>',
				$section_class,
				esc_attr( $control->control_type() ),
				\BetterFrameworkPackage\Component\Control\render_control( $control, $props )
			);
		} catch ( \BetterFrameworkPackage\Core\Module\Exception $e ) {

			return 'vc control render error: ' . $e->getMessage();
		}
	}

	/**
	 * @return bool
	 */
	public static function is_enable(): bool {

		return \BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\VCBlocksIntegration::is_enable() && \BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\VCBlocksIntegration::is_edit_form();
	}
}
