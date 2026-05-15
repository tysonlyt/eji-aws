<?php

class BF_Gutenberg_Navigator extends BF_Gutenberg_Field_Transformer {

	/**
	 * @var bool
	 */
	public $wrap_section_container = false;

	/**
	 * Store the level of tree.
	 *
	 * @var int
	 */
	protected $level;

	/**
	 * Store the breadcrumb of navigator item.
	 *
	 * @var array
	 */
	protected $breadcrumbs;

	public function transform_field( $iteration ): array {

		if ( ! empty( $this->field['nested'] ) ) {

			$parsed = $this->check_parents( $this->results );
		}

		return [
			'level'       => $parsed['level'] ?? 0,
			'root'        => $this->field['root'] ?? '/',
			'id'          => $this->field['id'] ?? '',
			'tab'         => $this->field['tab'] ?? '',
			'path'        => $this->field['path'] ?? '',
			'title'       => $this->field['name'] ?? '',
			'description' => $this->field['desc'] ?? '',
			'breadcrumbs' => $parsed['breadcrumbs'] ?? '',
		];
	}

	public function component(): string {

		return 'Navigator';
	}

	public function data_type() {

		return '';
	}

	/**
	 * @return array{level: mixed, breadcrumbs: string}
	 */
	protected function check_parents( array $results ) {

		foreach ( $results as $item ) {

			if ( isset( $item['children'] ) ) {

				$child      = array_column( $item['children'], 'id' );
				$is_include = in_array( $this->field['id'], $child, true );
			}

			if ( ! isset( $is_include ) || ! $is_include ) {

				continue;
			}

			if ( isset( $item['component'] ) && 'Navigator' === $item['component'] ) {

				$this->level ++;
				$this->breadcrumbs[] = $item['args']['title'] ?? '';
			}

			if ( isset( $item['children'] ) ) {

				$this->check_parents( $item['children'] );
			}
		}

		return [
			'level'       => $this->level,
			'breadcrumbs' => implode( '///', $this->breadcrumbs ?? [] ),
		];
	}
}
