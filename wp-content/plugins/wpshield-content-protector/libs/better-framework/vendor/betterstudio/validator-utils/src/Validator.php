<?php

namespace BetterFrameworkPackage\Utils\Validator;

use BetterFrameworkPackage\Core\Module;

/**
 * Base class for validators module.
 *
 * @since   1.0.0
 * @package BetterStudio/Core/Validator
 * @format  Core Module
 */
abstract class Validator {

	/**
	 * Store the module settings config.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Store the WP_Error object.
	 *
	 * @var \WP_Error
	 */
	protected $errors;

	/**
	 * @param string $id
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	abstract public function value( string $id );

	/**
	 * Validator constructor.
	 *
	 * @param array $settings
	 *
	 * @since 1.0.0
	 */
	public function options( array $settings = [] ) {

		$this->settings = wp_parse_args( $settings, [
			'throw' => false,
		] );

		$this->errors = new \WP_Error();
	}

	/**
	 * @param array $config
	 *
	 * @since 1.0.0
	 * @throws Module\Exception
	 * @return bool true on success.
	 */
	public function validate( array $config ): bool {

		try {

			foreach ( $config as $id => $options ) {

				if ( ! is_array( $options ) ) {

					$options = $this->parse_all_settings( $options );
				}

				if ( ! $this->validate_item( $id, $options ) ) {

					throw new \BetterFrameworkPackage\Core\Module\Exception( __( 'Validation was failed.', 'betterstudio' ) );
				}
			}

			return true;

		} catch ( \BetterFrameworkPackage\Core\Module\Exception $e ) {

			if ( ! empty( $this->settings['throw'] ) ) {

				throw new \BetterFrameworkPackage\Core\Module\Exception(
					$e->getMessage(),
					$e->getCode()
				);
			}

			$this->errors->add( $e->getCode(), $e->getMessage() );

			return false;
		}
	}

	/**
	 * Check was last validation failed.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function failed() {

		return ! ! $this->errors->get_error_code();
	}

	/**
	 * Get the errors list in detail.
	 *
	 * @since 1.0.0
	 * @return \WP_Error
	 */
	public function errors(): \WP_Error {

		return $this->errors;
	}

	/**
	 * @param string $options
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function parse_all_settings( $options ): array {

		if ( ! is_array( $options ) ) {

			$options = explode( '|', $options );
		}

		$results = [];

		foreach ( $options as $option ) {

			if ( strstr( $option, ':' ) ) {

				[ $rule, $setting ] = explode( ':', $option, 2 );

			} else {

				$rule    = $option;
				$setting = '';
			}

			$results[ $rule ] = $this->parse_setting( $setting );
		}

		return $results;
	}

	/**
	 * @param string $setting
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function parse_setting( string $setting ): array {

		if ( empty( $setting ) ) {

			return [];
		}

		if ( ! strstr( $setting, '=' ) ) {

			return [ $setting ];
		}

		$setting = preg_replace( '/\s*,\s*/', '&', $setting );
		$setting = preg_replace( '/\s*=\s*/', '=', $setting );
		parse_str( $setting, $settings );

		return $settings;
	}

	/**
	 * @param string $id
	 * @param array  $settings
	 *
	 * @since 1.0.0
	 * @throws Module\Exception
	 * @return bool
	 */
	protected function validate_item( $id, array $settings ): bool {

		$value = $this->value( $id );

		foreach ( $settings as $rule_name => $setting ) {

			$rule = \BetterFrameworkPackage\Utils\Validator\Factory::rule( $rule_name );

			$rule->init( $setting, $id, $this );

			if ( ! $rule->check( $value ) ) {

				throw new \BetterFrameworkPackage\Core\Module\Exception(
					$rule->message( $id ),
					sprintf( '%s-%s', $id, $rule_name )
				);
			}
		}

		return true;
	}
}
