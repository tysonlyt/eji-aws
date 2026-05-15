<?php

use BetterFrameworkPackage\Component\Control;

/**
 * TinyMCE Views field generator
 */
class BF_Simple_Field_Generator extends BF_Admin_Fields {

	/**
	 * @var array
	 */
	public $shortcode_content_fields = [];


	/**
	 * Constructor Method
	 *
	 * @param array $items  Panel All Options
	 * @param int   $id     Panel ID
	 * @param array $values Panel Saved Values
	 *
	 * @since  1.0
	 * @access public
	 */
	public function __construct( array &$items, $id = '', &$values = [] ) {

		$this->items  = $items;
		$this->id     = $id;
		$this->values = $values;

		// Parent Constructor
		parent::__construct(
			[
				'templates_dir' => BF_PATH . 'tinymce/templates/',
			]
		);

		\BetterFrameworkPackage\Component\Control\Setup::register_wrapper( 'better-framework-tinymce', [ $this, 'section' ] );
	}


	public function output() {

		$this->callback( true );
	}


	/**
	 * Field Generator
	 */
	public function callback() {

		$has_tab = false;

		// Add Class For Post Format Filter
		$container = Better_Framework::html()->add( 'div' )->class( 'bf-controls-container' );

		$tab_counter   = 0;
		$group_counter = 0;

		$fields_std = $this->get_stds();

		$container->text( '<div class="tabs-wrapper">' . $this->get_tabs() . '</div>' );

		foreach ( $this->get_fields() as $field ) {

			$field = apply_filters( 'better-framework/field-generator/field', $field, $this->id ?? '' );

			if ( ! empty( $field['type'] ) && 'id-holder' === $field['type'] ) {
				continue;
			}

			if ( ! isset( $field['input_class'] ) ) {
				$field['input_class'] = '';
			}

			$field['input_class'] .= ' mce-field';
			$field['input_name']   = $this->input_name( $field );

			$field_id = isset( $field['id'] ) ? $field['id'] : false;

			if ( 'info' === $field['type'] ) {
				if ( isset( $field['std'] ) ) {
					$field['value'] = $field['std'];
				} else {
					$field['value'] = '';
				}
			} else {
				$field['value'] = isset( $field['id'] ) && isset( $this->values[ $field['id'] ] ) ? $this->values[ $field['id'] ] : null;
			}

			if ( is_null( $field['value'] ) && isset( $fields_std[ $field_id ] ) ) {
				$field['value'] = $fields_std[ $field_id ];
			}

			if ( 'repeater' === $field['type'] ) {
				$field['clone-name-format'] = 'bf-metabox-option[$3][$4][$5][$6]';
				$field['metabox-id']        = $this->id;
				$field['metabox-field']     = true;
			}

			if ( 'tab' === $field['type'] || 'subtab' === $field['type'] ) {

				// close tag for latest group in tab
				if ( 0 !== $group_counter ) {
					$group_counter = 0;
					$container->text( $this->get_fields_group_close( $field ) );
				}

				$is_subtab = 'subtab' === $field['type'];

				if ( 0 !== $tab_counter ) {
					$container->text( '</div><!-- /Section -->' );
				}

				if ( $is_subtab ) {
					$container->text( "\n\n<!-- Section -->\n<div class='group subtab-group' id='bf-tmv-{$field["id"]}'>\n" );
				} else {
					$container->text( "\n\n<!-- Section -->\n<div class='group' id='bf-tmv-{$field["id"]}'>\n" );
				}
				$has_tab = true;
				$tab_counter ++;
				continue;
			}

			if ( 'group_close' === $field['type'] ) {

				// close tag for latest group in tab
				if ( 0 !== $group_counter ) {
					$group_counter = 0;
					$container->text( $this->get_fields_group_close( $field ) );
				}
				continue;
			}

			if ( 'group' === $field['type'] ) {

				// close tag for latest group in tab
				if ( 0 !== $group_counter ) {
					$group_counter = 0;
					$container->text( $this->get_fields_group_close( $field ) );
				}

				$container->text( $this->get_fields_group_start( $field ) );

				$group_counter ++;
			}

			if ( ! \BetterFrameworkPackage\Component\Control\control_exists( $field['type'] ) ) {
				continue;
			}

			// Filter Each Field For Post Formats!
			if ( isset( $field['post_format'] ) ) {

				if ( is_array( $field['post_format'] ) ) {
					$field_post_formats = implode( ',', $field['post_format'] );
				} else {
					$field_post_formats = $field['post_format'];
				}
				$container->text( "<div class='bf-field-post-format-filter' data-bf_pf_filter='{$field_post_formats}'>" );
			}

			$container->text(
				\BetterFrameworkPackage\Component\Control\render_control_array( $field, [ 'wrapper_id' => 'better-framework-tinymce' ] )
			);

			// End Post Format Filter Wrapper
			if ( isset( $field['post_format'] ) ) {

				$container->text( '</div>' );
			}
		}

		// close tag for latest group in tab
		if ( 0 !== $group_counter ) {
			$container->text( $this->get_fields_group_close( $field ) );
		}

		// last sub tab closing
		if ( $has_tab ) {
			$container->text( '</div><!-- /Section -->' );
		}

		//phpcs:ignore
		echo $container->display();
	} // callback


	/**
	 * Used for creating input name
	 *
	 * @param $options
	 *
	 * @return string
	 */
	public function input_name( &$options ) {

		if ( isset( $options['type'] ) && 'repeater' === $options['type'] ) {
			return $options['id'] . '[{{iteration}}][{{control_id}}]';
		}

		return isset( $options['id'] ) ? $options['id'] : '';
	}


	/**
	 * Get shortcode fields std values
	 *
	 * @return array
	 */
	public function get_stds() {

		$shortcode = BF_Shortcodes_Manager::factory( $this->id, [], true );

		return $shortcode->defaults ?? [];
	}


	public function generate_repeater_field( $tinymce, $field, $defaults, $name_format, $number ) {

		if ( ! isset( $field['input_class'] ) ) {
			$field['input_class'] = '';
		}
		$field['input_class'] .= ' mce-field';

		if ( ! empty( $field['shortcode_content'] ) ) {
			$this->shortcode_content_fields[ $tinymce['id'] ] = $field['id'];
		}

		return $this->generate_repeater_field( $tinymce, $field, $defaults, $name_format, $number );
	}


	public function generate_repeater_field_script( $tinymce, $field, $defaults ) {

		if ( ! isset( $field['input_class'] ) ) {
			$field['input_class'] = '';
		}
		$field['input_class'] .= ' mce-field';

		return $this->generate_repeater_field_script( $tinymce, $field, $defaults );
	}


	public function __call( $name, $arguments ) {

		$file = BF_PATH . 'tinymce/fields/' . $name . '.php';

		// Check if requested field (method) does exist!
		if ( ! file_exists( $file ) ) {
			return parent::__call( $name, $arguments );
		}

		$options = $arguments[0];

		// Capture output
		ob_start();

		require $file;

		return ob_get_clean();
	}
}
