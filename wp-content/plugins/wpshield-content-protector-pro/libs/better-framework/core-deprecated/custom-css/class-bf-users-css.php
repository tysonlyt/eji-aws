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
 * BF users custom css generator
 */
class BF_Users_CSS extends BF_Custom_CSS {

	/**
	 * Contains Current User ID
	 *
	 * @var int
	 */
	public $user_id = 0;


	/**
	 * prepare functionality
	 */
	function __construct() {

		// Clear Cache Callbacks
		add_action( 'delete_user', [ $this, 'clear_cache' ] );
		add_action( 'user_register', [ $this, 'clear_cache' ] );
		add_action( 'profile_update', [ $this, 'clear_cache' ] );

		// Register Custom CSS
		add_action( 'wp_enqueue_scripts', [ $this, 'register_custom_css' ], 100 );

		// Output Custom CSS Codes
		add_action( 'template_redirect', [ $this, 'global_custom_css' ], 1 );

	}


	/**
	 * Register BF Custom CSS Codes For Theme Specified Fields
	 *
	 * - Action Callback
	 */
	function register_custom_css() {

		// Only in user archive pages
		if ( ! is_author() ) {
			return;
		}

		$current_user = bf_get_author_archive_user();

		if ( ! $current_user ) {
			return;
		}

		// get last cached time
		$css_meta_cached_time = get_user_meta( $current_user->ID, '_bf_user_css_cached' );

		// if not cached then generate css and cache it
		if ( ! $css_meta_cached_time ) {
			$this->load_user_fields( $current_user->ID );
			$css_meta_cached_time = get_user_meta( $current_user->ID, '_bf_user_css_cached' );
		}

		// load main css
		$css_meta = get_user_meta( $current_user->ID, '_bf_user_css' );

		// if any css is cached before then enqueue it
		if ( $css_meta ) {
			wp_enqueue_style( 'better-framework-user-custom', get_site_url() . '/?bf_user_custom_css=1&user_id=' . $current_user->ID . '&ver=' . current( $css_meta_cached_time ), [], null );
		}

	}


	/**
	 * Output Custom CSS
	 *
	 * - Action Callback
	 */
	public function global_custom_css() {

		// just when custom css requested
		if (
			empty( $_GET['bf_user_custom_css'] ) ||
			intval( $_GET['bf_user_custom_css'] ) != 1 ||
			! isset( $_GET['user_id'] ) ||
			empty( $_GET['user_id'] )

		) {
			return;
		}

		$this->user_id = intval( $_GET['user_id'] );
		$this->display();
		exit;
	}


	/**
	 * Callback: Clear Cached CSS
	 *
	 * Action: delete_user
	 * Action: user_register
	 */
	public function clear_cache( $user_ID ) {

		delete_user_meta( $user_ID, '_bf_user_css' );
		delete_user_meta( $user_ID, '_bf_user_css_cached' );

	}


	/**
	 * Load all fields
	 */
	function load_all_fields() {

		$this->fields = apply_filters( 'better-framework/css/users', $this->fields );
		$this->load_user_fields( $this->user_id );

	}


	/**
	 * Loads Fields For User
	 *
	 * @param bool $user_id
	 */
	function load_user_fields( $user_id = false ) {

		if ( $user_id == false ) {
			$user_id = bf_get_author_archive_user();
			$user_id = $user_id->ID;
		}

		// load from cache if available
		$css_meta_cached_time = get_user_meta( $user_id, '_bf_user_css_cached', true );
		if ( $css_meta_cached_time !== '' ) {

			$css_meta = get_user_meta( $user_id, '_bf_user_css' );

			if ( ! $css_meta ) {
				return;
			} else {
				foreach ( $css_meta as $user_meta ) {
					$this->fields = array_merge( $this->fields, $user_meta );
				}

				return;
			}
		}

		// save current time to user cached time
		add_user_meta( $user_id, '_bf_user_css_cached', time() );

		$css_cache = [];

		// Iterate All Meta Box's
		foreach ( BF_User_Metabox_Core::$metabox as $metabox_id => $metabox ) {

			if ( ! isset( $metabox['css'] ) || ! $metabox['css'] ) {
				continue;
			}

			if ( isset( $metabox['panel-id'] ) ) {
				$css_id = $this->get_css_id( $metabox['panel-id'] );
			} else {
				$css_id = 'css';
			}

			$metabox_css = BF_User_Metabox_Core::get_metabox_css( $metabox_id );

			if ( empty( $metabox_css ) || ! is_array( $metabox_css ) ) {
				continue;
			}

			$css_cache = [];

			// Each field of Metabox
			foreach ( $metabox_css as $field_key => $field ) {

				// continue when haven't css field
				if ( ! isset( $field[ $css_id ] ) ) {
					if ( ! isset( $field['css'] ) ) {
						continue;
					}
				}

				// If Field Value Saved
				if ( false == ( $field_saved_value = get_user_meta( $user_id, $field['id'], true ) ) ) {
					continue;
				}

				if ( isset( $field[ $css_id ] ) ) {
					$_temp_css_field = $field[ $css_id ];
				} elseif ( isset( $field['css'] ) ) {
					$_temp_css_field = $field['css'];
				} else {
					continue;
				}

				// prepare selectors
				foreach ( $_temp_css_field as $_temp_css_field_key => $_temp_css_field_value ) {

					// prepare selectors
					if ( is_array( $_temp_css_field[ $_temp_css_field_key ]['selector'] ) ) {

						foreach ( $_temp_css_field[ $_temp_css_field_key ]['selector'] as $selector_key => $selector ) {
							if ( strpos( $selector, '%%user-id%%' ) !== false ) {
								$_temp_css_field[ $_temp_css_field_key ]['selector'][ $selector_key ] = str_replace( '%%user-id%%', $user_id, $_temp_css_field[ $_temp_css_field_key ]['selector'][ $selector_key ] );
							}
						}
					} else {
						$_temp_css_field[ $_temp_css_field_key ]['selector'] = str_replace( '%%user-id%%', $user_id, $_temp_css_field[ $_temp_css_field_key ]['selector'] );
					}
				}

				$_temp_css_field['value'] = $field_saved_value;

				$css_cache[] = $_temp_css_field;
			}

			// remove without data background image fields
			foreach ( $css_cache as $key => $meta_css ) {
				if ( isset( $meta_css['value']['img'] ) && $meta_css['value']['img'] == '' ) {
					unset( $css_cache[ $key ] );
				}
			}

			if ( ! $css_cache ) {
				add_user_meta( $user_id, '_bf_user_css', $css_cache );
				$this->fields = array_merge( $this->fields, $css_cache );
			}
		}
	}
}
