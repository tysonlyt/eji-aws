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


if ( ! function_exists( 'bf_shortcode_show_title' ) ) {
	/**
	 * Used to show shortcodes heading in standard way!
	 * You can redefine this function or use 'better-framework/shortcodes/title' filter
	 * for changing values.
	 *
	 * @param array $atts
	 *
	 * @since 2.5.5
	 *
	 * @return string
	 */
	function bf_shortcode_show_title( $atts = [] ) {

		if ( isset( $atts['show_title'] ) && $atts['show_title'] == false ) {
			return;
		}

		if ( isset( $atts['hide_title'] ) && $atts['hide_title'] == true ) {
			return;
		}

		if ( empty( $atts['title'] ) ) {
			return;
		}

		if ( bf_get_current_sidebar() && ! preg_match( '#^bs-(vc|is)-sidebar-column$#', bf_get_current_sidebar() ) ) {
			return;
		}

		$result = apply_filters( 'better-framework/shortcodes/title', $atts );

		if ( is_string( $result ) ) {
			echo $result; // escaped before
		}

	}
}


if ( ! function_exists( 'bf_shortcode_custom_css_prop' ) ) {
	/**
	 * @param        $css
	 * @param        $prop_name
	 * @param string    $default
	 *
	 * @return string
	 *
	 * @since 2.5.2
	 */
	function bf_shortcode_custom_css_prop( $css, $prop_name, $default = '' ) {

		preg_match( '/' . $prop_name . ':([^!]*)/', $css, $css );

		if ( ! empty( $css[1] ) ) {
			return trim( $css[1] );
		}

		return $default;
	}
}


if ( ! function_exists( 'bf_shortcode_custom_css_class' ) ) {
	/**
	 * Custom function used to get custom css class name form VC/Shortcode css atribute
	 *
	 * @param        $param_value
	 * @param string      $prefix
	 * @param string      $css_key
	 *
	 * @return string
	 *
	 * @since 2.5.2
	 */
	function bf_shortcode_custom_css_class( $param_value, $prefix = '', $css_key = 'css' ) {

		$css_class = '';

		// prepare field
		if ( is_array( $param_value ) && ! empty( $param_value[ $css_key ] ) ) {
			$param_value = $param_value[ $css_key ];
		} else {
			return $css_class;
		}

		if ( is_string( $param_value ) ) {
			$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
		}

		return $css_class;
	}
}


if ( ! function_exists( 'bf_vc_edit_form_classes' ) ) {
	/**
	 * Filter: vc_edit_form_class
	 * Description: add some class to visual composer edit form. used in admin-scripts.js setup_interactive_fields_for_vc() method
	 *
	 * @see setup_interactive_fields_for_vc method on `Better_Framework` JS Object
	 *
	 * @param array $classes
	 * @param array $atts
	 * @param array $params
	 *
	 * @return mixed
	 */
	function bf_vc_edit_form_classes( $classes, $atts, $params ) {

		$added_fields      = [];
		$interactive_added = false;
		foreach ( $params as $param ) {
			if ( ! empty( $param['show_on'] ) ) {
				if ( ! $interactive_added ) {
					array_push( $classes, 'bf-interactive-fields', 'bf-has-filters' );
					$interactive_added = true;
				}

				foreach ( (array) $param['show_on'] as $conditions ) {
					foreach ( (array) $conditions as $condition ) {
						$field_name = explode( '=', $condition, 2 );
						$field_name = $field_name[0];
						if ( ! in_array( $field_name, $added_fields ) ) {
							array_push( $classes, 'bf-filter-field-' . $field_name );
							$added_fields[] = $field_name;
						}
					}
				}
			}
		}

		return array_unique( $classes );
	}

	add_filter( 'vc_edit_form_class', 'bf_vc_edit_form_classes', 8, 3 );
}


if ( ! function_exists( 'bf_vc_layout_state' ) ) {
	/**
	 * Returns VC Columns state
	 *
	 * @return array
	 */
	function bf_vc_layout_state() {

		global $_bf_override_vc_layout_state, $_bf_vc_column_atts, $_bf_vc_column_inner_atts, $_bf_vc_row_columns;

		if ( ! empty( $_bf_override_vc_layout_state ) ) {
			return $_bf_override_vc_layout_state; // Allow to override state value
		}

		$_bf_vc_column_atts = array_filter( (array) $_bf_vc_column_atts );
		$_bf_vc_column_atts = bf_merge_args(
			$_bf_vc_column_atts,
			[
				'width'      => '1',
				'list'       => $_bf_vc_row_columns,
				'list_count' => $_bf_vc_row_columns ? bf_count( $_bf_vc_row_columns ) : 0,
			]
		);

		$_bf_vc_column_inner_atts = array_filter( (array) $_bf_vc_column_inner_atts );
		$_bf_vc_column_inner_atts = bf_merge_args(
			$_bf_vc_column_inner_atts,
			[
				'width' => '1',
			]
		);

		return [
			'column' => $_bf_vc_column_atts,
			'row'    => $_bf_vc_column_inner_atts,
		];
	}
}


if ( ! function_exists( 'bf_is_vc_editor' ) ) {

	/**
	 * Check whether browsing vc frontend/backend editor
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	function bf_is_vc_editor(): bool {
		global $pagenow;

		if ( ! is_user_logged_in() || ! defined( 'WPB_VC_VERSION' ) ) {
			return false;
		}

		if ( \in_array( $pagenow, [ 'post.php', 'post-new.php' ], true ) && vc_backend_editor() && vc_backend_editor()->editorEnabled() ) {

			return ! isset( $_GET['action'] ) || $_GET['action'] === 'edit';
		}

		return bf_is_doing_ajax( 'vc_edit_form' ) || vc_is_inline() || vc_is_editor() || vc_is_frontend_ajax();
	}
}
if ( ! function_exists( 'bf_is_elementor_editor' ) ) {

	/**
	 * Check if browsing elementor editor.
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	function bf_is_elementor_editor( $post_id = null ) {

		global $post, $pagenow;

		if ( ! class_exists( 'Elementor\Plugin' ) ) {

			return false;
		}

		if ( ! $post_id && $pagenow === 'post.php' ) {

			if ( isset( $post->ID ) ) {

				$post_id = $post->ID;
			} elseif ( isset( $_REQUEST['post'] ) ) {

				$post_id = (int) $_REQUEST['post'];
			}
		}

		return Elementor\Plugin::$instance->editor->is_edit_mode( $post_id );
	}
}


add_filter( 'better-framework/shortcodes/design-fields', 'bf_shortcpdes_design_fields', 10, 2 );


/**
 * fixme: check functionality
 */
if ( ! function_exists( 'bf_shortcpdes_design_fields' ) ) {
	/**
	 * Generates general design options for shortcodes
	 *
	 * @param array  $fields
	 * @param string $shortcode_id
	 *
	 * @return array
	 */
	function bf_shortcpdes_design_fields( $fields = [], $shortcode_id = '' ) {

		$fields['design_tab'] = [
			'type' => 'tab',
			'name' => __( 'Design', 'better-studio' ),
			'id'   => 'design_tab',
		];

		if ( bf_is_vc_editor() ) {

			$fields['css'] = [
				'type'           => 'css_editor',
				'name'           => __( 'CSS box', 'better-studio' ),
				'id'             => 'css',
				'vc_admin_label' => false,
			];
		}

		if ( ! bf_is_elementor_editor() ) {

			$fields['_advanced_options'] = [
				'type'               => 'tab',
				'name'               => __( 'Advanced', 'better-studio' ),
				'id'                 => '_advanced_options',
				'override-gutenberg' => [
					'name' => __( 'Advanced Options', 'better-studio' ),
				],
			];

			$fields['bs-show-desktop'] = [
				'name'           => __( 'Show on Desktop', 'better-studio' ),
				'id'             => 'bs-show-desktop',
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'type'           => 'advance_select',
				'options'        => [
					1 => __( 'Show', 'better-studio' ),
					0 => [
						'label' => __( 'Hide', 'better-studio' ),
						'color' => '#4f6d84',
					],
				],
				'vc_admin_label' => false,
				'pro_feature'    => [
					'activate' => true,
					'modal_id' => 'publisher',
				],
			];

			$fields['bs-show-tablet'] = [
				'name'           => __( 'Show on Tablet', 'better-studio' ),
				'id'             => 'bs-show-tablet',
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'group'          => __( 'Design options', 'better-studio' ),
				'type'           => 'advance_select',
				'options'        => [
					1 => __( 'Show', 'better-studio' ),
					0 => [
						'label' => __( 'Hide', 'better-studio' ),
						'color' => '#4f6d84',
					],
				],
				'vc_admin_label' => false,
				'pro_feature'    => [
					'activate' => true,
					'modal_id' => 'publisher',
				],
			];

			$fields['bs-show-phone'] = [
				'name'           => __( 'Show on Mobile', 'better-studio' ),
				'id'             => 'bs-show-phone',
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'type'           => 'advance_select',
				'options'        => [
					1 => __( 'Show', 'better-studio' ),
					0 => [
						'label' => __( 'Hide', 'better-studio' ),
						'color' => '#4f6d84',
					],
				],
				'vc_admin_label' => false,
				'pro_feature'    => [
					'activate' => true,
					'modal_id' => 'publisher',
				],
			];

			$fields['custom-css-class'] = [
				'type'           => 'text',
				'name'           => __( 'Custom CSS Class', 'better-studio' ),
				'id'             => 'custom-css-class',
				'section_class'  => 'bf-section-two-column',
				'vc_admin_label' => false,
				'pro_feature'    => [
					'activate' => true,
					'modal_id' => 'publisher',
				],
			];

			$fields['custom-id'] = [
				'type'           => 'text',
				'name'           => __( 'Custom ID', 'better-studio' ),
				'id'             => 'custom-id',
				'value'          => '',
				'section_class'  => 'bf-section-two-column',
				'vc_admin_label' => false,
				'pro_feature'    => [
					'activate' => true,
					'modal_id' => 'publisher',
				],
			];
		}

		return $fields;
	}
}

if ( ! function_exists( 'bf_vc_doing_shortcode' ) ) {

	/**
	 * Get the current running vc shortcode name.
	 *
	 * @since 3.13.2
	 * @return string
	 */
	function bf_vc_doing_shortcode() {

		global $bf_vc_current_shortcode;

		return $bf_vc_current_shortcode;
	}
}

if ( ! function_exists( 'bf_vc_is_doing_custom_shortcode' ) ) {

	/**
	 * Is currently doing a vc shortcode / except vc_row and vc_column/
	 *
	 * @since 3.13.2
	 * @return bool
	 */
	function bf_vc_is_doing_custom_shortcode(): bool {

		$shortcode = bf_vc_doing_shortcode();

		return ! empty( $shortcode ) && ! in_array( $shortcode, [ 'vc_row', 'vc_column' ], true );
	}
}


add_filter( 'vc_shortcode_content_filter', 'bf_vc_shortcode_start', 4, 2 );

if ( ! function_exists( 'bf_vc_shortcode_start' ) ) {

	/**
	 * Catch the vc shortcode name.
	 *
	 * @param string $content   The shortcode content.
	 * @param string $shortcode The shortcode name.
	 *
	 * @since 3.13.2
	 * @return string
	 */
	function bf_vc_shortcode_start( $content, $shortcode ) {

		global $bf_vc_current_shortcode;

		$bf_vc_current_shortcode = $shortcode;

		return $content;
	}
}

add_filter( 'vc_shortcode_content_filter_after', 'bf_vc_shortcode_stop', 999 );

if ( ! function_exists( 'bf_vc_shortcode_stop' ) ) {

	/**
	 * End vc doing shortcode functionality.
	 *
	 * @param string $output shortcode output.
	 *
	 * @since 3.13.2
	 * @return string
	 */
	function bf_vc_shortcode_stop( $output ) {

		global $bf_vc_current_shortcode;

		$bf_vc_current_shortcode = '';

		return $output;
	}
}

