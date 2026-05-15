<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * interface HaveExtension.
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface HaveExtension {

	/**
	 * Loading extensions.
	 *
	 * @since 1.0.0
	 * @return bool true on success,false when otherwise.
	 */
	public function load_extensions(): bool;
}
