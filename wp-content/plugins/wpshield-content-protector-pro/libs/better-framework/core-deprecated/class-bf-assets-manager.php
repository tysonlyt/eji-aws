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
 * Handles enqueue scripts and styles for preventing conflict and also multiple version of assets in on page
 */
class BF_Assets_Manager {

	/**
	 * Contains list of active modals that should be printed in bottom of page
	 *
	 * @var array
	 */
	public static $active_modals;

	/**
	 * Contains footer js codes
	 *
	 * @var array
	 */
	private $footer_js = [];


	/**
	 * Contains head js codes
	 *
	 * @var array
	 */
	private $head_js = [];


	/**
	 * Contains footer js codes
	 *
	 * @var array
	 */
	private $footer_jquery_js = [];


	/**
	 * Contains head js codes
	 *
	 * @var array
	 */
	private $head_jquery_js = [];


	/**
	 * Contains footer css codes
	 *
	 * @var array
	 */
	private $footer_css = [];


	/**
	 * Contains head css codes
	 *
	 * @var array
	 */
	private $head_css = [];


	/**
	 * Contains admin footer js codes
	 *
	 * @var array
	 */
	private $admin_footer_js = [];


	/**
	 * Contains admin head js codes
	 *
	 * @var array
	 */
	private $admin_head_js = [];


	/**
	 * Contains admin footer css codes
	 *
	 * @var array
	 */
	private $admin_footer_css = [];


	/**
	 * Contains admin head css codes
	 *
	 * @var array
	 */
	private $admin_head_css = [];

	/**
	 * Contains header codes
	 *
	 * @var array
	 */
	private $head_codes = [];


	function __construct() {

		// Front End Inline Codes
		add_action( 'wp_head', [ $this, 'print_head' ], 100 );
		add_action( 'wp_head', [ $this, 'force_head_print' ], 100 );
		add_action( 'wp_footer', [ $this, 'print_footer' ], 100 );

		// Backend Inline Codes
		add_action( 'admin_head', [ $this, 'force_head_print' ], 100 );
		add_action( 'admin_head', [ $this, 'print_admin_head' ], 100 );
		add_action( 'admin_footer', [ $this, 'print_admin_footer' ], 100 );
	}


	/**
	 * DRY!
	 *
	 * @param array  $code
	 * @param string $type
	 * @param string $comment
	 * @param string $before
	 * @param string $after
	 */
	private function _print( $code = [], $type = 'style', $comment = '', $before = '', $after = '' ) {

		$output = '';

		foreach ( (array) $code as $_code ) {
			$output .= $_code . "\n";
		}

		if ( $output ) {

			if ( ! empty( $comment ) ) {
				echo "\n<!-- {$comment} -->\n<{$type}>{$before}\n{$output}\n{$after}</{$type}>\n<!-- /{$comment}-->\n";
			} else {
				echo "\n<{$type}>{$before}\n{$output}\n{$after}</{$type}>\n";
			}
		}

	}


	/**
	 * Filter Callback: used for printing style and js codes in header
	 */
	function print_head() {

		$this->_print( $this->head_css, 'style', __( 'BetterFramework Head Inline CSS', 'better-studio' ) );
		$this->head_css = [];

		$this->_print( $this->head_js, 'script', __( 'BetterFramework Head Inline JS', 'better-studio' ) );
		$this->head_js = [];

		$this->_print( $this->head_jquery_js, 'script', __( 'BetterFramework Head Inline jQuery Code', 'better-studio' ), 'jQuery(function($){', '});' );
		$this->head_jquery_js = [];

	}


	/**
	 * Filter Callback: used for printing style and js codes in footer
	 */
	function print_footer() {

		// Print header lagged CSS
		$this->_print( $this->head_css, 'style', __( 'BetterFramework Header Lagged Inline CSS', 'better-studio' ) );

		// Print footer CSS
		$this->_print( $this->footer_css, 'style', __( 'BetterFramework Footer Inline CSS', 'better-studio' ) );

		// Print header lagged JS
		$this->_print( $this->head_js, 'script', __( 'BetterFramework Header Lagged Inline JS', 'better-studio' ) );

		// Print header lagged jQuery JS
		$this->_print( $this->head_jquery_js, 'script', __( 'BetterFramework Header Lagged Inline jQuery JS', 'better-studio' ), 'jQuery(function($){', '});' );

		// Print footer JS
		$this->_print( $this->footer_js, 'script', __( 'BetterFramework Footer Inline JS', 'better-studio' ) );

		// Print footer jQuery JS
		$this->_print( $this->footer_jquery_js, 'script', __( 'BetterFramework Footer Inline jQuery JS', 'better-studio' ), 'jQuery(function($){', '});' );

	}


	/**
	 * Filter Callback: used for printing style and js codes in admin header
	 */
	function print_admin_head() {

		// Print admin header CSS
		$this->_print( $this->admin_head_css, 'style', __( 'BetterFramework Admin Head Inline CSS', 'better-studio' ) );
		$this->admin_head_css = [];

		// Print admin header JS
		$this->_print( $this->admin_head_js, 'script', __( 'BetterFramework Head Inline JS', 'better-studio' ) );
		$this->admin_head_js = [];

	}


	/**
	 * Filter Callback: used for printing style and js codes in admin footer
	 */
	function print_admin_footer() {

		// Print header lagged CSS
		$this->_print( $this->admin_head_css, 'style', __( 'BetterFramework Admin Header Lagged Inline CSS', 'better-studio' ) );

		// Print footer CSS
		$this->_print( $this->admin_footer_css, 'style', __( 'BetterFramework Admin Footer Inline CSS', 'better-studio' ) );

		// Print header lagged JS
		$this->_print( $this->admin_head_js, 'script', __( 'BetterFramework Admin Footer Inline JS', 'better-studio' ) );

		// Print footer JS
		$this->_print( $this->admin_footer_js, 'script', __( 'BetterFramework Admin Footer Inline JS', 'better-studio' ) );

	}


	protected function force_print( $code = [], $type = 'style', $comment = '', $before = '', $after = '' ) {

		$func_get_args = func_get_args();

		// Before head script print or inside TinyMCE ajax callback
		if ( bf_is_doing_ajax( 'fetch-mce-view-shortcode' ) ||
			 bf_is_block_render_request() ||
			 did_action( is_admin() ? 'admin_head' : 'wp_head' )
		) {
			$this->_print( $code, $type, $comment, $before, $after );
		} else {
			$this->head_codes[] = $func_get_args;
		}
	}


	public function force_head_print() {

		if ( $this->head_codes ) {
			foreach ( $this->head_codes as $args ) {
				call_user_func_array( [ $this, '_print' ], $args );
			}

			$this->head_codes = [];
		}
	}


	/**
	 * Used for adding inline js
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function add_js( $code = '', $to_top = false, $force = false ) {

		if ( $force ) {
			$this->force_print( $code, 'script' );

			return;
		}

		if ( $to_top ) {
			$this->head_js[] = $code;
		} else {
			$this->footer_js[] = $code;
		}
	}


	/**
	 * Used for adding inline js
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function add_jquery_js( $code = '', $to_top = false, $force = false ) {

		if ( $force ) {
			$this->force_print( $code, 'script', 'jQuery(function($){', '});' );

			return;
		}

		if ( $to_top ) {
			$this->head_jquery_js[] = $code;
		} else {
			$this->footer_jquery_js[] = $code;
		}

	}


	/**
	 * Used for adding inline css
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function add_css( $code = '', $to_top = false, $force = false ) {

		//
		// Handle inline custom css code inside AMP
		//
		if ( bf_is_amp() === 'better' ) {

			better_amp_add_inline_style( $code );

			return;
		}

		if ( $force ) {
			$this->force_print( $code, 'style' );

			return;
		}

		if ( $to_top ) {
			$this->head_css[] = $code;
		} else {
			$this->footer_css[] = $code;
		}
	}


	/**
	 * Used for adding inline js
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function add_admin_js( $code = '', $to_top = false, $force = false ) {

		if ( $force ) {
			$this->force_print( $code, 'script' );

			return;
		}

		if ( $to_top ) {
			$this->admin_head_js[] = $code;
		} else {
			$this->admin_footer_js[] = $code;
		}

	}


	/**
	 * Used for adding inline css
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function add_admin_css( $code = '', $to_top = false, $force = false ) {

		if ( $force ) {
			$this->force_print( $code, 'style' );

			return;
		}

		if ( $to_top ) {
			$this->admin_head_css[] = $code;
		} else {
			$this->admin_footer_css[] = $code;
		}

	}


	/**
	 * Enqueue styles safely
	 *
	 * @param $style_key
	 */
	function enqueue_style( $style_key = '' ) {

		bf_enqueue_style( $style_key );
	}


	/**
	 * Enqueue scripts safely
	 *
	 * @param $script_key
	 */
	function enqueue_script( $script_key ) {

		bf_enqueue_script( $script_key );
	}


	public static function print_ace_editor_oldie_js() {

		static $loaded = false;

		if ( $loaded ) {
			return;
		}
		?>
		<!--[if lt IE 9]>
		<script type='text/javascript'
				src='https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.8/ext-old_ie.js'></script>
		<![endif]-->
		<?php

		$loaded = true;
	}


	/**
	 * Adds modals to active modals list
	 *
	 * @param $modal_id
	 */
	public static function add_modal( $modal_id ) {

		self::$active_modals[ $modal_id ] = $modal_id;
	}


	/**
	 * Callback: Hooked to admin_footer to print all modals in bottom of page
	 */
	public static function enqueue_modals() {

		foreach ( (array) self::$active_modals as $modal ) {

			self::load_template( $modal, false );
		}

	} // enqueue_modals

	/**
	 * @param string $modal_id
	 * @param bool   $return
	 *
	 * @return string
	 */
	public static function load_template( $modal_id, $return = true ) {

		$modal_template_file = BF_PATH . '/core-deprecated/field-generator/modals/' . $modal_id . '.php';

		if ( $return ) {
			ob_start();
		}

		if ( file_exists( $modal_template_file ) ) {

			include $modal_template_file;
		}

		if ( $return ) {
			return ob_get_clean();
		}

		return '';
	}
}
