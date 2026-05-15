<?php


class BF_Gutenberg_BF_Repeater extends BF_Gutenberg_Field_Transformer {


	/**
	 * @param int $iteration
	 *
	 * @return array
	 */
	public function transform_field( $iteration ): array {

		return $this->field;
	}

	/**
	 * @return array{type: string}
	 */
	public function items(): array {

		return [
			'type' => 'object',
		];
	}


	/**
	 * Return value data type.
	 *
	 * @since 3.9.0
	 * @return string
	 */
	public function data_type() {

		return 'array';
	}


	public function children_items_list() {

		return $this->field['options'] ?? [];
	}


	public function children_item( $item ) {

		$item['repeater_item'] = $this->field['id'];

		return $item;
	}
}
