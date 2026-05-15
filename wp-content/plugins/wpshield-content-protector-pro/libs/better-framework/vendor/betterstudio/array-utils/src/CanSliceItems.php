<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException;

trait CanSliceItems {

	/**
	 * Extract a slice of an array, given a list of keys.
	 *
	 * @string 1.1.0
	 * @throw  BadMethodCallException
	 * @return self new list instance
	 */
	public function slice_assoc( array $keys ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		$slice = array();

		foreach ( $keys as $key ) {

			$data = $this->get( $key );

			if ( isset( $data ) ) {

				$slice[ $key ] = $data;
			}
		}

		$instance       = new self();
		$instance->data = $slice;

		return $instance;
	}
}
