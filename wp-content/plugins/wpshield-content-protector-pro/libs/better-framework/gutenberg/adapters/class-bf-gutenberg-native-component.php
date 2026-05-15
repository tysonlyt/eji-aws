<?php

class BF_Gutenberg_Native_Component extends BF_Gutenberg_Field_Transformer {

	/**
	 * @param int $iteration
	 *
	 * @return array
	 */
	public function transform_field( $iteration ): array {

		return $this->field['component_props'] ?? $this->field;
	}

	/**
	 * The component name.
	 *
	 * @return string
	 */
	public function component() {

		return $this->field['component'];
	}


	/**
	 * Return value data type.
	 *
	 * @since 3.9.0
	 * @return string
	 */
	public function data_type() {

		if ( isset( $this->field['component_data_type'] ) ) {

			return 'component_data_type';
		}

		$info      = include __DIR__ . '/native-components.php';
		$component = $this->component();

		return $info[ $component ]['data_type'] ?? '';
	}
}
