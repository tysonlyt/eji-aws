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

// use wp core APIs
use WP_HTTP_Response;

class RestRequestProps extends \BetterFrameworkPackage\Component\Control\Core\Rest\RestRequestBase {

	/**
	 * @throws Exception
	 *
	 * @return WP_HTTP_Response|null
	 */
	public function response(): ?WP_HTTP_Response {

		$control = $this->control();
		$props   = $this->props();
		$token   = $this->token();

		if ( empty( $token ) || $control->secure_props_token( $props ) !== $token ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid token', 'invalid-token' );
		}

		$new_props = $control->secure_props( $props );

		if ( $control instanceof \BetterFrameworkPackage\Component\Standard\Control\WillModifyProps ) {

			$new_props = $control->modify_props( $new_props );
		}

		unset( $new_props['_lazy_loading'] );

		return new WP_HTTP_Response(
			[
				'data'  => $new_props,
				'extra' => $this->extra_params(),
			]
		);
	}


	protected function extra_params(): array {

		return array_diff_key(
			$this->request->get_params(),
			[
				'props' => '',
				'token' => '',
				'type'  => '',
			]
		);
	}

	protected function token(): string {

		if ( ! $token = $this->request->get_param( 'token' ) ) {

			$props = $this->request->get_param( 'props' );
			$token = $props['_token'] ?? $props['token'] ?? '';
		}

		return trim( $token );
	}

	protected function props(): array {

		$props = $this->request->get_param( 'props' );

		unset( $props['_token'] );

		return \is_array( $props ) ? $props : [];
	}

	/**
	 * @throws Exception
	 *
	 * @since 1.0.0
	 * @return ControlStandard\HaveSecureProps
	 */
	protected function control(): \BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps {

		if ( ! $control_type = $this->request->get_param( 'type' ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid request', 'rest-props-invalid-request' );
		}

		if ( ! $control = \BetterFrameworkPackage\Component\Control\Core\Rest\Utils::control( $control_type ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid control type given.', 'invalid-control' );
		}

		if ( ! $control instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid control given.', 'control-error' );
		}

		return $control;
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function rest_end_point(): string {

		return 'control-props';
	}

	/**
	 * The validation rules.
	 *
	 * @since 1.0.0
	 * @return array{type: string}
	 */
	public function validation_rules(): array {

		return [
			'type' => 'required|bs-control: modify_settings=1',
		];
	}
}
