<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

// use WP APIs
use WP_HTTP_Response;

abstract class AjaxHandlerBase implements \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

	/**
	 * Create response object.
	 *
	 * @param array $data
	 * @param bool  $wrap_items
	 *
	 * @since 1.0.0
	 * @return WP_HTTP_Response
	 */
	public function response( array $data = [], bool $wrap_items = true ): WP_HTTP_Response {

		$response = new WP_HTTP_Response();

		$response->set_data( $wrap_items ? compact( 'data' ) : $data );

		return $response;
	}
}
