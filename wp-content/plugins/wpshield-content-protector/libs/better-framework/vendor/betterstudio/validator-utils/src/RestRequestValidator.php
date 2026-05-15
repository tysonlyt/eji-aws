<?php

namespace BetterFrameworkPackage\Utils\Validator;

use BetterFrameworkPackage\Utils\ArrayUtil;
use \BetterFrameworkPackage\Core\{Rest,Module};
use WP_REST_Request;

/**
 * Form validator is module to validate $_POST and $_GET values.
 *
 * @since   1.0.0
 * @package BetterStudio\Utils\Validator
 * @format  Core Module
 */
class RestRequestValidator extends \BetterFrameworkPackage\Utils\Validator\Validator {

	/**
	 * Store the values collection.
	 *
	 * @since 1.0.0
	 * @var ArrayUtil\Collection
	 *
	 */
	protected $collection;

	/**
	 * RestRequestValidator constructor.
	 *
	 * @param WP_REST_Request $request
	 * @param array           $options
	 *
	 * @since 1.0.0
	 */
	public function __construct( WP_REST_Request $request, array $options = [] ) {

		if ( ! class_exists( \BetterFrameworkPackage\Core\Rest\RestHandler::class ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( "betterstudio/rest package not found!" );
		}

		$this->collection = \BetterFrameworkPackage\Utils\ArrayUtil\Collection::instance(
			$request->get_params()
		);

		$this->options( $options );
	}

	/**
	 * @inheritDoc
	 */
	public function value( string $id ) {

		return $this->collection->get( $id );
	}
}
