<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException;

trait CanGroupItems {

	/**
	 * Group collection items.
	 *
	 * @param callable $callable
	 *
	 * @string 1.0.3
	 * @throw  BadMethodCallException
	 * @return self
	 */
	public function group( callable $callable ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		$grouped_items = [];

		$this->data && array_walk( $this->data, static function ( $value, $key ) use ( $callable, &$grouped_items ) {

			$group = $callable( $value, $key );

			$grouped_items[ $group ][ $key ] = $value;
		} );

		$this->data = $grouped_items;

		return $this;
	}

	/**
	 * Group collection items step by step.
	 *
	 * @param callable $callable
	 *
	 * @string 1.0.3
	 * @throw  BadMethodCallException
	 */
	public function group_cascade( callable $callable ) {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		$last_id       = '';
		$indicator     = - 1;
		$grouped_items = [];

		$this->data && array_walk( $this->data, static function ( $value, $key ) use ( $callable, &$grouped_items, &$indicator, &$last_id ) {

			$current_id = $callable( $value, $key );

			if ( $last_id !== $current_id ) {

				$indicator ++;
				$last_id = $current_id;
			}

			$grouped_items[ $indicator ][ $key ] = $value;
		} );

		$this->data = $grouped_items;
	}
}
