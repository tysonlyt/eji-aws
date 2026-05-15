<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException;

trait CanMapItems {

	/**
	 * Map collection items.
	 *
	 * @param callable $callable
	 *
	 * @string 1.0.3
	 * @throw  BadMethodCallException
	 * @return self
	 */
	public function map( callable $callable, $options = [] ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		if(! $this->data) {

			return $this;
		}

		$use_key = ! empty( $options['use_key'] );

		foreach ( $this->data as $key => $value ) {

			$this->data[ $key ] = $callable( $use_key ? $key : $value );
		}

		return $this;
	}
}
