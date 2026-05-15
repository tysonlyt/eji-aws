<?php

namespace BetterFrameworkPackage\Component\Control\AjaxAction;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};
use WP_HTTP_Response;

class AjaxActionAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	public function handle_request( array $params ): WP_HTTP_Response {

		$callback = $params['callback'] ?? '';
		$args     = $params['args'] ?? [];

		// Security issue fix
		if ( empty( $params['call_token'] ) || $this->token( $callback ) !== $params['call_token'] ) {

			return $this->response(
				[
					'status' => 'error',
					'msg'    => __( 'the security token is not valid!', 'better-studio' ),
				]
			);
		}

		if ( empty( $callback ) || ! \is_callable( $callback ) ) {

			return $this->response(
				[
					'status' => 'error',
					'msg'    => __( 'An error occurred while doing action.', 'better-studio' ),
				]
			);
		}

		if ( \is_array( $args ) ) {

			$response = \call_user_func_array( $callback, $args );

		} else {

			$response = $callback( $args );
		}

		if ( ! \is_array( $response ) ) {

			$response = [
				'status' => 'error',
				'msg'    => __( 'An error occurred while doing action.', 'better-studio' ),
			];
		}

		return $this->response( $response );
	}

	/**
	 * @param string $callback
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function token( string $callback ): string {

		return wp_create_nonce( sprintf( 'bf-custom-callback:%s', $callback ) );
	}
}
