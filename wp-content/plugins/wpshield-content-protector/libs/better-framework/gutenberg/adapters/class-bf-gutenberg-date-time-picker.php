<?php


class BF_Gutenberg_Date_Time_Picker extends BF_Gutenberg_Field_Transformer {

	/**
	 * @param int $iteration
	 *
	 * @return array{label: mixed, id: mixed}
	 */
	public function transform_field( $iteration ): array {

		$label = $this->field['name'] ?? '';
		$id    = $this->field['id'] ?? '';

		return compact( 'label', 'id' );
	}


	/**
	 * The component name.
	 *
	 * @return string
	 */
	public function component() {

		return 'DateTimePicker';
	}


	/**
	 * Return value data type.
	 *
	 * @since 3.9.0
	 * @return string
	 */
	public function data_type() {

		return 'string';
	}
}
