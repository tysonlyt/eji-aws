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


/**
 * todo refactor this
 */
abstract class BF_Admin_Fields {

	/**
	 * Holds All Field Generator Options
	 *
	 * @since  1.0
	 * @access private
	 * @var array
	 */
	protected $options = [];


	/**
	 * Holds all fields
	 *
	 * @since  1.0
	 * @access private
	 * @var array
	 */
	protected $items = [];


	/**
	 * Panel ID
	 *
	 * @since  1.0
	 * @access public
	 * @var string
	 */
	protected $id;


	/**
	 * Panel Values
	 *
	 * @since  1.0
	 * @access public
	 * @var array
	 */
	protected $values;


	/**
	 * Store options keys which will be print in html source
	 *
	 * @since BF 2.8.4
	 * @var array
	 */
	public static $public_options = [
		'show_on'      => '',
		'show_on_type' => '',
	];

	/**
	 * PHP Constructor Function
	 *
	 * defining class options with constructor function
	 *
	 * @param array $items
	 *
	 * @since  1.0
	 * @access public
	 * @return \BF_Admin_Fields
	 */
	public function __construct( $items = [] ) {

		if ( ! function_exists( 'bf_show_on_attributes' ) ) {
			require BF_PATH . '/core-deprecated/field-generator/functions.php';
		}

		$default_options = [
			'fields_dir'    => BF_PATH . 'core-deprecated/field-generator/fields/',
			'modals_dir'    => BF_PATH . 'core-deprecated/modals/',
			'templates_dir' => BF_PATH . 'core-deprecated/templates/',
			'section-file'  => BF_PATH . 'core-deprecated/templates/default-fileld-template.php',
		];

		$this->options = array_merge( $default_options, $items );

		// normalize controls value
		$this->normalize_values();
	}


	/**
	 * Setting object settings
	 *
	 * This class is for setting options
	 *
	 * @param  (string)           $option_name    Name of option
	 * @param  (array|sting|bool) $option_value    Value of option
	 *
	 * @since  1.0
	 * @access public
	 * @return object
	 */
	public function set( $option_name, $option_value ) {

		$this->options[ $option_name ] = $option_value;

		return $this;
	}


	/**
	 * Check if the panel has specific field
	 *
	 * @param  (string) $type Field Type
	 *
	 * @since  1.0
	 * @access public
	 * @return bool
	 */
	public function has_field( $field ) {

		$has = false;
		foreach ( $this->items as $item ) {
			if ( isset( $item['type'] ) && $item['type'] == $field ) {
				return true;
			}
		}

		return (bool) $has;
	}


	/**
	 * Used for checking meta box have tab or not
	 *
	 * @return bool
	 */
	public function has_tab(): bool {

		foreach ( $this->items['fields'] as $field ) {
			if ( $field['type'] == 'tab' ) {
				return true;
			}
		}

		return false;

	}


	/**
	 * Wrap the input in a section
	 *
	 * This class is for setting options
	 *
	 * @param  (string) $input   The string value of input (<input />))
	 * @param  (array)  $option     Field options (like name, id etc)
	 *
	 * @since  1.0
	 * @access public
	 * @return string
	 */
	public function section( $input, $options ) {

		$template_file = $this->options['templates_dir'] . $options['type'] . '.php';
		ob_start();

		if ( ! file_exists( $template_file ) ) {
			require $this->options['templates_dir'] . 'default.php';
		} else {
			require $template_file;
		}

		return ob_get_clean();

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

			case ( 'image_checkbox' ):
				return "{$id}[%s]";
				break;

			default:
				return $id;
				break;

		}

	}


	/**
	 * Get classes - @Vahid WTF!!!
	 *
	 * get element classes array
	 *
	 * @param  (type) about this param
	 *
	 * @since  1.0
	 * @access public
	 * @return array
	 */
	public function get_classes( &$options ): array {

		$is_repeater = isset( $options['repeater_item'] ) && $options['repeater_item'] === true;
		$classes     = [];

		$classes['section']  = apply_filters( 'better-framework/field-generator/class/section', 'bf-section' );
		$classes['section'] .= ! isset( $options['section_class'] ) ? '' : ' ' . $options['section_class'];

		$classes['container']  = apply_filters( 'better-framework/field-generator/class/container', 'bf-section-container' );
		$classes['container'] .= ! isset( $options['container_class'] ) ? '' : ' ' . $options['container_class'];

		if ( isset( $options['direction'] ) ) {
			$classes['container'] .= ! isset( $options['container_class'] ) ? ' dir-' . $options['direction'] : ' ' . $options['container_class'] . ' dir-' . $options['direction'];
		}

		$classes['repeater-section']                        = apply_filters( 'better-framework/field-generator/class/section/repeater', 'bf-repeater-section-option' );
		$classes['nonrepeater-section']                     = apply_filters( 'better-framework/field-generator/class/section/nonrepeater', 'bf-nonrepeater-section' );
		$classes['section-class-by-filed-type']             = apply_filters( 'better-framework/field-generator/class/section/by/type', 'bf-section-' . $options['type'] . '-option', $options['type'] );
		$classes['nonrepeater-section-class-by-filed-type'] = apply_filters( 'better-framework/field-generator/class/section/nonrepeater/by/type', 'bf-nonrepeater-' . $options['type'] . '-section', $options['type'] );
		$classes['repeater-section-class-by-filed-type']    = apply_filters( 'better-framework/field-generator/class/section/repeater/by/type', 'bf-repeater-' . $options['type'] . '-section', $options['type'] );

		$classes['heading']                                 = apply_filters( 'better-framework/field-generator/class/heading', 'bf-heading' );
		$classes['repeater-heading']                        = apply_filters( 'better-framework/field-generator/class/heading/repeater', 'bf-repeater-heading-option' );
		$classes['nonrepeater-heading']                     = apply_filters( 'better-framework/field-generator/class/heading/nonrepeater', 'bf-nonrepeater-heading' );
		$classes['heading-class-by-filed-type']             = apply_filters( 'better-framework/field-generator/class/heading/by/type', 'bf-heading-' . $options['type'] . '-option', $options['type'] );
		$classes['nonrepeater-heading-class-by-filed-type'] = apply_filters( 'better-framework/field-generator/class/heading/nonrepeater/by/type', 'bf-nonrepeater-' . $options['type'] . '-heading', $options['type'] );
		$classes['repeater-heading-class-by-filed-type']    = apply_filters( 'better-framework/field-generator/class/heading/repeater/by/type', 'bf-repeater-' . $options['type'] . '-heading', $options['type'] );

		$classes['controls']                                 = apply_filters( 'better-framework/field-generator/class/controls', 'bf-controls' );
		$classes['repeater-controls']                        = apply_filters( 'better-framework/field-generator/class/heading/repeater', 'bf-repeater-controls-option' );
		$classes['nonrepeater-controls']                     = apply_filters( 'better-framework/field-generator/class/heading/nonrepeater', 'bf-nonrepeater-controls' );
		$classes['controls-class-by-filed-type']             = apply_filters( 'better-framework/field-generator/class/heading/by/type', 'bf-controls-' . $options['type'] . '-option', $options['type'] );
		$classes['nonrepeater-controls-class-by-filed-type'] = apply_filters( 'better-framework/field-generator/class/heading/nonrepeater/by/type', 'bf-nonrepeater-' . $options['type'] . '-controls', $options['type'] );
		$classes['repeater-controls-class-by-filed-type']    = apply_filters( 'better-framework/field-generator/class/heading/repeater/by/type', 'bf-repeater-' . $options['type'] . '-controls', $options['type'] );

		$classes['image'] = apply_filters( 'better-framework/field-generator/class/image', 'bf-field-image' );

		$classes['explain']                                 = apply_filters( 'better-framework/field-generator/class/explain', 'bf-explain' );
		$classes['repeater-explain']                        = apply_filters( 'better-framework/field-generator/class/explain/repeater', 'bf-repeater-explain-option' );
		$classes['nonrepeater-explain']                     = apply_filters( 'better-framework/field-generator/class/explain/nonrepeater', 'bf-nonrepeater-explain' );
		$classes['explain-class-by-filed-type']             = apply_filters( 'better-framework/field-generator/class/explain/by/type', 'bf-explain-' . $options['type'] . '-option', $options['type'] );
		$classes['nonrepeater-explain-class-by-filed-type'] = apply_filters( 'better-framework/field-generator/class/explain/nonrepeater/by/type', 'bf-nonrepeater-' . $options['type'] . '-explain', $options['type'] );
		$classes['repeater-explain-class-by-filed-type']    = apply_filters( 'better-framework/field-generator/class/explain/repeater/by/type', 'bf-repeater-' . $options['type'] . '-explain', $options['type'] );

		return $classes;

	}


	/**
	 * Used for generating start tag of fields group
	 *
	 * @param $group
	 *
	 * @return string
	 */
	function get_fields_group_start( $group ) {

		$group_container_class = 'fields-group bf-clearfix';
		if ( isset( $group['container-class'] ) ) {
			$group_container_class .= ' ' . $group['container-class'];
		}

		$group_title_class = 'fields-group-title-container';
		if ( isset( $group['title-class'] ) ) {
			$group_title_class .= ' ' . $group['title-class'];
		}

		if ( ! empty( $group['icon'] ) ) {
			$group_title_class .= ' group-title-has-icon';
			$group['name']      = bf_get_icon_tag( $group['icon'] ) . ' ' . $group['name'];
		}

		if ( ! empty( $group['ajax-section'] ) ) {
			$group_container_class .= ' bf-ajax-section ' . sanitize_html_class( $group['ajax-section'] );
		}

		if ( ! empty( $group['ajax-tab'] ) ) { // Backward compatibility
			$group_container_class .= ' bf-ajax-tab';
		}

		if ( ! isset( $group['id'] ) ) {
			$group['id'] = time();
		}

		if ( isset( $group['color'] ) ) {
			$color = $group['color'];
		} else {
			$color = '';
		}

		// Collapsible feature
		if ( isset( $group['state'] ) ) {
			$state = $group['state'];
		} else {
			$state = 'open';
		}
		if ( $state == 'close' ) {

			$group_container_class .= ' collapsible close';
			$collapse_button        = '<span class="collapse-button"></span>';

		} elseif ( $state == 'open' ) {

			$group_container_class .= ' collapsible open';
			$collapse_button        = '<span class="collapse-button"></span>';

		} else {

			$group_container_class .= ' not-collapsible';
			$collapse_button        = '';

		}

		// Desc
		if ( ! empty( $group['desc'] ) ) {
			$desc = "<div class='bf-group-desc'>{$group['desc']}</div>";
		} else {
			$desc = '';
		}

		$output = "\n\n<!-- Fields Group -->\n<div class='{$group_container_class} {$color}' id='fields-group-{$group['id']}' data-param-type='group'\n";

		$output .= bf_show_on_attributes( $group );
		$output .= '>';

		$output .= "<div class='{$group_title_class}'><span class='fields-group-title'>{$group['name']}</span>{$collapse_button}</div>";
		$output .= "<div class='bf-group-inner bf-clearfix' style='" . ( $state == 'close' ? 'display:none;' : '' ) . "'>$desc";

		return $output;
	}


	/**
	 * Used for generating close tag of fields group
	 *
	 * @param $group
	 *
	 * @return string
	 */
	function get_fields_group_close( $group = '' ) {

		return '</div></div>';

	}

	//
	//
	// Handy functions
	//
	//

	/**
	 * Used for generating section css attr from field array
	 *
	 * @param $field
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	function get_section_css_attr( $field ) {

		$attr = '';

		if ( isset( $field['section-css'] ) ) {

			$attr = 'style="';

			foreach ( (array) $field['section-css'] as $css_id => $css_code ) {

				$attr .= $css_id . ':' . $css_code . ';';

			}

			$attr .= '"';
		}

		return $attr;
	}


	/**
	 * Used for generating section field attr from field array
	 *
	 * @param $field
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	function get_section_filter_attr( $field ) {

		$attributes          = '';
				$attributes .= bf_show_on_attributes( $field );
		$attributes         .= sprintf( ' data-param-type="%s"', $field['type'] ?? '' );

		return $attributes;
	}


	/**
	 * Used for creating field input desc
	 *
	 * @param $field
	 *
	 * @since 2.0
	 *
	 * @return string
	 */
	function get_filed_input_desc( $field ) {

		if ( isset( $field['input-desc'] ) ) {
			return '<div class="input-desc">' . $field['input-desc'] . '</div>'; // escaped before
		} else {
			return '';
		}

	}


	/**
	 * Return The HTML Output of Tabs
	 *
	 * @since 1.0
	 * @return string
	 */
	public function get_tabs() {

		// Generate Tabs Array
		$tabs_array   = [];
		$prev_tab_key = 0;
		$menu_items   = [];

		foreach ( (array) $this->items['fields'] as $field ) {

			if ( ! isset( $field['type'] ) ) {
				continue;
			}

			if ( isset( $field['type'] ) && $field['type'] == 'tab' || $field['type'] == 'subtab' ) {
				$tabs_array[] = $field;
			}
		}

		foreach ( $tabs_array as $k => $v ) {
			$token = $v['id'];
			// Capture the token.
			$v['token'] = $token;
			if ( $v['type'] == 'tab' ) {
				$menu_items[ $token ] = $v;
				$prev_tab_key         = $token;
			}
			if ( $v['type'] == 'subtab' ) {
				$menu_items[ $prev_tab_key ]['children'][] = $v;
			}
		}

		if ( ! $menu_items ) {
			return '';
		}

		$output  = '';
		$output .= '<ul>';

		foreach ( $menu_items as $tab_id => $tab ) {
			$hasChildren = isset( $tab['children'] ) && bf_count( $tab['children'] ) > 0;
			$class       = $hasChildren ? 'has-children' : '';

			if ( isset( $tab['margin-top'] ) ) {
				$class .= ' margin-top-' . $tab['margin-top'];
			}

			if ( isset( $tab['margin-bottom'] ) ) {
				$class .= ' margin-bottom-' . $tab['margin-bottom'];
			}
			if ( isset( $tab['ajax-tab'] ) ) {
				$class .= ' bf-ajax-tab';
			}

			if ( isset( $tab['level'] ) ) {
				$class .= ' bf-tab-indent-' . $tab['level'];
			}

			if ( ! empty( $tab['ajax-section'] ) ) {
				$class .= ' bf-ajax-section ' . sanitize_html_class( $tab['ajax-section'] );
			}

			$output .= '<li class="' . $class . '" data-go="' . $tab_id . '">';
			$output .= '<a href="#" class="bf-tab-item-a" data-go="' . $tab['id'] . '">';

			// Icon
			if ( isset( $tab['icon'] ) && ! empty( $tab['icon'] ) ) {

				$output .= bf_get_icon_tag( $tab['icon'] ) . ' ';

			}

			$output .= $tab['name'];

			// Adds badge to tab
			if ( isset( $tab['badge'] ) && isset( $tab['badge']['text'] ) ) {

				$badge_style = '';

				if ( isset( $tab['badge']['color'] ) ) {
					$badge_style = "style='background-color:{$tab['badge']['color']};border-color:{$tab['badge']['color']}'";
				}

				$output .= "<span class='bf-tab-badge' {$badge_style}>{$tab['badge']['text']}</span>";
			}

			$output .= '</a>';

			if ( $hasChildren ) {

				$output .= '<ul class="sub-nav">';

				foreach ( $tab['children'] as $child ) {

					$output .= '<li>';
					$output .= '<a href="#" class="bf-tab-subitem-a" data-go="' . $child['id'] . '">' . $child['name'] . '</a>'; // escaped before
					$output .= '</li>';
				}

				$output .= '</ul>';

			}

			$output .= '</li>';
		}

		$output .= '</ul>';

		return $output;

	}


	/**
	 * @return array
	 */
	public function get_items() {

		return $this->items;
	}


	/**
	 * @param array $items
	 */
	public function set_items( $items ) {

		$this->items = $items;
	}


	/**
	 * @return array
	 */
	public function get_fields() {

		return $this->items['fields'];
	}


	/**
	 * @param array $fields
	 */
	public function set_fields( $fields ) {

		$this->items['fields'] = $fields;
	}

	public function get_field_value( $field_id ) {

		return $this->values[ $field_id ] ?? null;
	}


	/**
	 * Converts field to global standard field
	 *
	 * @param array  $field Field array
	 * @param string $type  Generator type
	 *
	 * @return mixed
	 */
	public function standardize_field( $field, $type = 'all' ) {

		//
		// Group fix
		//
		{
			// group level fix types
			$_group_level_fix = [
				'group'       => '',
				'group_close' => '',
			];

			if ( isset( $_group_level_fix[ $field['type'] ] ) && ! isset( $field['level'] ) ) {
				$field['level'] = 0;
			}

			}

			return $field;
	}

	protected function normalize_values(): bool {

		if ( empty( $this->items['fields'] ) ) {

			return false;
		}

		foreach ( $this->items['fields'] as $control ) {

			if ( ! isset( $control['type'] ) || $control['type'] !== 'multiple_controls' ) {

				continue;
			}

			$values = [];

			foreach ( $control['controls'] ?? [] as $inner_control ) {

				if ( empty( $inner_control['id'] ) ) {

					continue;
				}

				$inner_control_id    = $inner_control['id'];
				$inner_control_value = $this->get_field_value( $inner_control_id );

				if ( isset( $inner_control_value ) ) {

					$values[ $inner_control_id ] = $inner_control_value;
				}
			}

			$this->values[ $control['id'] ] = $values;
		}

		return true;
	}

} // BF_Admin_Fields
