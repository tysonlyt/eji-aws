<?php

namespace BetterFrameworkPackage\Utils\Validator\Rules;

use BetterFrameworkPackage\Core\Module\Exception;

class Min extends \BetterFrameworkPackage\Utils\Validator\Rules\Rule {


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

		if ( empty( $this->options['length'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( _x( 'Max length is not defined.', 'developer', 'betterstudio' ), 'empty-length' );
		}

		if ( ! empty( $this->options['trim'] ) ) {

			$value = trim( $value );
			$value = preg_replace( '/\s+/', ' ', $value );
		}

		return mb_strlen( $value ) >= $this->options['length'];
	}

	/**
	 * @inheritDoc
	 */
	public function unnamed_option()
	: string {

		return 'length';
	}

	/**
	 * @inheritDoc
	 */
	protected function error_message( $placeholders )
	: string {

		return sprintf(
			_x( 'Minimum length of %s is %s.', 'The %s is the field name and the second one is max allowed length.', 'betterstudio' ),
			$placeholders['label'],
			number_format_i18n( $this->options['length'] )
		);
	}
}
