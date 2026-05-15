<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Activate
 *
 * @since 1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Activate {

	/**
	 * Retrieve activation hook is registered?
	 *
	 * @since 1.0.0
	 * @return bool true on success,false when failure.
	 */
	public function activation_hook():bool;
}
