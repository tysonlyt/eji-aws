<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Deactivated
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Deactivated {

	/**
	 * Retrieve deactivation hook is registered?
	 *
	 * @since 1.0.0
	 * @return bool true on success,false when failure.
	 */
	public function deactivation_hook(): bool;
}
