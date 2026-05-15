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
 * Class BF_Widgets_Field_Generator
 */
class BF_Widgets_Field_Generator extends BF_Admin_Fields {


	/**
	 * Constructor Function
	 *
	 * @param array $items  Panel All Options
	 * @param array $values Panel ID
	 *
	 * @since  1.0
	 * @access public
	 * @return \BF_Widgets_Field_Generator
	 */
	public function __construct( array $items, $values = [] ) {

		$default_options = [
			'templates_dir' => BF_PATH . 'widget/templates/',
		];
		$items           = array_merge( $default_options, $items );

		foreach ( $items['fields'] as $idx => $field ) {

			if ( isset( $field['id'] ) ) {
				$items['fields'][ $idx ]['id'] = $field['id'];
			}
		}

		$this->items  = $items;
		$this->values = $values;

		// Parent Constructor
		parent::__construct( $items );

		\BetterFrameworkPackage\Component\Control\Setup::register_wrapper( 'better-framework-widget', [ $this, 'section' ] );
	}


	/**
	 * Display HTML output of one field
	 *
	 * @param $field
	 *
	 * @return string
	 */
	public function get_field( array $field ) {

		$field = apply_filters( 'better-framework/field-generator/field', $field, $this->id ?? '' );

		$field['value'] = $this->values[ $field['id'] ] ?? null;

		if ( is_null( $field['value'] ) && isset( $field['std'] ) && 'repeater' !== $field['type'] ) {
			$field['value'] = $field['std'];
		}

		if ( ! \BetterFrameworkPackage\Component\Control\control_exists( $field['type'] ) ) {
			return '';
		}

		if ( 'repeater' === $field['type'] ) {
			$field['widget_field'] = true;
		}

		// filter field
		if ( isset( $field['filter-field'] ) && $field['filter-field-value'] ) {

			// filter field value
			$filter_field_value = $this->values[ $field['filter-field'] ] ?? null;
			if ( is_null( $filter_field_value ) ) {

				foreach ( $this->items['fields'] as $_field ) {

					if ( $field['filter-field'] === $_field ) {

						if ( isset( $_field['std'] ) && 'repeater' !== $_field['type'] ) {

							$filter_field_value = $_field['std'];
						}
					}
				}
			}

			if ( $field['filter-field-value'] !== $filter_field_value ) {

				$field['section-css']['display'] = 'none';
			}
		}

		return \BetterFrameworkPackage\Component\Control\render_control_array( $field, [ 'wrapper_id' => 'better-framework-widget' ] );
	}


	/**
	 * Display HTML output of widget fields array
	 *
	 * @since  1.0
	 * @access public
	 * @return string
	 */
	public function get_fields() {

		$output = '';

		// Flag for detecting Groups
		$group_counter = 0;

		foreach ( $this->items['fields'] as $field ) {

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
				if ( 0 !== $group_counter ) {
					$group_counter = 0;
					$output       .= $this->get_fields_group_close( $field );
				}

				if ( isset( $field['container-class'] ) ) {
					$field['container-class'] .= ' bf-widgets';
				} else {
					$field['container-class'] = 'bf-widgets';
				}

				$output .= $this->get_fields_group_start( $field );

				$group_counter ++;

			} else {

				$output .= $this->get_field( $field );

			}
		}

		// close tag for latest group
		if ( 0 !== $group_counter ) {
			$output .= $this->get_fields_group_close();
		}

		return $output;
	}

}
