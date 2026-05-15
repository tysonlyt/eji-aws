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
 * Class BS_Theme_Pages_Base
 */
trait BF_Product_Pages_Base {

	public static $config = [];


	public function __construct() {

		self::init_config();
	}

	public function error( $error_message ) {

		// todo: print error message

		//phpcs:ignore
		printf( '<div class="update-nag">%s</div>', $error_message );
	}


	public static function init_config() {

		if ( ! self::$config ) {

			self::$config         = apply_filters( 'better-framework/product-pages/config', [] );
			self::$config['URI']  = BF_PRODUCT_PAGES_URI;
			self::$config['path'] = BF_PRODUCT_PAGES_PATH;
		}

	}

	public static function get_config() {

		self::init_config();

		return self::$config;
	}

	public static function get_product_info( $index, $default = false ) {

		if ( isset( self::$config[ $index ] ) ) {
			return self::$config[ $index ];
		}

		return $default;
	}

	/**
	 * handle api request
	 *
	 * @see \BetterFramework_Oculus::request
	 *
	 * @param string $action
	 * @param array  $data
	 * @param array  $auth
	 * @param bool   $use_wp_error
	 *
	 * @return array|bool|object|WP_Error
	 */
	protected function api_request( string $action, array $data = [], array $auth = [], bool $use_wp_error = true ) {

		if ( ! function_exists( 'bs_core_request' ) ) {
			return false;
		}

		// Backward Compatible
		if ( empty( $auth ) ) {
			$auth = $data['auth'] ?? [];
		}

		$results = bs_core_request( $action, compact( 'auth', 'data', 'use_wp_error' ) );

		if ( isset( $results->result ) && 'error' === $results->result ) {

			return new WP_Error( $results->{'error-code'}, $results->{'error-message'} );
		}

		return $results;
	} //api_request


	/**
	 * Throw exception when argument is WP_Error
	 *
	 * @param mixed $maybe_wp_error
	 *
	 * @throws BF_Exception
	 */
	protected static function throw_if_is_wp_error( $maybe_wp_error ) {

		$args = func_get_args();

		if ( is_wp_error( $maybe_wp_error ) ) {
			/**
			 * @var WP_Error $maybe_wp_error
			 */
			$message = '';

			foreach ( $maybe_wp_error->get_error_codes() as $code ) {
				$message .= "\n";
				$message .= $code . ': ';
				$message .= $maybe_wp_error->get_error_message( $code );
				$message .= "\n";
				//phpcs:ignore
				$message .= print_r( $maybe_wp_error->get_error_data( $code ), true );
				$message .= "\n";
			}

			$message .= " ----- \n";

			foreach ( array_slice( $args, 1 ) as $param ) {
				$message .= "\n";
				//phpcs:ignore
				$message .= print_r( $param, true );
			}

			throw new BF_Exception( $message, $maybe_wp_error->get_error_code() );
		}
	}

	/**
	 * Fetch the remote file content.
	 *
	 * @param string $url File url.
	 *
	 * @return string The remote file content
	 */
	protected function read_remote_file( $url ) {

		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {

			return '';
		}

		$request = wp_remote_get( $url, [ 'sslverify' => false ] );

		if ( ! $request || is_wp_error( $request ) ) {

			return '';
		}

		if ( wp_remote_retrieve_response_code( $request ) !== 200 ) {

			return '';
		}

		return wp_remote_retrieve_body( $request );
	}
}
