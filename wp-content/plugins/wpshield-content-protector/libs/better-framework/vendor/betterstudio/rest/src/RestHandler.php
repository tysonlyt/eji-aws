<?php

namespace BetterFrameworkPackage\Core\Rest;

use BetterFrameworkPackage\Core\Module;

/**
 * Rest Endpoint Handler Base Class.
 *
 * @since   1.0.0
 * @package BetterStudio\Core\Rest
 */
abstract class RestHandler {

	/**
	 * Implements singleton instance method
	 *
	 * @since 1.0.0
	 */
	use \BetterFrameworkPackage\Core\Module\Singleton;

	/**
	 * Rest route callback handler.
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @since 1.0.0
	 * @throws \Exception
	 * @return \WP_REST_Response
	 */
	abstract public function rest_handler( \WP_REST_Request $request ): \WP_REST_Response;

	/**
	 * Check user permission.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	abstract public function rest_permission(): bool;

	/**
	 * Get endpoint name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function rest_end_point(): string;


	/**
	 * The rest endpoint method.
	 *
	 * @since 1.0.0
	 * @return string GET or POST
	 */
	abstract public function methods(): string;

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @since 1.0.0
	 *
	 * @return \WP_REST_Response
	 */
	final public function rest_response( \WP_REST_Request $request ) {

		try {

			return $this->rest_handler( $request );

		} catch ( \Exception $e ) {

			if ( $e instanceof \BetterFrameworkPackage\Core\Module\Exception ) {

				return new \WP_REST_Response( [

					'success' => false,
					'code'    => $e->getCode(),
					'message' => $e->getMessage(),
				] );
			}

			return new \WP_REST_Response( [

				'success' => false,
				'code'    => $e->getMessage(),
				'message' => $e->getTraceAsString(),
			] );
		}
	}

	/**
	 * The rest url.
	 *
	 * @since 1.0.3
	 * @return string
	 */
	public static function url(): string {

		return rest_url( trailingslashit( \BetterFrameworkPackage\Core\Rest\RestSetup::NAMESPACE ) . static::instance()->rest_end_point() );
	}
}
