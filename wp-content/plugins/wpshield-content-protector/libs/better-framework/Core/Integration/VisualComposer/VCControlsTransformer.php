<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\VisualComposer;

// use controls lists
use BetterFrameworkPackage\Component\Control;

class VCControlsTransformer {

	/**
	 * Store the controls iterator.
	 *
	 * @var array
	 * @since 4.0.0
	 */
	protected $controls;

	/**
	 * Controls default values.
	 *
	 * @var array
	 * @since 4.0.0
	 */
	protected $defaults;

	/**
	 * ControlsTransformer constructor.
	 *
	 * @param array $controls The block controls list.
	 */
	public function __construct( array $controls, array $defaults = [] ) {

		$this->controls = $controls;
		$this->defaults = $defaults;
	}


	/**
	 * @since 4.0.0
	 * @return array
	 */
	public function controls(): array {

		return $this->controls;
	}

	/**
	 * @return array.
	 */
	public function transform(): array {

		// Store the current tab label
		$active_tab = __( 'General', 'better-studio' );

		$controls_list = [];
		foreach ( $this->controls as  $control ) {

			if ( ! isset( $control['type'] ) ) {

				continue;
			}

			if ( $control['type'] === 'tab' ) {

				$active_tab = $control['label'] ?? $control['name'];

				continue;
			}

			$control['group'] = $active_tab;

			$controls_list[] = $this->normalize( $control );
		}

		return $controls_list;
	}

	/**
	 * @param array &$control
	 *
	 * @since 4.0.0
	 */
	protected function normalize( array &$control ): array {

		if ( isset( $control['override-vc'] ) ) {

			$control = array_merge( $control, $control['override-vc'] );

			unset( $control['override-vc'] );
		}

		$replacements = [
			// 'block id' => 'vc key'
			'vc_admin_label' => 'admin_label',
			'label'          => 'heading',
			'name'           => 'heading',
			'desc'           => 'description',
			'id'             => 'param_name',
		];

		foreach ( array_intersect_key( $replacements, $control ) as $key => $new_key ) {

			$control[ $new_key ] = $control[ $key ];
			unset( $control[ $key ] );
		}

		if ( \BetterFrameworkPackage\Component\Control\control_exists( $control['type'] ) ) {
			$control['type'] = 'bs-' . $control['type'];
		}

		$id = $control['param_name'] ?? '';

		if ( $id && isset( $this->defaults[ $id ] ) ) {

			$control['value'] = $this->defaults[ $id ];
		}

		return $control;
	}
}
