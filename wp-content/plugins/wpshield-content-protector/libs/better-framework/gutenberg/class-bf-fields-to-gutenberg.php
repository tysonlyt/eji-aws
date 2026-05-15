<?php


class BF_Fields_To_Gutenberg {

	/**
	 * @var array
	 */
	protected $fields = [];

	/**
	 * @var array
	 */
	protected $stds = [];

	/**
	 * @var array
	 */
	protected $outside_items = [];

	/**
	 * BF_Fields_To_Gutenberg constructor.
	 *
	 * @param array $fields
	 * @param array $stds
	 *
	 * @since 3.9.0
	 */
	public function __construct( array $fields = [], $stds = [] ) {

		$this->load_fields( $fields );
		$this->load_stds( $stds );
	}


	/**
	 * Set standard BF fields array.
	 *
	 * @param array $fields
	 *
	 * @since 3.9.0
	 */
	public function load_fields( array $fields ) {

		foreach ( $fields as $id => $field ) {

			if ( isset( $field['override-gutenberg'] ) ) {

				$fields[ $id ] = array_merge( $field, $field['override-gutenberg'] );

				unset( $fields[ $id ]['override-gutenberg'] );
			}
		}

		$this->fields = $fields;
	}


	/**
	 * Set fields default std value.
	 *
	 * @param array $stds
	 *
	 * @since 3.9.0
	 */
	public function load_stds( array $stds ) {

		$this->stds = $stds;
	}


	/**
	 * Get BF fields array.
	 *
	 * @since 3.9.0
	 * @return array
	 */
	public function fields() {

		return $this->fields;
	}


	/**
	 * Transform fields to gutenberg format.
	 *
	 * @param BF_Gutenberg_Field_Transformer|null $parent_field parent object if exists
	 *
	 * @since 3.9.0
	 *
	 * @return array
	 */
	public function transform( BF_Gutenberg_Field_Transformer $parent_field = null ) {

		$container                 = [];
		$results                   = &$container;
		$exclude_specific_wrappers = [ 'tab_panel', 'nav_provider' ];
		$wrapper_types             = [ 'group', 'tab', 'panel', 'navigator' ];
		$close_wrappers            = [ 'group_close', 'panel_end', 'close_navigator', 'close_nav_provider' ];

		$tab_started = false;
		$iteration   = 0;

		$tab_panels_stack   = $this->extract_field_type( $parent_field, 'tab_panel' );
		$nav_provider_stack = $this->extract_field_type( $parent_field, 'nav_provider' );

		foreach ( $this->fields as $field ) {

			if ( empty( $field['type'] ) ) {

				continue;
			}

			$field_type = $field['type'];

			if ( in_array( $field_type, $exclude_specific_wrappers, true ) ) {

				continue;
			}

			// replace wp_editor with textarea
			if ( 'wp_editor' === $field['type'] ) {
				$field['type'] = 'textarea';
			}

			if ( ! isset( $field['std'] ) && isset( $field['id'] ) ) {

				$id = $field['id'];

				if ( isset( $this->stds[ $id ] ) ) {

					$field['std'] = $this->stds[ $id ];
				}
			}

			if ( isset( $field['std'] ) ) {

				$field['default'] = $field['std'];

				unset( $field['std'] );
			}

			if ( in_array( $field_type, $close_wrappers, true ) ) {

				$container = &$results;
				continue;
			}

			if ( 'tab' === $field_type && $tab_started ) {

				$tab_started = false;
				$container   = &$results;
			}

			$factory = $this->factory( $field, $results );

			if ( ! $factory ) {
				continue;
			}

			$this->transform_field( $container, $factory, ++ $iteration, $parent_field );

			if ( in_array( $field_type, $wrapper_types, true ) ) {

				$tab_started = true;

				end( $container );
				$key = key( $container );

				// $results   = &$container;
				$container = &$container[ $key ]['children'];
			}
		}

		if ( ! empty( $nav_provider_stack ) ) {

			$results = $this->register_in_nav_provider( $nav_provider_stack, $results );
		}
		if ( ! empty( $tab_panels_stack ) ) {

			$results = $this->register_in_tab_panels( $tab_panels_stack, $results );
		}

		return $results;
	}

	/**
	 * Extracting all "{$field_type}" fields to clean of these fields from other than!
	 *
	 * @param BF_Gutenberg_Field_Transformer|null $parent_field
	 * @param string                              $field_type
	 *
	 * @since 4.0.0
	 * @return array
	 */
	protected function extract_field_type( BF_Gutenberg_Field_Transformer $parent_field = null, string $field_type ): array {

		$_container = [];
		$_results   = &$_container;
		$iteration  = 0;

		foreach ( $this->fields as $key => $field ) {

			if ( empty( $field['type'] ) || $field_type !== $field['type'] ) {

				continue;
			}

			$factory = $this->factory( $field, $_results );

			if ( ! $factory ) {
				continue;
			}

			$this->transform_field( $_container, $factory, ++ $iteration, $parent_field );

			$stack[]    = array_merge( ...$_container );
			$_container = &$_results;
		}

		return $stack ?? [];
	}

	/**
	 * Register results attributes in navigator provider items if possible!
	 *
	 * @param array $provider_stack
	 * @param array $results
	 *
	 * @since 4.0.0
	 * @return array
	 */
	protected function register_in_nav_provider( array $provider_stack, array $results = [] ): array {

		foreach ( $provider_stack ?? [] as $key => $provider ) {

			if ( ! isset( $provider['component'], $provider['args']['initialPath'] ) || 'NavigatorProvider' !== $provider['component'] ) {
				continue;
			}

			foreach ( $results as $result ) {

				if ( ! isset( $result['component'], $result['args']['root'] ) ) {

					if ( ! in_array( $result, $this->outside_items, true ) ) {

						$this->outside_items[] = $result;
					}

					continue;
				}

				if ( $result['args']['root'] !== $provider['args']['initialPath'] ) {

					continue;
				}

				$provider['children'] = array_merge( $provider['children'] ?? [], [ $result ] );
			}

			//Rewrite stack!
			$provider_stack[ $key ] = $provider;
		}

		return array_merge( $provider_stack ?? [], $this->outside_items );
	}

	/**
	 * Register results attributes in tab panels items if possible!
	 *
	 * @param array $panel_stack
	 * @param array $results
	 *
	 * @since 4.0.0
	 * @return array
	 */
	protected function register_in_tab_panels( array $panel_stack, array $results = [] ): array {

		//Rewrite outside items!
		$this->outside_items = [];

		foreach ( $panel_stack ?? [] as $key => $tab_panel ) {

			if ( ! isset( $tab_panel['component'], $tab_panel['args']['tabs'] ) || 'BFTabPanel' !== $tab_panel['component'] ) {
				continue;
			}

			$tabs = array_column( $tab_panel['args']['tabs'], 'name' );

			foreach ( $results as $result ) {

				if ( ! isset( $result['component'], $result['args']['tab'] ) ) {

					if ( ! in_array( $result, $this->outside_items, true ) ) {

						$this->outside_items[] = $result;
					}

					continue;
				}

				if ( ! in_array( $result['args']['tab'], $tabs, true ) ) {

					continue;
				}

				$tab_panel['children'] = array_merge( $tab_panel['children'] ?? [], [ $result ] );
			}

			//Rewrite tab panels stack!
			$panel_stack[ $key ] = $tab_panel;
		}

		return array_merge( $panel_stack ?? [], $this->outside_items );
	}

	public function list_attributes() {

		$parent  = [];
		$results = [];

		foreach ( $this->fields as $field ) {

			if ( empty( $field['type'] ) ) {
				continue;
			}

			$factory = $this->factory( $field, $results );

			if ( ! $factory ) {
				continue;
			}

			$the_attribute = $factory->the_attribute( $parent );

			if ( $the_attribute ) {

				$results[ $factory->field( 'id' ) ] = $the_attribute;
			}

			if ( 'tab' === $field['type'] ) {

				$parent = $field;
			}
		}

		return $results;
	}


	/**
	 * @param array                          $container
	 * @param BF_Gutenberg_Field_Transformer $field
	 * @param int                            $iteration
	 * @param BF_Gutenberg_Field_Transformer $parent_transformer
	 *
	 * @since 3.9.0
	 * @return bool true on success or false on failure.
	 */
	protected function transform_field( &$container, BF_Gutenberg_Field_Transformer $field, $iteration, BF_Gutenberg_Field_Transformer $parent_transformer = null ): bool {

		$transformed = $field->transform_field( $iteration );

		if ( ! is_array( $transformed ) ) {

			return false;
		}

		$id   = $field->field( 'id' );
		$data = [
			'id'        => $id,
			'key'       => $id,
			'data_type' => $field->data_type(),
			'component' => $field->component(),
			'args'      => $transformed,
		];

		$attribute = $field->the_attribute();

		if ( $attribute ) {

			$data['attribute'] = $attribute;
		}

		$settings = $field->settings();

		if ( $settings ) {

			$data['args']['_settings'] = $settings;
		}

		$shared_keys = array_intersect_key(
			$field->field(),
			[
				'include_blocks' => '',
				'exclude_blocks' => '',
				'only_widgets'   => '',
				'fixed_class'    => '',
				'priority'       => '',
				'action'         => '',
			]
		);

		if ( $shared_keys ) {

			$data = array_merge( $data, $shared_keys );
		}

		$children = $field->children_items_list();

		if ( $children ) {

			$children_handler = new self( $children );

			$children_transformed = $children_handler->transform( $field );

			if ( $children_transformed ) {

				$data['children'] = $children_transformed;
			}
		}

		if ( $parent_transformer ) {

			$data = $parent_transformer->children_item( $data );
		}

		if ( $field->wrap_section_container ) {

			$container[] = $this->wrap_section_container( $field, $data );

		} else {

			$container[] = $data;
		}

		return true;
	}


	/**
	 * @param BF_Gutenberg_Field_Transformer $field
	 * @param array                          $data
	 *
	 * @return array{data_type: string, component: string, children: mixed[][], args: array{type: mixed, label: mixed, id: mixed, name: mixed, container_class?: mixed, section_class?: mixed, description?: mixed, show_on?: mixed[]}, key: string, id: string, dynamic_props?: mixed[]}
	 */
	public function wrap_section_container( $field, $data ) {

		$title = $field->field( 'name' );
		unset( $data['args']['label'] );

		$id = $field->field( 'id' );

		$args = [
			'type'  => $field->field( 'type' ),
			'label' => $title,
			'id'    => $id,
			'name'  => $id,
			'tab'   => $field->tab_panel(),
		];

		$classes = $field->field( 'container_class' );

		if ( $classes ) {
			$args['container_class'] = $classes;
		}

		$classes = $field->field( 'section_class' );

		if ( $classes ) {
			$args['section_class'] = $classes;
		}

		if ( ! empty( $data['args']['desc'] ) ) {
			$args['description'] = $data['args']['desc'];

			unset( $data['args']['desc'] );
		}

		$show_on = $field->field( 'show_on' );

		if ( $show_on ) {

			if ( ! function_exists( 'bf_show_on_attributes' ) ) {
				require BF_PATH . '/core-deprecated/field-generator/functions.php';
			}

			$args['show_on'] = bf_show_on_settings( $field->field() );
		}

		$props = [
			'data_type' => $field->data_type(),
			'component' => 'section_container',
			'children'  => [ $data ],
			'args'      => $args,
			'key'       => "field_$id",
			'id'        => "field_$id",
		];

		$dynamic_props = $field->dynamic_values_indexes();

		if ( $dynamic_props ) {

			$props['dynamic_props'] = $dynamic_props;
		}

		return $props;
	}

	/**
	 * @param array $field
	 * @param array $results
	 *
	 * @since 3.9.0
	 * @return BF_Gutenberg_Field_Transformer on success or null on failure.
	 */
	public function factory( array $field, array &$results ) {

		if ( empty( $field['type'] ) ) {
			return null;
		}

		switch ( $field['type'] ) {

			case 'group':
			case 'tab':
				$instance = new BF_Gutenberg_Panel_Body();

				break;

			case 'panel':
				$instance = new BF_Gutenberg_BF_Panel();

				break;

			case 'date':
				$instance = new BF_Gutenberg_Date_Time_Picker();

				break;

			case 'color':
				$instance = new BF_Gutenberg_Color_Palette();

				break;

			case 'tab_panel':
				$instance = new BF_Gutenberg_Tabs();

				break;

			case 'navigator':
				$instance = new BF_Gutenberg_Navigator();

				break;

			case 'gutenberg':
				$instance = new BF_Gutenberg_Native_Component();

				break;

			case 'repeater':
				$instance = new BF_Gutenberg_BF_Repeater();

				break;
			case 'nav_provider':
				$instance = new BF_Gutenberg_Navigator_Provider();

				break;

			default:
				$instance = new BF_Gutenberg_Control();

				break;
		}

		$instance->init( $field, $results );

		return $instance;
	}
}
