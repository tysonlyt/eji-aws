<?php

namespace BetterFrameworkPackage\Component\Control\Repeater;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

use BetterFrameworkPackage\Component\Control as LibRoot;

class RepeaterControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveScripts, \BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'repeater';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @param array  $controls
	 * @param string $input_format
	 * @param array  $values
	 *
	 * @since 1.0.0
	 */
	public function render_items( array $controls, string $input_format, string $input_classes = '', array $values = [], array $render_options = [] ): void {

		foreach ( $controls as $index => $control ) {

			// nested repeater does not support
			if ( $control['type'] === 'repeater' ) {

				unset( $controls[ $index ] );
				continue;
			}

			if ( ! isset( $control['input_class'] ) ) {

				$controls[ $index ]['input_class'] = '';
			}

			$controls[ $index ]['value']         = $values[ $control['id'] ] ?? null;
			$controls[ $index ]['repeater_item'] = true;
			$controls[ $index ]['input_class']  .= $input_classes;
		}

		foreach ( \BetterFrameworkPackage\Component\Control\render_controls_list( $controls, $input_format, $render_options ) as $control ) {

			echo $control;
		}
	}

	public function scripts_list(): array {

		return [
			[
				'id' => 'jquery-ui-sortable',
			],
		];
	}

	public function data_type(): string {

		return 'array';
	}


	/**
	 * @param array $options
	 *
	 * @return array
	 */
	protected function normalize_options( array $options ): array {

		$formatted = [];

		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {

				continue;
			}

			$formatted[ $option['id'] ] = $option;
		}

		return $formatted;
	}

	public function modify_save_value( $value, array $props = [] ) {

		if ( empty( $value ) || ! is_array( $value ) || ! isset( $props['options'] ) ) {

			return $value;
		}

		$options = $this->normalize_options( $props['options'] );

		foreach ( $value as $index => $item_values ) {

			if ( ! is_array( $item_values ) ) {

				continue;
			}

			foreach ( $item_values as $id => $control_value ) {

				$control_props = $options[ $id ] ?? null;

				if ( ! isset( $control_props['type'] ) ) {

					continue;
				}

				$value[ $index ][ $id ] = \BetterFrameworkPackage\Component\Control\filter_control_value( $control_props['type'], $control_value, $control_props );
			}
		}

		return $value;
	}
}
