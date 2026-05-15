<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

/**
 * Simple Collection Storage with reference data to reduce memory usage.
 *
 * @since   1.0.0
 * @package BetterStudio/Utils/ArrayUtil
 */
class CollectionRef extends \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase {

	/**
	 * Load the array into the collection.
	 *
	 * @param array &$data
	 */
	public function _load( array &$data ): void {

		$this->data = &$data;
	}


	/**
	 * Get fresh instance of the collection.
	 *
	 * @param array &$data
	 *
	 * @since 1.0.0
	 * @return static
	 */
	public static function instance( &$data = array() ): self {

		$instance = new static();

		$instance->_load( $data );

		return $instance;
	}
}
