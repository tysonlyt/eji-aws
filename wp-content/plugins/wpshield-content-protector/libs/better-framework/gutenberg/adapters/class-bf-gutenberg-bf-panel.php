<?php

class BF_Gutenberg_BF_Panel extends BF_Gutenberg_Field_Transformer {

	/**
	 * @var int
	 */
	protected $level = 0;

	/**
	 * @var mixed[]
	 */
	protected $breadcrumbs = [];

	/**
	 * @var bool
	 */
	public $wrap_section_container = false;


	/**
	 * @param int $iteration
	 *
	 * @return array{id: mixed, title: mixed, level: mixed, breadcrumbs: mixed, initialOpen: bool}
	 */
	public function transform_field( $iteration ): array {

		if ( ! empty( $this->field['nested'] ) ) {

			$parsed = $this->check_parents( $this->results );
		}

		return [
			'id'          => $this->field['id'] ?? '',
			'title'       => $this->field['name'],
			'level'       => $parsed['level'] ?? 0,
			'breadcrumbs' => $parsed['breadcrumbs'] ?? '',
			'tab'         => $this->field['tab'] ?? '',
			'initialOpen' => ( isset( $this->field['state'] ) && 'open' === $this->field['state'] ) || 1 === $iteration,
		];
	}


	/**
	 * The component name.
	 *
	 * @return string
	 */
	public function component() {

		return 'bf_panel_body';
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


	/**
	 * @return array{level: mixed, breadcrumbs: string}
	 */
	protected function check_parents( &$results ) {

		foreach ( $results as $item ) {

			if ( isset( $item['component'] ) && 'bf_panel_body' === $item['component'] ) {

				$this->level ++;
				$this->breadcrumbs[] = $item['args']['title'] ?? '';
			}

			if ( isset( $item['children'] ) ) {

				$this->check_parents( $item['children'] );
			}
		}

		return [
			'level'       => $this->level,
			'breadcrumbs' => implode( '///', $this->breadcrumbs ),
		];
	}
}
