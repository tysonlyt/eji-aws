<?php

namespace BetterFrameworkPackage\Component\Control\MultipleControls;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

use \BetterFrameworkPackage\Component\Control\{
	Features\ProFeature
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class MultipleControls extends \BetterFrameworkPackage\Component\Control\BaseControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'multiple_controls';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @param array $props
	 * @param array $render_options
	 *
	 * @since 1.0.0
	 */
	public function render_items( array &$props ): void {

		$controls = $this->controls( $props );

		if ( isset( $props['name_format'] ) ) {

			$name_format = $props['name_format'];

		} else {

			$name_format = str_replace( $props['id'], '{{control_id}}', $props['input_name'] );
		}

		foreach ( \BetterFrameworkPackage\Component\Control\render_controls_list( $controls, $name_format, $this->options() ) as $control ) {

			echo $control;
		}
	}

	protected function controls( array &$props ): array {

		if ( empty( $props['controls'] ) || ! is_array( $props['controls'] ) ) {

			return [];
		}
		$controls = $props['controls'] ?? [];

		foreach ( $controls as $index => $control ) {

			if ( ! isset( $control['container_attributes'] ) ) {

				$controls[ $index ]['container_attributes'] = [];
			}

			if ( isset( $control['classes'] ) ) {

				$controls[ $index ]['classes'] = (array) $control['classes'];
			} else {

				$controls[ $index ]['classes'] = [];
			}

			if ( ! isset( $controls[ $index ]['container_class'] ) ) {
				$controls[ $index ]['container_class'] = '';
			}

			$controls[ $index ]['multiple_controls_item']                              = true;
						$controls[ $index ]['container_attributes']['data-param-type'] = $control['type'];
						$controls[ $index ]['container_class']                        .= ' bf-multiple-controls-item-container';
			$controls[ $index ]['classes'][] = 'bf-multiple-controls-item';

			$controls[ $index ]['value'] = $props['value'][ $control['id'] ] ?? null;

		}

		return $controls;
	}
}
