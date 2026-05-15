<?php

namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Class Creator
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
abstract class Creator {

	/**
	 * Store instance of this class.
	 *
	 * @var self $instance
	 */
	protected $instance;

	/**
	 * Retrieve instance of Module Contract.
	 *
	 * @since 1.0.0
	 * @return Module
	 */
	abstract public function factory_method(): Module;

	/**
	 * Running Module object operations.
	 * It contains some core business logic that relies on Module objects, returned by the factory method.
	 *
	 * @return bool true on success, false when otherwise.
	 */
	public function run(): bool {

		//Call the factory method to create a Module object.
		// Now, use the module.
		return $this->factory_method()->operation();
	}

	/**
	 * Refresh module operations.
	 * Can be fire clear cache data or restart module operations.
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	public function fresh(): bool {

		return $this->factory_method()->clear_data();
	}
}
