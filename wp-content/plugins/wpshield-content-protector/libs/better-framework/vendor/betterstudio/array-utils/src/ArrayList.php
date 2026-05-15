<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

/**
 * Simple Changeable Collection Storage.
 *
 * @since   1.0.0
 * @package BetterStudio/Utils/ArrayUtil
 */
class ArrayList extends \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase {

	/**
	 * Load the data into the collection.
	 *
	 * @param array|CollectionBase $data
	 */
	public function _load( $data ): void {

		$this->data = $data;
	}


	/**
	 * Get fresh object of the collection.
	 *
	 * @param array $data
	 *
	 * @since 1.0.0
	 * @return static
	 */
	public static function instance( $data = array() ): self {

		$instance = new static();

		$data && $instance->_load( $data );

		return $instance;
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
	 * @param mixed $offset
	 * @param mixed $value
	 *
	 * @since 1.0.2
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ): void {

		$this->data[ $offset ] = $value;
	}

	/**
	 * @param mixed $offset
	 *
	 * @since 1.0.2
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ): void {

		unset( $this->data[ $offset ] );
	}

}
