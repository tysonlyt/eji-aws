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

use Elementor\Plugin as ElementorPlugin;

/**
 * Base class for all shortcodes that have some general functionality for all of them
 */
class BF_Shortcode {

	/**
	 * Shortcode id
	 *
	 * @var string
	 */
	public $id;


	/**
	 * Widget ID For Class Name
	 *
	 * @var string
	 */
	public $widget_id;


	/**
	 * the enclosed content (if the shortcode is used in its enclosing form)
	 *
	 * @var string
	 */
	public $content;


	/**
	 * Name Of Shortcode Used In VC
	 *
	 * @var string
	 */
	public $name = '';


	/**
	 * Description Of Shortcode Used In VC
	 *
	 * @var string
	 */
	public $description = '';


	/**
	 * Icon URL Of Shortcode Used In VC
	 *
	 * @var string
	 */
	public $icon = '';

	/**
	 * Category of Shortcode Used In Elementor
	 *
	 * @var mixed|string
	 */
	public $category = '';

	/**
	 * contains an array of attributes to add to this item
	 *
	 * @var array
	 */
	public $defaults = [];


	/**
	 * contains options for shortcode
	 *
	 * @var array
	 */
	public $options = [];


	/**
	 * Define this shortcode have widget or not
	 *
	 * @var bool
	 */
	public $have_widget = false;

	/**
	 * Define this shortcode have tinymce add-on
	 *
	 * @var bool
	 */
	public $have_tinymce_add_on = false;


	/**
	 * Define this shortcode have gutenberg block
	 *
	 * @var bool
	 */
	public $have_gutenberg_add_on = false;

	/**
	 * Constructor.
	 */
	public function __construct( $id = '', $options = [] ) {

		if ( empty( $id ) ) {
			return;
		}

		$this->id = $id;

		if ( isset( $options['defaults'] ) ) {
			$this->defaults = $options['defaults'];
			unset( $options['defaults'] );
		}

		if ( isset( $options['have_widget'] ) ) {
			$this->have_widget = $options['have_widget'];
			unset( $options['have_widget'] );
		}

		if ( isset( $options['have_tinymce_add_on'] ) ) {
			$this->have_tinymce_add_on = $options['have_tinymce_add_on'];
			unset( $options['have_tinymce_add_on'] );

			if ( $this->have_tinymce_add_on ) {
				BF_Shortcodes_Manager::register_tinymce_addon( $id );
			}
		}

		if ( isset( $options['name'] ) ) {
			$this->name = $options['name'];
		}

		$this->options = $options;

		if ( isset( $options['have_gutenberg_add_on'] ) ) {

			$this->have_gutenberg_add_on = $options['have_gutenberg_add_on'];
			unset( $options['have_gutenberg_add_on'] );

			if ( $this->have_gutenberg_add_on ) {

				BF_Gutenberg_Shortcode_Wrapper::register( $id, $this->page_builder_settings() );

				add_action( 'enqueue_block_editor_assets', [ $this, 'gutenberg_override_script_enqueue' ] );
			}
		}
	}

	/**
	 * @since 3.8.0
	 * @return array
	 */
	public function page_builder_settings() {

		return [
			'id'       => $this->id,
			'name'     => $this->id,
			'category' => $this->category,
			'desc'     => $this->description,
			'icon_url' => $this->options['icon_url'] ?? false,
			'_version' => $this->options['version'] ?? '',
		];
	}


	/**
	 * Prepares shortcodes atts
	 *
	 * @param &$atts
	 */
	public function prepare_atts( &$atts ) {

		$class = bf_shortcode_custom_css_class( $atts );
		if ( ! empty( $atts['css-class'] ) ) {
			$atts['css-class'] .= ' ' . $class;
		} else {
			$atts['css-class'] = $class;
		}

		if ( isset( $atts['bs-show-desktop'] ) && ! $atts['bs-show-desktop'] ) {
			$atts['css-class'] .= ' bs-hidden-lg';
		}

		if ( isset( $atts['bs-show-tablet'] ) && ! $atts['bs-show-tablet'] ) {
			$atts['css-class'] .= ' bs-hidden-md';
		}

		if ( isset( $atts['bs-show-phone'] ) && ! $atts['bs-show-phone'] ) {
			$atts['css-class'] .= ' bs-hidden-sm';
		}

		if ( ! empty( $atts['bs-text-color-scheme'] ) ) {
			$atts['css-class'] .= " bs-{$atts['bs-text-color-scheme']}-scheme";
		}
	}

	/**
	 * Handle shortcode
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function handle_shortcode( $atts, $content ) {

		return $this->display(
			$this->shortcode_attributes_prepare( $atts ),
			$content
		);
	}

	/**
	 * Prepare the shortcode attributes list.
	 *
	 * @param array|string $atts
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function shortcode_attributes_prepare( $atts ) {

		$atts = bf_merge_args( $atts, $this->defaults );

		$this->prepare_atts( $atts );

		$atts['shortcode-id'] = $this->id; // adds shortcode id to atts for using it inside filters

		// customize atts from outside
		$atts = apply_filters( 'better-framework/shortcodes/atts', $atts, $this->id );

		return $atts;
	}


	/**
	 * Handle widget display
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function handle_widget( $atts ) {

		$atts = bf_merge_args( $atts, $this->defaults );

		$this->prepare_atts( $atts );

		// customize atts from outside
		$atts = apply_filters( 'better-framework/widgets/atts', $atts, $this->id );

		return $this->display( $atts );

	}


	/**
	 * This function must override in child's for displaying results
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	public function display( array $atts, $content = '' ) {

		return '';
	}


	/**
	 * Method returns a proper array of attributes
	 */
	public function get_atts( $atts ) {

		return bf_merge_args( $atts, $this->defaults );
	}


	/**
	 * Method returns a string of attributes
	 */
	public function get_atts_string( $atts ) {

		$attr = '';

		foreach ( $this->get_atts( $atts ) as $key => $value ) {
			$attr .= " $key='" . trim( $value ) . "'";
		}

		return $attr;
	}


	/**
	 * Method returns the completed shortcode as a string
	 */
	public function do_shortcode( $atts = [], $content = '', $echo = false ) {

		// initializing
		$attrs = $this->get_atts_string( $atts );

		if ( $this->content ) {
			$content = $this->content . "[/$this->id]";
		}

		ob_start();
		echo do_shortcode( "[$this->id $attrs]$content" );
		$output = ob_get_clean();

		if ( $echo ) {
			//phpcs:ignore
			echo $output; // escaped before

			return '';
		}

		return $output;
	}


	/**
	 * Load widget for shortcode
	 */
	public function load_widget() {

		if ( $this->widget_id ) {
			BF_Widgets_Manager::register_widget_for_shortcode( $this->id, $this->options );
		} else {
			BF_Widgets_Manager::register_widget_for_shortcode( $this->id, $this->options );
		}
	}


	/**
	 * Get fields config array
	 *
	 * @inheritdoc This method must override in subclass
	 *
	 * @since      3.0.0
	 * @return array
	 */
	public function get_fields() {

		return [];
	}

	public function gutenberg_scripts() {

		return [];
	}


	public function gutenberg_styles() {

		return [];
	}

	public function gutenberg_override_script_enqueue() {

		$css_list = $this->gutenberg_styles();

		if ( $css_list ) {

			foreach ( $css_list as $css ) {

				$deps    = $css['deps'] ?? [];
				$version = $css['version'] ?? BF_VERSION;

				bf_enqueue_style( $this->id . '-gutenberg', $css['url'], $deps, $css['path'], $version );
			}
		}

		$js_list = $this->gutenberg_scripts();

		if ( $js_list ) {

			foreach ( $js_list as $js ) {

				$deps    = $js['deps'] ?? [];
				$version = $js['version'] ?? BF_VERSION;

				bf_enqueue_script( $this->id . '-gutenberg', $js['url'], $deps, $js['path'], $version );
			}
		}
	}


	/**
	 * it's just for backward compatibility.
	 *
	 * @return array
	 */
	public function vc_map_listing_all() {

		return [];
	}


	/**
	 * Tinymce View Settings
	 *
	 * @return array {
	 *
	 * @type string $name           name of the shortcode
	 * @type array  $scripts        dedicated scripts for the shortcode. array like bf_enqueue_tinymce_style() return
	 *       values
	 * @type array  $style          dedicated styles  for the shortcode. array like bf_enqueue_tinymce_style() return
	 *       values
	 *
	 * @type array  $sub_shortcodes information to insert a new shortcode inside the
	 *                               main one EX:[tabs] [tab][/tab] [/tabs].   array{
	 *      'repeater field id' => 'shortcodeName',
	 *      ...
	 *
	 *      EX: array(
	 *       'single_tab_settings' => 'tab'
	 *      )
	 *      it will we collect each repeater item, and the create [tab attr1=a attr2=b]
	 * }
	 * }
	 */
	public function tinymce_settings() {

		return [];
	}

	public function sorter_backward_compatible( $value ) {

		if ( ! is_array( $value ) ) {

			return $value;
		}

		$value = array_map(
			static function ( $item ) {

				return ! empty( $item['active'] ) && 'false' !== $item['active'] ? $item['id'] : false;

			},
			$value
		);

		$value = array_filter( $value );

		return implode( ',', $value );
	}
}
