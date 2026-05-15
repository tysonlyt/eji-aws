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
 * Taxonomies Field Generator For Admin
 */
class BF_Taxonomy_Front_End_Generator extends BF_Admin_Fields {


	/**
	 * @see BF_Metabox_Core::init_metabox apply_filters
	 *
	 * @var string
	 */
	public $metabox_id;


	/**
	 * Constructor Function
	 *
	 * @param array              $items
	 * @param        $id         term ID
	 * @param array              $values
	 * @param string             $metabox_id metabox ID {@see  self::$metabox_id}  doc
	 *
	 * @since  1.0
	 * @access public
	 * @return \BF_Taxonomy_Front_End_Generator
	 */
	public function __construct( array &$items, &$id, &$values = [], $metabox_id = false ) {

		// Parent Constructor
		$generator_options = [
			'templates_dir' => BF_PATH . 'taxonomy/templates/',
		];

		$this->items  = $items;
		$this->id     = $id;
		$this->values = $values;

		$this->metabox_id = $metabox_id;

		parent::__construct( $generator_options );

		\BetterFrameworkPackage\Component\Control\Setup::register_wrapper( 'better-framework-taxonomy-mb', [ $this, 'section' ] );
	}


	/**
	 * Make input name from options variable
	 *
	 * @param  (array) $options Options array
	 *
	 * @since  1.0
	 * @access public
	 * @return string
	 */
	public function input_name( &$options ) {

		$id   = isset( $options['id'] ) ? $options['id'] : '';
		$type = isset( $options['type'] ) ? $options['type'] : '';

		switch ( $type ) {

			case 'repeater':
				return "bf-term-meta[{$id}][{{iteration}}][{{control_id}}]";

			default:
				return "bf-term-meta[{$id}]";
		}

	}


	/**
	 * Used for generating fields
	 */
	public function callback( $is_ajax = false ) {

		$items_has_tab    = ! $is_ajax;
		$skip_ajax_fields = ! $is_ajax;
		$has_tab          = false;
		$tab_counter      = 0;
		$group_counter    = [];

		$metabox_name = isset( $this->items['config']['name'] ) ?
			esc_html( $this->items['config']['name'] ) : esc_html__( 'Better Options', 'better-studio' );

		// Base wrapper
		if ( ! $is_ajax ) {
			$wrapper = Better_Framework::html()->add( 'div' );
			$wrapper = $wrapper->class( 'bf-tax-meta-wrap bf-metabox-wrap bf-clearfix' )->data( 'id', $this->id );
			if ( $this->metabox_id ) {
				$wrapper->data( 'metabox-id', $this->metabox_id );
			}
			// Better Option Title
			$wrapper->text(
				Better_Framework::html()->add( 'div' )->class( 'bf-tax-metabox-title' )->text(
					Better_Framework::html()->add( 'h3' )->text( $metabox_name )
				)
			);
		}

		$container = Better_Framework::html()->add( 'div' );

		if ( ! $is_ajax ) {
			$container = $container->class( 'bf-metabox-container' );
		}

		// Add Tab
		if ( $items_has_tab && ! $is_ajax ) {
			$container->class( 'bf-with-tabs' );
			$tabs_container = Better_Framework::html()->add( 'div' )->class( 'bf-metabox-tabs' );
			$tabs_container->text( $this->get_tabs() );
			$wrapper->text( $tabs_container->display() );
		}

		foreach ( $this->items['fields'] as $field ) {

			$field = apply_filters( 'better-framework/field-generator/field', $field, $this->id );

			$field['input_name'] = $this->input_name( $field );

			$field['value'] = isset( $field['id'], $this->values[ $field['id'] ] ) ? $this->values[ $field['id'] ] : false;

			if ( isset( $field['type'] ) && 'info' === $field['type'] && $field['std'] ) {
				$field['value'] = $field['std'];
			}

			if ( $skip_ajax_fields && ! empty( $field['ajax-tab-field'] ) ) { // Backward compatibility
				continue;
			}

			if ( $skip_ajax_fields && ! empty( $field['ajax-section-field'] ) ) {
				continue;
			}

			if ( 'repeater' === $field['type'] ) {
				$field['clone-name-format']  = 'bf-term-meta[$4][$5][$6]';
				$field['term-metabox-id']    = $field['id'];
				$field['term-metabox-field'] = true;
			}

			if ( 'tab' === $field['type'] || 'subtab' === $field['type'] ) {

				if ( $has_tab ) {

					// close all opened groups
					foreach ( array_reverse( $group_counter ) as $level_k => $level_v ) {

						if ( 0 === $level_v ) {
							continue;
						}

						for ( $i = 0; $i < $level_v; $i ++ ) {
							$container->text( $this->get_fields_group_close( $field ) );
						}

						$group_counter[ $level_k ] = 0;
					}
				}
				$is_subtab = 'subtab' === $field['type'];

				if ( 0 !== $tab_counter ) {
					$container->text( '</div><!-- /Section -->' );
				}

				if ( $is_subtab ) {
					$container->text( "\n\n<!-- Section -->\n<div class='group subtab-group' id='bf-metabox-{$this->id}-{$field["id"]}'>\n" );
				} else {
					$container->text( "\n\n<!-- Section -->\n<div class='group' id='bf-metabox-{$this->id}-{$field["id"]}'>\n" );
				}
				$has_tab = true;
				$tab_counter ++;
				continue;
			}

			//
			// Close group
			//
			if ( 'group_close' === $field['type'] ) {

				if ( isset( $field['level'] ) && 'all' === $field['level'] ) {

					krsort( $group_counter );

					// close all opened groups
					foreach ( $group_counter as $level_k => $level_v ) {

						if ( 0 === $level_v ) {
							continue;
						}

						for ( $i = 0; $i < $level_v; $i ++ ) {
							$container->text( $this->get_fields_group_close( $field ) );
						}

						$group_counter[ $level_k ] = 0;
					}
				} else {

					krsort( $group_counter );

					// close last opened group
					foreach ( $group_counter as $level_k => $level_v ) {

						if ( ! $level_v ) {
							continue;
						}

						for ( $i = 0; $i < $level_v; $i ++ ) {
							$container->text( $this->get_fields_group_close( $field ) );
							$group_counter[ $level_k ] --;
							break;
						}
					}
				}

				continue;
			}

			//
			// Group
			// All nested groups and same level groups should be closed
			//
			if ( 'group' === $field['type'] ) {

				if ( ! isset( $field['level'] ) ) {
					$field['level'] = 0;
				}

				if ( ! isset( $group_counter[ $field['level'] ] ) ) {
					$group_counter[ $field['level'] ] = 0;
				}

				krsort( $group_counter );

				foreach ( $group_counter as $level_k => $level_v ) {

					if ( $level_k < $field['level'] ) {
						continue;
					}

					for ( $i = 0; $i < $level_v; $i ++ ) {
						$container->text( $this->get_fields_group_close( $field ) );
					}

					$group_counter[ $level_k ] = 0;
				}

				$container->text( $this->get_fields_group_start( $field ) );

				$group_counter[ $field['level'] ] ++;
			}

			if ( ! \BetterFrameworkPackage\Component\Control\control_exists( $field['type'] ) ) {
				continue;
			}

			$container->text(
				\BetterFrameworkPackage\Component\Control\render_control_array( $field, [ 'wrapper_id' => 'better-framework-taxonomy-mb' ] )
			);

		}

		// last sub tab closing
		if ( $has_tab ) {
			$container->text( '</div><!-- /Section -->' );
		}

		if ( $is_ajax ) {
			//phpcs:ignore
			echo $container->display();
		} else {
			$wrapper->text(
				$container->display()
			);
			//phpcs:ignore
			echo $wrapper;  // escaped before
		}

	} // callback
}
