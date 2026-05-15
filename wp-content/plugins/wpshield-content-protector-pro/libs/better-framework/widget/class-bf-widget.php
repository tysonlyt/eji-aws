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

// use Control API
use BetterFrameworkPackage\Component\Control;

/**
 * Base class for widgets
 */
class BF_Widget extends WP_Widget {


	/**
	 * Widget Position in wp-admin/widgets.php
	 *
	 * @var int
	 */
	public $position = 30;

	/**
	 * $default values for widget fields
	 *
	 * @var array
	 */
	public $defaults = [];


	/**
	 * Flag to load default only one time
	 *
	 * @var bool
	 */
	public $defaults_loaded = false;


	/**
	 * Contains shortcode id of widget
	 *
	 * @var string
	 */
	public $base_widget_id;


	/**
	 * Contain all fields of widget
	 *
	 * @var array
	 */
	public $fields = [];


	/**
	 * Show widget title
	 *
	 * @var bool
	 */
	public $with_title = true;


	/**
	 * Register widget with WordPress.
	 *
	 * @param string $shortcode_id
	 * @param string $title
	 * @param array  $desc
	 * @param bool   $widget_id
	 */
	public function __construct( $shortcode_id = '', $title = '', $desc = [], $widget_id = false ) {

		if ( empty( $shortcode_id ) ) {
			return;
		}

		$this->base_widget_id = $shortcode_id;

		if ( false !== $widget_id ) {
			parent::__construct(
				$widget_id,
				$title,
				$desc
			);
		} else {
			parent::__construct(
				$shortcode_id,
				$title,
				$desc
			);
		}
	}

	/**
	 * Return the widget id.
	 *
	 * The function should override in sub-classes.
	 *
	 * @since 3.13.2
	 * @return string The widget id.
	 */
	public static function widget_id() {

		return '';
	}

	/**
	 * Loads widget -> shortcode default attrs
	 */
	public function load_defaults() {

		if ( $this->defaults_loaded ) {
			return;
		}

		$this->defaults_loaded = true;
		$this->defaults        = BF_Shortcodes_Manager::factory( $this->base_widget_id, [], true )->defaults;
	}


	/**
	 * Loads fields
	 */
	public function load_fields() {
	}


	/**
	 * Prepare fields for field generator
	 */
	public function prepare_fields() {

		foreach ( $this->fields as $field_id => $_ ) {

			if ( isset( $this->fields[ $field_id ]['attr_id'] ) ) { // Backward compatibility
				$this->fields[ $field_id ]['id'] = $this->fields[ $field_id ]['attr_id'];
			}
			// do not do anything on fields that haven't ID, ex: group fields
			if ( ! isset( $this->fields[ $field_id ]['id'] ) ) {
				continue;
			}

			$this->fields[ $field_id ]['input_name'] = $this->get_field_name( $this->fields[ $field_id ]['id'] );

			//phpcs:ignore
			// TODO: check this
			// $this->fields[ $field_id ]['id'] = $this->get_field_id( $this->fields[ $field_id ]['id'] );

			if ( 'repeater' === $this->fields[ $field_id ]['type'] ) {

				foreach ( $this->fields[ $field_id ]['options'] as $_id => $_item ) {

					$this->fields[ $field_id ]['options'][ $_id ]['input_name'] = $this->fields[ $field_id ]['input_name'] . '[%d][' . $_item['id'] . ']';
				}
			} elseif ( 'select' === $this->fields[ $field_id ]['type'] && ! empty( $this->fields[ $field_id ]['multiple'] ) && $this->fields[ $field_id ]['multiple'] ) {
				$this->fields[ $field_id ]['input_name'] .= '[]';
			}
		}
	}


	/**
	 * Merge two arrays to one, if $atts key not defined or is empty then $default value will be set.
	 *
	 * @param $default
	 * @param $atts
	 *
	 * @return mixed
	 */
	public function parse_args( $default, $atts ) {

		foreach ( (array) $default as $key => $value ) {

			// empty fields in $atts is ok!
			if ( ! isset( $atts[ $key ] ) ) {
				$atts[ $key ] = $value;
			}
		}

		$atts['shortcode-id'] = $this->base_widget_id; // adds shortcode id to atts for using it inside filters

		return $atts;
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see BetterWidget::widget()
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		$this->load_defaults();
		$instance = $this->parse_args( $this->defaults, $instance );

		//phpcs:ignore
		echo $args['before_widget'];  // escaped before inside WP

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->base_widget_id );
		if ( ! empty( $title ) && $this->with_title ) {
			//phpcs:ignore
			echo $args['before_title'] . $title . $args['after_title']; // escaped before inside WP
		}

		//phpcs:ignore
		echo BF_Shortcodes_Manager::factory( $this->base_widget_id, [], true )->handle_widget( $instance ); // escaped before inside WP

		//phpcs:ignore
		echo $args['after_widget']; // escaped before inside WP
	}


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$this->load_defaults();
		$this->load_fields();

		$values = $this->parse_args( $this->defaults, $new_instance );
		$fields = $this->get_fields();

		foreach ( $values as $field_id => $value ) {

			$new_value = \BetterFrameworkPackage\Component\Control\filter_control_value( $fields[ $field_id ]['type'] ?? '', $value, $fields[ $field_id ] ?? null );

			if ( isset( $new_value ) ) {

				$values[ $field_id ] = $new_value;
			}
		}

		return $values;
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {

		$this->load_defaults();
		$instance = $this->parse_args( $this->defaults, $instance );

		Better_Framework::factory( 'widgets-field-generator', false, true );

		// prepare fields for generator
		$this->load_fields();
		$this->prepare_fields();
		$options = [
			'fields' => $this->fields,
		];

		/**
		 * Keep Widget Group State After Widget Settings Saved
		 */
		//phpcs:ignore
		if ( ! empty( $_POST['_group_status'] ) ) {
			foreach ( $options['fields'] as $idx => $field ) {
				if ( 'group' === $field['type'] && ! empty( $field['id'] ) ) {
					$id = &$field['id'];

					//phpcs:ignore
					if ( ! empty( $_POST['_group_status'][ $id ] ) ) {
						//phpcs:ignore
						$options['fields'][ $idx ]['state'] = $_POST['_group_status'][ $id ];
					}
				}
			}
		}

		$generator = new BF_Widgets_Field_Generator( $options, $instance );

		//phpcs:ignore
		echo $generator->get_fields(); // escaped before
	}


	public function get_fields() {

		return $this->fields;
	}
}
