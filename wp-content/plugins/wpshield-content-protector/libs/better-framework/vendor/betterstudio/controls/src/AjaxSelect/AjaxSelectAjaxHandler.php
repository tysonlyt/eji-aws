<?php

namespace BetterFrameworkPackage\Component\Control\AjaxSelect;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use wp APIs
use WP_HTTP_Response;

class AjaxSelectAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	/**
	 * @var array
	 * @since 1.0.0
	 */
	protected $params;

	/**
	 * Handle the control ajax request.
	 *
	 * @since 1.0.0
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response {

		$this->params = $params;
		$data         = $this->validate();

		return $this->response(
			[
				'data' => \call_user_func_array( $data['callback'], $data['params'] ),
			]
		);
	}

	/**
	 * @throws Exception
	 * @since 1.0.0
	 * @return array{callback: callable, params: mixed[]}
	 */
	protected function validate(): array {

		$token    = $this->params['token'] ?? '';
		$callback = $this->params['callback'] ?? '';

		if ( empty( $token ) || empty( $callback ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid request', 'ajax-select-invalid-request' );
		}

		if ( ! \BetterFrameworkPackage\Component\Control\AjaxSelect\AjaxSelectControl::token( $callback ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid token', 'invalid-token' );
		}

		if ( ! \is_callable( $callback ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid callback', 'invalid-callback' );
		}

		$params = [
			$this->params['key'] ?? '',
			$this->params['exclude'] ?? '',
			$this->params['include'] ?? '',
		];

		return compact( 'callback', 'params' );
	}
}
