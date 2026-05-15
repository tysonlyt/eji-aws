<?php


class BF_Gutenberg_Panel_Body extends BF_Gutenberg_Field_Transformer {

	/**
	 * @var bool
	 */
	public $wrap_section_container = false;

	/**
	 * @var bool
	 */
	public $first_item = true;


	/**
	 * @param int $iteration
	 *
	 * @return array{id: mixed, title: mixed, initialOpen: bool}
	 */
	public function transform_field( $iteration ): array {

		return [
			'id'          => $this->field['id'] ?? '',
			'title'       => $this->field['name'],
			'initialOpen' => ( isset( $this->field['state'] ) && 'open' === $this->field['state'] ) || 1 === $iteration,
		];
	}


	/**
	 * The component name.
	 *
	 * @return string
	 */
	public function component() {

		return 'PanelBody';
	}


	/**
	 * Return value data type.
	 *
	 * @since 3.9.0
	 * @return string
	 */
	public function data_type() {

		return '';
	}


	public function is_first_panel(): bool {

		if ( $this->first_item ) {

			$this->first_item = false;

			return true;
		}

		return false;
	}
}
