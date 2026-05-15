<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */

use BetterFrameworkPackage\Component\Control;

/**
 * BF Menu Field Generator
 *
 * @since 1.0
 */
class BF_Menu_Field_Generator extends BF_Admin_Fields {

	/**
	 * Menu item that contains values
	 *
	 * @var array
	 * @since  1.0
	 * @access public
	 */
	public $menu_item;


	/**
	 * Constructor Function
	 *
	 * @param array  $items
	 * @param object $menu_item
	 *
	 * @since  1.0
	 * @access public
	 * @return \BF_Menu_Field_Generator
	 */
	public function __construct( array $items, &$menu_item = null ) {

		$items['templates_dir'] = BF_PATH . 'menu/templates/';

		$this->items     = [
			'fields' => BF_Menus::get_fields(),
		];
		$this->values    = get_object_vars( $menu_item );
		$this->menu_item = &$menu_item;

		// Parent Constructor
		parent::__construct( $items );

		\BetterFrameworkPackage\Component\Control\Setup::register_wrapper( 'better-framework-menu', [ $this, 'section' ] );
	}


	/**
	 * Display HTML output of fields
	 *
	 * @since  1.0
	 * @access public
	 * @return string
	 */
	public function get_fields() {

		$output        = '';
		$group_counter = 0;

		$all_std = BF_Menus::get_std();

		foreach ( $this->items['fields'] as $key => $field ) {

			$field = apply_filters( 'better-framework/field-generator/field', $field, $field['panel-id'] ?? $this->id ?? '' );

			if ( isset( $field['panel-id'] ) ) {
				$std = Better_Framework::options()->get_panel_std_id( $field['panel-id'] );
			} else {
				$std = 'std';
			}

			if ( empty( $field['id'] ) ) {
				$field['value'] = false;
			} else {
				$field['value'] = $this->values[ $field['id'] ] ?? false;
			}

			if ( false === $field['value'] ) {
				if ( isset( $all_std[ $std ] ) ) {
					$field['value'] = $all_std[ $std ];
				} elseif ( 'std' !== $std && isset( $all_std['std'] ) ) {
					$field['value'] = $all_std['std'];
				}
			}

			if ( 'group_close' === $field['type'] ) {

				// close tag for latest group in tab
				if ( 0 !== $group_counter ) {
					$group_counter = 0;
					$output       .= $this->get_fields_group_close( $field );
				}
				continue;
			}

			if ( 'group' === $field['type'] ) {

				// close tag for latest group in tab
				if ( $group_counter != 0 ) {
					$group_counter = 0;
					$output       .= $this->get_fields_group_close( $field );
				}

				$output .= $this->get_fields_group_start( $field );

				$group_counter ++;
			}

			if ( ! \BetterFrameworkPackage\Component\Control\control_exists( $field['type'] ) ) {
				continue;
			}

			// for image checkbox sortable option
			if ( isset( $field['is_sortable'] ) && ( '1' === $field['is_sortable'] ) ) {
				$field['section_class'] .= ' is-sortable';
			}

			$field['input_name'] = $this->generate_field_ID( $key, $this->menu_item->ID );

			$output .= \BetterFrameworkPackage\Component\Control\render_control_array( $field, [ 'wrapper_id' => 'better-framework-menu' ] );

		} // foreach

		// close tag for latest group in tab
		if ( 0 !== $group_counter ) {
			$output .= $this->get_fields_group_close( $field );
		}

		unset( $std );
		unset( $group_counter );

		return $output;
	}


	/**
	 * Generate valid names for fields
	 *
	 * @param $key
	 * @param $parent_id
	 *
	 * @return string
	 */
	public function generate_field_ID( $key, $parent_id ) {

		return 'bf-m-i[' . esc_attr( $key ) . '][' . $parent_id . ']';
	}

}
