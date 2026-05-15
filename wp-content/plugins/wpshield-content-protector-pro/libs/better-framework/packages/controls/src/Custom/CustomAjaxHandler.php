<?php

namespace BetterFrameworkPackage\Component\Control\Custom;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use lib functions
use function \BetterFrameworkPackage\Component\Control\{
	json_decode
};

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use wp APIs
use WP_HTTP_Response, WP_Error;

class CustomAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	/**
	 * Handle the control ajax request.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response {

		$filtered_params = $this->validate( $params );

		if ( is_wp_error( $filtered_params ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( $filtered_params->get_error_message(), $filtered_params->get_error_code() );
		}

		return $this->response(
			[
				'raw' => $this->fire( $filtered_params ),
			]
		);
	}


	/**
	 * @param array $params
	 *
	 * @since 1.0.0
	 * @return array|WP_Error array on success or WP_Error when an error occurs.
	 */
	protected function validate( array $params ) {

		// check required params
		if ( empty( $params['callbackArgs'] ) || empty( $params['callback'] ) || empty( $params['token'] ) ) {

			return new WP_Error( 'custom-control-invalid-request', 'Invalid arguments given' );
		}

		if ( ! \is_callable( $params['callback'] ) ) {

			return new WP_Error( 'invalid-request', sprintf( '%s callback is not callable', $params['callback'] ) );
		}

		// validate security token

		$callback_args = \BetterFrameworkPackage\Component\Control\json_decode( $params['callbackArgs'] );
		$valid_token   = wp_create_nonce(
			md5( $params['callback'] . serialize( $callback_args ) )
		);

		if ( $valid_token !== $params['token'] ) {

			return new WP_Error( 'invalid-token', 'Invalid security token given' );
		}

		// sanitize & prepare values

		$value = '';

		if ( isset( $params['_value'] ) ) {

			$value = \BetterFrameworkPackage\Component\Control\json_decode( $params['_value'] );

			if ( json_last_error() !== JSON_ERROR_NONE ) {

				$value = $params['_value'];
			}

			unset( $params['_value'] );
		}

		$params['value']                                    = $value;
				$callback_args[0]['field_options']['value'] = $params['value'];
		$params['callback_args']                            = $callback_args;

		return $params;
	}

	/**
	 * @param array $params
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function fire( array $params ): string {

		ob_start();

		$return = \call_user_func_array( $params['callback'], $params['callback_args'] );

		$buffer = ob_get_clean();

		if ( ! $buffer && $return ) {
			return $return;
		}

		return $buffer;
	}
}
