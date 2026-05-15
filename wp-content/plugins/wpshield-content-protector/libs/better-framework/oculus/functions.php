<?php

if ( ! function_exists( 'bs_core_request' ) ) {

	/**
	 * Connect Better Studio API and Retrieve Data From Server.
	 *
	 * @param string $action       {@see handle_request}
	 * @param array  $args         {
	 *
	 * @type array   $auth         authentication info {@see $auth}
	 * @type array   $data         array of data to send
	 * @type string  $group        API group name
	 * @type bool    $use_wp_error use wp_error object on failure or always return false
	 * }
	 *
	 * @since 1.3.0
	 * @return bool|WP_Error|array|object bool|WP_Error on failure.
	 */
	function bs_core_request( string $action, array $args = [] ) {

		return BetterFramework_Oculus::request( $action, $args );
	}
}
