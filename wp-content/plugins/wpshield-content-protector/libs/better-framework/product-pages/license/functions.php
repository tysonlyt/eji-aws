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
 * Callback: enqueue product registration static files in welcome page.
 * action  :  admin_enqueue_scripts
 */

if ( ! function_exists( 'bf_welcome_admin_enqueue' ) ) {

	function bf_welcome_admin_enqueue() {

		if ( bf_is_product_page( 'license' ) ) {

			bf_register_product_enqueue_scripts();
		}
	}
}

add_action( 'admin_enqueue_scripts', 'bf_welcome_admin_enqueue' );


if ( ! function_exists( 'bf_register_product_enqueue_scripts' ) ) {

	/**
	 * Enqueue product registration static file dependencies.
	 */
	function bf_register_product_enqueue_scripts() {

		wp_enqueue_script( 'bs-register-product', BF_Product_Pages::get_url( 'license/assets/js/register-product.js' ), [], BF_VERSION );

		wp_localize_script(
			'bs-register-product',
			'bs_register_product',
			[
				'messages' => [
					'success' => wp_kses( __( '<b>Congratulations, your license is now registered.</b>', 'better-studio' ), bf_trans_allowed_html() ),
				],
			]
		);
	} // bf_register_product_enqueue_scripts
}

if ( ! function_exists( 'bf_register_product_get_info' ) ) {

	/**
	 * Retrieve product registration information
	 *
	 * @see \bf_bs_register_product_params
	 * @return bool|array array of data on success or false on failure. array data always have this indexes {
	 * @type int    $last_check    last time status was updated timestamp.
	 * @type string $purchase_code envato marketplace product code
	 * }
	 */
	function bf_register_product_get_info( $id = '15801051' ) {

		/**
		 * @see \BetterFramework_Oculus::$auth
		 */
		$auth        = apply_filters( "better-framework/product-pages/$id/auth", [] );
		$option_name = sprintf( '%s-register-info', $id );

		return array_merge( (array) $auth, (array) get_option( $option_name, [] ) );
	} // bf_register_product_get_info
}

if ( ! function_exists( 'bf_register_product_clear_info' ) ) {

	function bf_register_product_clear_info( $item_id = '15801051' ) {

		$option_name = sprintf( '%s-register-info', $item_id );

		return delete_option( $option_name );
	}
}

if ( ! function_exists( 'bf_register_product_set_info' ) ) {

	/**
	 * Array of data about product registration
	 *
	 * @param array $data
	 *
	 * @return bool true when data updated or false otherwise.
	 */
	function bf_register_product_set_info( $id, $data ) {

		/**
		 * @see \BetterFramework_Oculus::$auth
		 */
		$auth        = apply_filters( "better-framework/product-pages/$id/auth", [] );
		$option_name = sprintf( '%s-register-info', $id );
		$data        = bf_merge_args(
			$data,
			[
				'last_check'    => time(),
				'purchase_code' => $auth['purchase_code'],
			]
		);

		// Clear plugins list cache
		delete_option( 'bf-plugins-config' );

		return update_option( $option_name, $data );
	} // bf_register_product_set_info
}

if ( ! function_exists( 'bf_is_product_registered' ) ) {

	/**
	 * check product activated?
	 *
	 * @return bool
	 */
	function bf_is_product_registered( $id = '15801051' ) {

		$option_name = sprintf( '%s-register-info', $id );
		$options     = get_option( $option_name );

		return ! empty( $options['purchase_code'] );
	}
}

if ( ! empty( $_GET['bs-clear-registered-product'] ) ) {
	if ( empty( $_GET['bs-reset-token'] ) ) {
		$link = add_query_arg(
			[
				'bs-reset-token' => wp_create_nonce( 'bs-reset-registered-' . $_GET['bs-clear-registered-product'] ),
			]
		);
		wp_die( sprintf( __( 'Are you sure to deregister product <strong>%1$s</strong> in this WordPress installation?<br/> This can disable auto update and other premium features of product.<br/><br/> <a href="%2$s" class="button" style="color: #fff;background-color: #0085ba;border-color:#0073aa #006799 #006799;">Yes, Deregister</a> &nbsp; <a href="%3$s" class="button">No</a> ', 'better-studio' ), $_GET['bs-clear-registered-product'], esc_url( $link ), esc_url( get_dashboard_url() ) ) );
	} else {
		if ( wp_verify_nonce( $_GET['bs-reset-token'], 'bs-reset-registered-' . $_GET['bs-clear-registered-product'] ) ) {
			bf_register_product_clear_info( $_GET['bs-clear-registered-product'] );

			wp_redirect(
				add_query_arg(
					[
						'bs-reset-token'              => false,
						'bs-clear-registered-product' => false,
					]
				)
			);
			exit;
		}
	}
}
