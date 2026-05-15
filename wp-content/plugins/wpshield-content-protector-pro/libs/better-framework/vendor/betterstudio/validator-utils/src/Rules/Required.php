<?php

namespace BetterFrameworkPackage\Utils\Validator\Rules;

class Required extends \BetterFrameworkPackage\Utils\Validator\Rules\Rule {

	/**
	 * @inheritDoc
	 */
	public function default_options()
	: array {

		return [
			'trim' => true,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function check( $value )
	: bool {

		if ( ! empty( $this->options['trim'] ) ) {

			$this->trim( $value );
		}

		if ( empty( $value ) ) {
			return false;
		}

		return true;
	}


	/**
	 * @param string|array $value
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function trim( &$value ) {

		if ( is_array( $value ) ) {

			$value = array_filter(
				array_map( 'trim', $value )
			);

		} else {

			$value = trim( $value );
		}

		return true;
	}

	/**
	 * @inheritDoc
	 */
	protected function error_message( $placeholders )
	: string {

		return sprintf(
			_x( '%s is required.', '%s is the field name.', 'betterstudio' ),
			$placeholders['label']
		);
	}
}
