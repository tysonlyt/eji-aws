<?php

namespace BetterFrameworkPackage\Component\Control\Core\Rest;

// use core modules
use \BetterFrameworkPackage\Core\{
	Rest,
	Module\Exception
};

// use utilities
use \BetterFrameworkPackage\Utils\{
	Validator
};

// use wp core APIs
use WP_HTTP_Response,
	WP_REST_Response,
	WP_REST_Request,
	WP_Error;

abstract class RestRequestBase extends \BetterFrameworkPackage\Core\Rest\RestHandler implements \BetterFrameworkPackage\Core\Rest\AllowBatch {

	/**
	 * Store WP_REST_Request instance.
	 *
	 * @var WP_REST_Request
	 * @since 1.0.0
	 */
	protected $request;

	/**
	 * Return the request validation rules.
	 *
	 * @see   Validator documentation for more.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function validation_rules(): array;

	/**
	 * @since 1.0.0
	 * @return WP_REST_Response|null
	 */
	abstract public function response(): ?WP_HTTP_Response;

	/**
	 * @param WP_REST_Request $request
	 *
	 * @throws Exception
	 * @return WP_REST_Response
	 */
	public function rest_handler( WP_REST_Request $request ): WP_REST_Response {

		$this->request = $request;
		$validation    = $this->validate();

		if ( is_wp_error( $validation ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( $validation->get_error_message(), $validation->get_error_code() );
		}

		if ( ! $response = $this->response() ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'cannot-handle-request' );
		}

		return \BetterFrameworkPackage\Component\Control\Core\Rest\Utils::map_response( $response );
	}

	/**
	 * @throws Exception
	 * @return bool|WP_Error
	 */
	protected function validate() {

		$validator = new \BetterFrameworkPackage\Utils\Validator\RestRequestValidator( $this->request );
		$is_valid  = $validator->validate(
			$this->validation_rules()
		);

		return ! $is_valid ? $validator->errors() : true;
	}

	/**
	 * @since 1.0.0
	 * @return bool
	 */
	public function rest_permission(): bool {

		return is_user_logged_in();
	}

	/**
	 * Rest http request method.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function methods(): string {

		return 'POST';
	}

	/**
	 * Allow batch request.
	 *
	 * @since 1.0.0
	 * @return array{v1: true}
	 */
	public function allow_batch(): array {

		return [
			'v1' => true,
		];
	}
}
