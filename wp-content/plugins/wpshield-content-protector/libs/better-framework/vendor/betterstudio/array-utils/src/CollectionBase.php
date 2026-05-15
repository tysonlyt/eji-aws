<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use Psr\{
	Container as Psr11,
};

//
use ErrorException;
use ArrayIterator;

/**
 * Collection Base class.
 *
 * @since   1.0.0
 * @package BetterStudio/Utils/ArrayUtil
 */
abstract class CollectionBase implements \Countable, \ArrayAccess, \IteratorAggregate, Psr11\ContainerInterface {

	/**
	 * Load the array into the collection.
	 *
	 * @since 1.0.0
	 * @var array
	 *
	 */
	protected $data = [];

	/**
	 * Get a value by index.
	 *
	 * @param string $index
	 *
	 * @since 1.0.0
	 * @return mixed null on failure.
	 */
	public function get( string $index ) {

		if ( $index === '' ) {

			return $this->data;
		}

		if ( isset( $this->data[ $index ] ) || strpos( $index, '.' ) === false ) {

			return $this->data[ $index ] ?? null;
		}

		$indexes = explode( '.', $index );
		$ref     = &$this->data;

		foreach ( $indexes as $c_index ) {

			if ( isset( $ref[ $c_index ] ) ) {

				$ref = &$ref[ $c_index ];
			} else {

				return null;
			}
		}

		return $ref;
	}

	/**
	 * Store value in given index.
	 *
	 * @param string $index
	 * @param mixed  $value
	 *
	 * @since 1.0.0
	 */
	public function set( string $index, $value ): void {

		if ( $index === '' ) {

			$this->data = $value;

			return;
		}

		if ( strpos( $index, '.' ) === false ) {

			$this->data[ $index ] = $value;

			return;
		}

		$indexes = explode( '.', $index );
		$ref     = &$this->data;

		foreach ( $indexes as $c_index ) {

			if ( ! isset( $ref[ $c_index ] ) ) {

				$ref[ $c_index ] = array();
			}

			$ref = &$ref[ $c_index ];
		}

		$ref = $value;
	}

	public function push( string $index, $value ): void {

		$array   = $this->get( $index ) ?? [];
		$array[] = $value;

		$this->set( $index, $array );
	}


	/**
	 * @param string|int|float $key
	 * @param string|int|float $new_key
	 * @param mixed            $new_value
	 *
	 * @return void
	 */
	public function insert_before( $key, $new_key, $new_value ): bool {

		if ( ! $this->has( $key ) ) {

			return false;
		}

		$namespace = \BetterFrameworkPackage\Utils\ArrayUtil\Util::parent_index( $key );
		$base_key  = \BetterFrameworkPackage\Utils\ArrayUtil\Util::base_index( $key );
		$new       = [];

		foreach ( $this->get( $namespace ) as $k => $value ) {

			if ( $k === $base_key ) {

				if ( isset( $new_key ) ) {

					$new[ $new_key ] = $new_value;

				} else {

					$new[] = $new_value;
				}
			}

			$new[ $k ] = $value;
		}

		$this->set( $namespace, $new );

		return true;
	}

	/**
	 * @param string|int|float $key
	 * @param string|int|float $new_key
	 * @param mixed            $new_value
	 *
	 * @return void
	 */
	public function insert_after( $key, $new_key, $new_value ): bool {

		if ( ! $this->has( $key ) ) {

			return false;
		}

		$namespace = \BetterFrameworkPackage\Utils\ArrayUtil\Util::parent_index( $key );
		$base_key  = \BetterFrameworkPackage\Utils\ArrayUtil\Util::base_index( $key );
		$new       = [];

		foreach ( $this->get( $namespace ) as $k => $value ) {

			$new[ $k ] = $value;

			if ( $k === $base_key ) {

				if ( isset( $new_key ) ) {

					$new[ $new_key ] = $new_value;
				} else {

					$new[] = $new_value;
				}
			}
		}

		$this->set( $namespace, $new );

		return true;
	}

	/**
	 * Load default values.
	 *
	 * @param iterable $defaults
	 *
	 * @since 1.1.0
	 */
	public function defaults( iterable $defaults ): void {

		foreach ( $defaults as $key => $default_value ) {

			if ( is_null( $this->get( $key ) ) ) {

				$this->set( $key, $default_value );
			}
		}
	}

	/**
	 * Remove a key.
	 *
	 * @param int|string $index
	 *
	 * @since     1.1.0
	 * @copyright https://stackoverflow.com/a/46466325
	 */
	public function remove( $index ): void {

		$keys    = explode( '.', $index );
		$pointer = &$this->data;
		//
		while ( ( $current = array_shift( $keys ) ) && ( count( $keys ) > 0 ) ) {
			// if some key is missing all the sub-keys will be already unset
			if ( ! array_key_exists( $current, $pointer ) ) {
				// is already unset somewhere along the way
				return;
			}
			// set pointer to new, deeper level
			// called for all but last key
			$pointer = &$pointer[ $current ];
		}
		// handles empty input string
		if ( $current ) {
			// we finally unset what we wanted
			unset( $pointer[ $current ] );
		}
	}


	/**
	 * Get all stored data as an array.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function to_array( $deep = true ): array {

		if ( $this->data instanceof self ) {

			return $this->data->to_array();
		}

		return $deep ? array_map( [ \BetterFrameworkPackage\Utils\ArrayUtil\Util::class, 'to_array' ], $this->data ?? [] ) : ( $this->data ?? [] );
	}

	/**
	 * Count items.
	 *
	 * @since 1.0.2
	 * @return int
	 */
	public function count(): int {

		return count( $this->to_array() ) ?? 0;
	}

	/**
	 * an alias for count method.
	 *
	 * @since 1.2.0
	 * @return int
	 */
	public function size(): int {

		return $this->count();
	}

	public function is_empty(): bool {

		return empty( $this->data );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.2
	 * @return bool
	 */
	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ): bool {

		if ( strpos( $offset, '.' ) === false ) {

			return isset( $this->data[ $offset ] );
		}

		return $this->get( $offset ) !== null;
	}

	/**
	 * @param string $id
	 *
	 * @since 1.2.0
	 * @return bool
	 */
	public function has( string $id ): bool {

		return $this->offsetExists( $id );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.2
	 * @return mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {

		return $this->get( $offset );
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @throws ErrorException
	 * @since 1.0.2
	 */
	public function offsetSet( $offset, $value ): void {

		throw new ErrorException( __CLASS__ . ' is read only' );
	}

	/**
	 * @param mixed $offset
	 *
	 * @throws ErrorException
	 * @since 1.0.2
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ): void {

		throw new ErrorException( __CLASS__ . ' is read only' );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.2
	 * @return ArrayIterator
	 */
	#[\ReturnTypeWillChange]
	public function getIterator(): \Traversable {

		return new ArrayIterator( $this->data ?? [] );
	}
}
