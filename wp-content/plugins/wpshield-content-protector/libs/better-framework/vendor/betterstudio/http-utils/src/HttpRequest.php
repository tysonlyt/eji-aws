<?php

namespace BetterFrameworkPackage\Utils\Http;

use BetterFrameworkPackage\Utils\ArrayUtil;

class HttpRequest {

	/**
	 * @since 1.0.0
	 * @var ArrayUtil\CollectionRef
	 */
	protected $post;

	/**
	 * @since 1.0.0
	 * @var ArrayUtil\CollectionRef
	 */
	protected $get;

	/**
	 * HttpRequest constructor.
	 */
	public function __construct() {

		$this->post = \BetterFrameworkPackage\Utils\ArrayUtil\CollectionRef::instance( $_POST );
		$this->get  = \BetterFrameworkPackage\Utils\ArrayUtil\CollectionRef::instance( $_GET );
	}

	/**
	 * Is request method post?
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_post(): bool {

		return 'POST' === $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Get a posted value.
	 *
	 * @param string $index
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function post( $index ) {

		return $this->post->get( $index );
	}

	/**
	 * Get a query string.
	 *
	 * @param string $index
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get( $index ) {

		return $this->get->get( $index );
	}

	/**
	 * @param string $index
	 *
	 * @since 1.0.4
	 * @return mixed
	 */
	public function param( string $index ) {

		return $this->is_post() ? $this->post( $index ) : $this->get( $index );
	}
}
