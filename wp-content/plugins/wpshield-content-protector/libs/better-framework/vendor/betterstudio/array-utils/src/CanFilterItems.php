<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException;

trait CanFilterItems {

	/**
	 * Filter collection items.
	 *
	 * @param callable $callable
	 *
	 * @string 1.0.3
	 * @throw  BadMethodCallException
	 * @return self
	 */
	public function filter( callable $callable ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		$this->data = array_filter( $this->data ?? [], $callable, ARRAY_FILTER_USE_BOTH );

		return $this;
	}
}
