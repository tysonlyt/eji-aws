<?php

namespace BetterFrameworkPackage\Utils\Validator\Rules;

use BetterFrameworkPackage\Core;
use BetterFrameworkPackage\Core\Exception;

//
use BetterFrameworkPackage\Utils\Validator;

/**
 * Base class fot the validator rule.
 *
 * @package BetterStudio\Utils\Validator\Rules
 */
abstract class Rule {

	/**
	 * Store the options array
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $options = [];

	/**
	 * Store the validator instance.
	 *
	 * @since 1.0.0
	 * @var Validator\Validator
	 */
	protected $validator;

	/**
	 * Store the rule id.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $id;

	/**
	 * @param mixed $value
	 *
	 * @since 1.0.0
	 * @throws Core\Exception
	 *
	 * @return bool true on success
	 */
	abstract public function check( $value ): bool;

	/**
	 * @since 1.0.0
	 * @return array
	 */
	abstract public function default_options(): array;

	/**
	 * @param array $placeholders {
	 *
	 * @type string $id The field unique ID.
	 * @type string $label The field name/label.
	 * }
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract protected function error_message( $placeholders ): string;


	/**
	 * Initialize rule before validation.
	 *
	 * @param array $options
	 * @param string $id
	 * @param Validator\Validator $validator
	 *
	 * @since 1.0.0
	 * @throws Exception
	 */
	public function init(
		array $options = [],
		$id = '',
		\BetterFrameworkPackage\Utils\Validator\Validator $validator = null
	) {

		if ( isset( $options[0] ) ) {

			if ( $name = $this->unnamed_option() ) {

				$options[ $name ] = $options[0];

				unset( $options[0] );

			} else {

				throw new \BetterFrameworkPackage\Core\Exception( _x( 'unnamed option is not available.', 'developer', 'betterstudio' ) );
			}
		}

		$this->options   = wp_parse_args(
			$options,
			$this->default_options()
		);
		$this->validator = $validator;
		$this->id        = $id;
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function name(): string {

		return $this->options['name'] ?? '';
	}


	public function unnamed_option(): string {

		return '';
	}


	/**
	 * @param string $id
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function message( $id ): string {

		$label = $this->options['label'] ?? $id;

		return $this->error_message( compact( 'id', 'label' ) );
	}

	/**
	 * Get the validator instance.
	 *
	 * @since 1.0.0
	 * @return Validator\Validator
	 */
	public function validator() {

		return $this->validator;
	}
}
