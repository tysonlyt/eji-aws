<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

/**
 * Simple Collection Storage.
 *
 * @since   1.0.0
 * @package BetterStudio/Utils/ArrayUtil
 */
class Collection extends \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase {

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
}
