<?php

class BF_Gutenberg_Navigator_Provider extends BF_Gutenberg_Field_Transformer {

	/**
	 * @var bool
	 */
	public $wrap_section_container = false;

	public function transform_field( $iteration ): array {

		return [
			'tab'         => $this->field['tab'] ?? '',
			'initialPath' => $this->field['init-path'] ?? '',
		];
	}

	public function component(): string {

		return 'NavigatorProvider';
	}

	public function data_type() {

		return '';
	}
}
