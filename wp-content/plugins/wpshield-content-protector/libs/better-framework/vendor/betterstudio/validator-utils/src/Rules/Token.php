<?php

namespace BetterFrameworkPackage\Utils\Validator\Rules;

class Token extends \BetterFrameworkPackage\Utils\Validator\Rules\Rule {

	/**
	 * @inheritDoc
	 */
	public function check( $value )
	: bool {

		return wp_verify_nonce( $value, $this->options['action'] );
	}

	/**
	 * @inheritDoc
	 */
	public function default_options()
	: array {

		return [
			'action' => 'Secure',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function unnamed_option()
	: string {

		return 'action';
	}


	/**
	 * @inheritDoc
	 */
	protected function error_message( $placeholders )
	: string {

		return __( 'Security Error.', 'betterstudio' );
	}
}
