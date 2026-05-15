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


// Prevent Direct Access
defined( 'ABSPATH' ) or die;


/**
 * BF Admin Pages Main Class
 *
 * @since   2.0
 * @package BetterFramework
 */
abstract class BF_Admin_Page extends BF_Admin_Panel {


	/**
	 * Holds everything about the front-end template
	 *
	 * @since   2.0
	 *
	 * @access  protected
	 * @var     string
	 */
	public $template = [];


	/**
	 * Holds all arguments
	 *
	 * @since   2.0
	 *
	 * @access  protected
	 * @var     string
	 */
	protected $args = [];


	/**
	 * contains page id
	 *
	 * @since   2.0
	 *
	 * @access  protected
	 * @var string
	 */
	protected $page_id = '';


	/**
	 * Constructor Function
	 *
	 * @param array $args         Page arguments ( preserved for future )
	 * @param bool  $only_backend Run page only in backend
	 *
	 * @since   2.0
	 * @access  public
	 *
	 * @return  BF_Admin_Page
	 */
	public function __construct( $args = [], $only_backend = true ) {

		// Run only in admin
		if ( $only_backend && ! is_admin() ) {
			return;
		}

		if ( ! isset( $args['dir-uri'] ) ) {
			$args['dir-uri'] = '';
		}

		// Prepare ID
		if ( isset( $args['id'] ) ) {
			$this->page_id = $args['id'];
		}

		// Prepare Slug
		if ( ! isset( $args['slug'] ) ) {
			$args['slug'] = $args['id'];
		}

		// Prepare template
		if ( isset( $args['template'] ) ) {

			if ( $args['template'] === 'custom' && isset( $args['template-file'] ) ) {
				$this->template['type']          = 'custom';
				$this->template['template-file'] = $args['template-file'];
			} else {
				$this->template['type'] = $args['template'];
			}
		} else {
			$this->template['type'] = 'minimal-1';
		}

		// Save args
		$this->args = $args;

		// Callback for adding admin menu
		add_action( 'better-framework/admin-menus/admin-menu/before', [ $this, 'add_menu' ] );

		// Callback for adding page custom classes
		add_filter( 'admin_body_class', [ $this, 'admin_body_class' ], 999 );

	}


	/**
	 * Used for detecting current page is a BF admin page or not
	 *
	 * @since   2.0
	 *
	 * @return bool
	 */
	function is_current_admin_page() {

		if ( ! isset( $_GET['page'] ) ) {
			return false;
		}

		$page = explode( '/', $_GET['page'] );

		if ( empty( $page[1] ) ) {

			return 'better-studio' === $page[0];
		}

		if ( $page[1] == $this->args['slug'] ) {
			return true;
		} else {
			return false;
		}

	}


	/**
	 * Callback: Used for adding page custom classes to admin body
	 *
	 * @param $classes
	 *
	 * @since   2.0
	 *
	 * @return string
	 */
	function admin_body_class( $classes ) {

		if ( ! $this->is_current_admin_page() ) {
			return $classes;
		}

		$classes = explode( ' ', $classes );

		$classes = array_flip( $classes );

		$classes['bf-admin-panel']                                      = 'bf-admin-panel';
		$classes['hide-notices']                                        = 'hide-notices';
		$classes['bf-admin-page']                                       = 'bf-admin-page';
		$classes[ 'bf-admin-page-template-' . $this->template['type'] ] = 'bf-admin-page-template-' . $this->template['type'];

		// Custom classes per page
		if ( isset( $this->args['class'] ) ) {

			if ( is_array( $this->args['class'] ) ) {

				$custom_classes = $this->args['class'];

			} else {

				$custom_classes = explode( ' ', $this->args['class'] );

			}

			foreach ( $custom_classes as $class ) {

				$classes[ $class ] = $class;

			}
		}

		return implode( ' ', $classes );
	}


	/**
	 * Callback: Used for registering menu to WordPress
	 *
	 * Action: better-framework/admin-menus/admin-menu/before
	 *
	 * @since   2.0
	 * @access  public
	 *
	 * @return  void
	 */
	public function add_menu() {

		return;

	}


	/**
	 * Page Title
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_title() {

		return '';

	}


	/**
	 * Page Pre Title
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_pre_title() {

		return '';

	}


	/**
	 * Page header description
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_desc() {

		return '';

	}


	/**
	 * Page body
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_body() {

		return '';

	}

	/**
	 * Page logo url.
	 *
	 * @since   2.0
	 * @return string
	 */
	protected function get_logo() {

		return '';

	}


	/**
	 * Used for getting page directory that is useful to enqueue scripts and styles
	 *
	 * @since   2.0
	 * @return mixed
	 */
	public function get_dir_uri() {

		return $this->args['dir-uri'];
	}


	/**
	 * Used for render page
	 *
	 * @since 2.0
	 * @return void
	 */
	public function display() {

		$output = '';

		/**
		 * Fires before display page
		 *
		 * @param string $args arguments
		 *
		 * @since   2.0
		 */
		do_action( 'better-framework/admin-page/' . $this->page_id . '/before', $this, $output );

		switch ( $this->template['type'] ) {

			// Custom template file
			case 'custom':
				$template_file = $this->template['template-file'];

				$body = $this->get_body();

				break;

			default:
				$template_file = bf_get_dir( 'admin-page/templates/' . $this->template['type'] . '.php' );

				$body = $this->get_body();
		}

		$body = apply_filters( 'better-framework/admin-page/' . $this->page_id . '/body', $body, $this );

		// Capture output
		ob_start();

		require $template_file;

		$output .= ob_get_clean();

		/**
		 * Fires after display page
		 *
		 * @param string $args arguments
		 *
		 * @since   2.0
		 */
		do_action( 'better-framework/admin-page/' . $this->page_id . '/after', $this, $output );

		// Print output
		echo $output; // escaped before

	}

}
