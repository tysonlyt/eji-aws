<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Module {

	/**
	 * Running operations of module.
	 * Running when fire up base hook.
	 *
	 * @return bool true on success, false when otherwise.
	 */
	public function operation(): bool;

	/**
	 * Running clear data operation of module.
	 * Running when fire up base hook.
	 *
	 * @return bool true on success, false when otherwise.
	 */
	public function clear_data(): bool;
}
