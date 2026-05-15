<?php

class BF_Gutenberg_Tabs extends BF_Gutenberg_Control {

	/**
	 * @var bool
	 */
	public $wrap_section_container = false;

	public function transform_field( $iteration ): array {

		return [
			'id'          => $this->field['id'] ?? '',
			'tabs'        => $this->field['tabs'] ?? [],
			'initialOpen' => ( isset( $this->field['state'] ) && 'open' === $this->field['state'] ) || 1 === $iteration,
		];
	}

	public function component(): string {

		return 'BFTabPanel';
	}

	public function data_type() {

		return '';
	}
}
