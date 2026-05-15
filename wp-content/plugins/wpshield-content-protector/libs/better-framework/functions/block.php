<?php
//phpcs:disable
if ( ! function_exists( 'bf_is_rest_request' ) ) {

	/**
	 * @copyright https://gist.github.com/robskidmore/d43ee3a4e53652efb2c3d9e2a0d01b61
	 *
	 * @return bool
	 */
	function bf_is_rest_request() {
		static $bIsRest; // for cache

		if ( ! isset( $bIsRest ) ) {
			if ( function_exists( 'rest_url' ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
				$sRestUrlBase = get_rest_url( get_current_blog_id(), '/' );
				$sRestPath    = trim( parse_url( $sRestUrlBase, PHP_URL_PATH ), '/' );
				$sRequestPath = trim( $_SERVER['REQUEST_URI'], '/' );
				$bIsRest      = ( 0 === strpos( $sRequestPath, $sRestPath ) );
			} else {
				$bIsRest = false;
			}
		}

		return $bIsRest;
	}
}

if ( ! function_exists( 'bf_is_block_render_request' ) ) {

	/**
	 * Is gutenberg block render request?
	 *
	 * @return bool
	 */
	function bf_is_block_render_request(): bool {

		if ( isset( $_GET['rest_route'] ) && preg_match( '#/v\d+/block-renderer/better-studio/.*?$#i', $_GET['rest_route'] ) ) {

			return true;
		}

		if ( false !== stripos( $_SERVER['REQUEST_URI'], '/block-renderer/better-studio/' ) ) {

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'bf_server_side_block_render' ) ) {

	/**
	 * Get currently doing block serverside rendering type.
	 *
	 * @since 3.14.0
	 * @return bool
	 */
	function bf_server_side_block_render() {

		if ( ! bf_is_rest_request() ) {

			return '';
		}

		return isset( $_REQUEST['bf_render_context'] ) ? $_REQUEST['bf_render_context'] : '';
	}
}

if ( ! function_exists( 'bf_is_widget_block_rendering' ) ) {

	/**
	 * Is currently doing block widget serverside rendering?
	 *
	 * @since 3.14.0
	 * @return bool
	 */
	function bf_is_widget_block_rendering(): bool {

		return 'widgets' === bf_server_side_block_render();
	}
}

