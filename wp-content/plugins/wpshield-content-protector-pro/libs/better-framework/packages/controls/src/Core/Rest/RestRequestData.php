<?php

namespace BetterFrameworkPackage\Component\Control\Core\Rest;

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use lib functions
use function \BetterFrameworkPackage\Component\Control\{
	json_decode
};

// use wp core APIs
use WP_HTTP_Response;

class RestRequestData extends \BetterFrameworkPackage\Component\Control\Core\Rest\RestRequestBase {

	/**
	 * @throws Exception
	 *
	 * @return array
	 */
	public function response(): ?WP_HTTP_Response {

		$control = $this->control();
		$params  = $this->request->get_param( 'params' );

		if ( \is_string( $params ) ) {

			$params = \BetterFrameworkPackage\Component\Control\json_decode( $params );
		}

		return $control->ajax_handler()->handle_request(
			\is_array( $params ) ? $params : []
		);
	}


	/**
	 * @throws Exception
	 *
	 * @since 1.0.0
	 * @return ControlStandard\HaveAjaxHandler
	 */
	protected function control(): \BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler {
		if ( ! $control_type = $this->request->get_param( 'type' ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid request', 'rest-data-invalid-request' );
		}

		if ( ! $control = \BetterFrameworkPackage\Component\Control\Core\Rest\Utils::control( $control_type ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid control type given.', 'invalid-control' );
		}

		if ( ! $control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid control given.', 'control-error' );
		}

		return $control;
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function rest_end_point(): string {

		return 'control-data';
	}

	/**
	 * The validation rules.
	 *
	 * @since 1.0.0
	 * @return array{type: string}
	 */
	public function validation_rules(): array {

		return [
			'type' => 'required|bs-control: handle_ajax_request=1',
		];
	}
}
