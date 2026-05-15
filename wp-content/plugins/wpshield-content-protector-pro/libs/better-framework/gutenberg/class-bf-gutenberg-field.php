<?php


abstract class BF_Gutenberg_Field_Transformer {

	/**
	 * @var bool
	 */
	public $wrap_section_container = true;

	/**
	 * @var array
	 */
	protected $field;


	/**
	 * @var mixed|null
	 */
	protected $results;

	/**
	 *
	 * @param int $iteration
	 *
	 * @return array
	 */
	abstract public function transform_field( $iteration );


	/**
	 * The component name.
	 *
	 * @return string
	 */
	public function component() {

		return $this->field( 'type' );
	}


	/**
	 * Return value data type.
	 *
	 * @return string
	 */
	public function data_type() {

		return 'string';
	}

	/**
	 * @param array $field
	 */
	public function init( $field, &$results ) {

		$this->init_tabs( $field );

		$this->field   = $field;
		$this->results = $results;
	}


	/**
	 * @param string $index
	 *
	 * @return mixed
	 */
	public function field( $index = '' ) {

		if ( $index ) {

			return $this->field[ $index ] ?? null;
		}

		return $this->field;
	}


	/**
	 * @return bool|array
	 */
	public function items() {

		return false;
	}

	/**
	 * @link https://wordpress.org/gutenberg/handbook/block-api/attributes/
	 *
	 * @param array $parent parent tab. optional
	 *
	 * @return array
	 */
	public function the_attribute( $parent = [] ) {

		$type = $this->data_type();

		if ( $type ) {

			$items      = $this->items(); // it will fix Undefined index: items in wp-includes/rest-api.php:093
			$for_blocks = $parent['include_blocks'] ?? [];
			$component  = $this->component();

			return compact( 'type', 'items', 'for_blocks', 'component' );
		}

		return [];
	}

	public function init_tabs( array &$field ) {

		if ( ! isset( $field['tabs'], $field['tab'] ) ) {

			return $field;
		}

		if ( ! isset( $field['show_on'] ) ) {

			$field['show_on'] = [ [] ];
		}

		foreach ( $field['show_on'] as $index => $condition ) {

			$field['show_on'][ $index ][] = $field['tabs'] . '=' . $field['tab'];

			if ( ! isset( $field['show_on_type'][ $index ] ) ) {

				$field['show_on_type'][ $index ] = 'hide';
			}
		}

		$this->append_classes( $field, 'bf-tab-item' );

		return $field;
	}

	public function append_classes( &$field ) {

		$classes = array_slice( func_get_args(), 1 );

		if ( isset( $field['classes'] ) ) {

			$field['classes'] = (array) $field['classes'];

		} else {

			$field['classes'] = [];
		}

		$field['classes'] = array_merge( $field['classes'], $classes );
	}

	/**
	 * @return array
	 */
	public function children_items_list() {

		return [];
	}


	public function children_item( $item ) {

		return $item;
	}

	public function dynamic_values_indexes(): array {

		return [];
	}

	public function settings(): array {

		return [];
	}

	public function tab_panel():string {

		return $this->field['tab'] ?? '';
	}
}
