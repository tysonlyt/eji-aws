<?php

namespace BetterFrameworkPackage\Utils\Validator\Rules;

class Password extends \BetterFrameworkPackage\Utils\Validator\Rules\Rule {

	/**
	 * Store the message.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $_message;

	/**
	 * @inheritDoc
	 */
	public function check( $value ): bool {

		if ( $value === '' && ! $this->options['required'] ) {

			return true;
		}

		if ( ! $this->_validate( $value ) ) {

			return false;
		}

		if ( $this->options['double_check'] && ! $this->double_check( $value ) ) {

			$this->_message = sprintf(
				__( '{label} and the confirmation is not the same.', 'betterstudio' ),
				number_format_i18n( $this->options['length'] )
			);

			return false;
		}

		return true;
	}


	/**
	 * Validate the given password.
	 *
	 * @param string $value
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function _validate( $value ): bool {

		if ( mb_strlen( $value ) < $this->options['length'] ) {

			$this->_message = sprintf(
				__( '{label} length should be greater than %s.', 'betterstudio' ),
				number_format_i18n( $this->options['length'] )
			);

			return false;
		}

		return true;
	}

	/**
	 * Check the given password with the confirmation field.
	 *
	 * @param string $value
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function double_check( $value ): bool {

		$pass2_field = $this->id . '2';
		$pass2_value = $this->validator()->value( $pass2_field );

		if ( ! $this->_validate( $pass2_value ) ) {

			return false;
		}

		if ( $pass2_value !== $value ) {

			$this->_message = sprintf(
				__( '{label} and the confirmation is not the same.', 'betterstudio' ),
				number_format_i18n( $this->options['length'] )
			);

			return false;
		}

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	public function default_options(): array {

		return [
			'required'     => true,
			'double_check' => true,
			'length'       => 6,
		];
	}

	/**
	 * @inheritDoc
	 */
	protected function error_message( $placeholders ): string {

		return str_replace( '{label}', $placeholders['label'], $this->_message );
	}
}
