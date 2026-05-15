<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException;
use InvalidArgumentException;

trait CanMergeItems {

	/**
	 * Merge new items with the collection.
	 *
	 * @string 1.1.0
	 * @return self new list instance
	 */
	public function merge( $new_items ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		if ( $new_items instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			$this->data = array_merge( $this->data, $new_items->to_array() );

		} else {

			if ( ! is_array( $new_items ) ) {

				throw new InvalidArgumentException( 'Merge argument should be an array or instance of CollectionBase.' );
			}

			$this->data = array_merge( $this->data, $new_items );
		}

		return $this;
	}

}
