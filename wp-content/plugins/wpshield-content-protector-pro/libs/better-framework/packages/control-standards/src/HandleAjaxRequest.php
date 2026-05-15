<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use WP APIs
use WP_HTTP_Response;

interface HandleAjaxRequest {

	/**
	 * @param array $request
	 *
	 * @throws Exception
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response;

	/**
	 * TODO: Add validator
	 */

	// public function validate();
}
