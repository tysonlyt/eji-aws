<?php

namespace BetterFrameworkPackage\Component\Control\Core\Rest;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

// use wp core APIs
use WP_HTTP_Response,
	WP_REST_Response;

class Utils {

	/**
	 * @since 1.0.0
	 * @return ControlStandard\StandardControl|null
	 */
	public static function control( string $control_type ): ?\BetterFrameworkPackage\Component\Standard\Control\StandardControl {

		return \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory(
			$control_type
		);
	}

	/**
	 * @param WP_HTTP_Response $request
	 *
	 * @return WP_REST_Response
	 */
	public static function map_response( WP_HTTP_Response $request ): WP_REST_Response {

		$result = new WP_REST_Response();
		$result->set_headers( $request->get_headers() );
		$result->set_status( $request->get_status() );
		$result->set_data( $request->get_data() );

		return $result;
	}
}
