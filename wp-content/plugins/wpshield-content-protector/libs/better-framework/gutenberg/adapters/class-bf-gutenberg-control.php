<?php

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class BF_Gutenberg_Control extends BF_Gutenberg_Field_Transformer {


	/**
	 * @var ControlStandard\StandardControl|null
	 */
	protected $control;

	public function init( $field, &$results ) {

		parent::init( $field, $results );

		$type = $field['type'];

		$this->control = \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory( $type );
	}

	/**
	 * @param int $iteration
	 *
	 * @return array
	 */
	public function transform_field( $iteration ): array {

		$props = $this->control ? $this->control->props_init( $this->field ) : $this->field;

		if ( isset( $props['deferred-options'] ) && $props['deferred-options'] instanceof \Closure ) {

			unset( $props['deferred-options'] );
		}

		return $props;
	}

	public function items() {

		return in_array( $this->field['type'], [ 'sorter', 'sorter_checkbox' ], true ) ? [ 'type' => 'object' ] : false;
	}

	/**
	 * Return value data type.
	 *
	 * @since 3.9.0
	 * @return string
	 */
	public function data_type() {

		// select backward compatibility
		if ( isset( $this->field['type'] ) && $this->field['type'] === 'select' ) {

			return 'string';
		}

		if ( $this->control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveData ) {

			return $this->control->data_type();
		}

		return '';
	}

	public function dynamic_values_indexes(): array {

		if ( $this->control instanceof \BetterFrameworkPackage\Component\Standard\Control\ManageControlData ) {

			return $this->control->dynamic_values_indexes();
		}

		return [];
	}

	public function settings(): array {

		$settings = parent::settings();

		if ( in_array(
			$this->field['type'],
			[
				'radio',
				'select_popup',
				'image_select',
				'advance_select',
				'sorter',
				'sorter_checkbox',
			],
			true
		) ) {
			$settings['keep_previous_value'] = true;
		}

		return $settings;
	}
}

