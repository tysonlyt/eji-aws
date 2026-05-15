<?php

if ( ! function_exists( 'cpp_get_current_url' ) ) {

	/**
	 * @return string
	 */
	function cpp_get_current_url(): string {

		global $wp;

		if ( ! isset( $_SERVER['HTTPS'], $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) {

			return home_url( add_query_arg( [], $wp->request ) );
		}

		//phpcs:disable
		$protocol = $_SERVER['HTTPS'];

		$protocol = 'on' === $protocol ? 'https' : 'http';

		return sprintf( '%s://%s%s', $protocol, $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] );
		//phpcs:enable
	}
}
