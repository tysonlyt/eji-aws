<?php

namespace BetterFrameworkPackage\Utils\Validator;

use BetterFrameworkPackage\Core\Module;
use BetterFrameworkPackage\Utils\Http;

/**
 * Form validator is module to validate $_POST and $_GET values.
 *
 * @since   1.0.0
 * @package BetterStudio\Utils\Validator
 * @format  Core Module
 */
class FormValidator extends \BetterFrameworkPackage\Utils\Validator\Validator {

	/**
	 * Store the HttpRequest instance.
	 *
	 * @since 1.0.0
	 * @var Http\HttpRequest
	 *
	 */
	protected $request;

	/**
	 * FormValidator constructor.
	 *
	 * @param array $settings
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $settings = [] ) {

		$this->options( $settings );

		if ( ! class_exists( \BetterFrameworkPackage\Utils\Http\HttpRequest::class ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( "betterstudio/http-utils package not found!" );
		}

		$this->request = new \BetterFrameworkPackage\Utils\Http\HttpRequest();
	}


	/**
	 * @inheritDoc
	 */
	public function value( string $id ) {

		if ( $this->request->is_post() ) {

			return $this->request->post( $id );

		} else {

			return $this->request->get( $id );
		}
	}
}
