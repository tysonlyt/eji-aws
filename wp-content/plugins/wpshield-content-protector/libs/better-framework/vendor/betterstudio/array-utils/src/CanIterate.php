<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException, Generator;

trait CanIterate {

	/**
	 * Map collection items.
	 *
	 * @param callable $callable
	 *
	 *
	 * @since  1.1.0 add use_key option.
	 *
	 * @string 1.0.3
	 * @throw  BadMethodCallException
	 * @return self
	 */
	public function iterate( callable $callable, $options = [] ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		if ( empty( $this->data ) ) {

			return $this;
		}

		$use_key = ! empty( $options['use_key'] );
		$keys    = array_keys( $this->data );
		$i       = 0;


		foreach ( $this->data as $key => $value ) {

			if ( $use_key ) {

				$item = $key;
				$next = $keys[ $i + 1 ] ?? null;
				$prev = $keys[ $i - 1 ] ?? null;

			} else {

				$item = $value;
				$next = isset( $keys[ $i + 1 ] ) ? $this->data[ $keys[ $i + 1 ] ] : null;
				$prev = isset( $keys[ $i - 1 ] ) ? $this->data[ $keys[ $i - 1 ] ] : null;
			}

			$callable( $item, $next, $prev, $this );

			$i ++;
		}

		return $this;
	}

	/**
	 * Map collection items.
	 *
	 * @param callable $callable
	 *
	 * @since  1.1.0 add use_key option.
	 *
	 * @string 1.0.3
	 * @throw  BadMethodCallException
	 * @return Generator
	 */
	public function iterate_generator( callable $callable, $options = [] ): Generator {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		if ( empty( $this->data ) ) {

			return $this;
		}

		$use_key = ! empty( $options['use_key'] );
		$keys    = array_keys( $this->data );
		$i       = 0;

		foreach ( $this->data as $key => $value ) {

			if ( $use_key ) {

				$item = $key;
				$next = $keys[ $i + 1 ] ?? null;
				$prev = $keys[ $i - 1 ] ?? null;

			} else {

				$item = $value;
				$next = isset( $keys[ $i + 1 ] ) ? $this->data[ $keys[ $i + 1 ] ] : null;
				$prev = isset( $keys[ $i - 1 ] ) ? $this->data[ $keys[ $i - 1 ] ] : null;

			}

			yield from $callable( $item, $next, $prev, $this );

			$i ++;
		}

		return $this;
	}
}
