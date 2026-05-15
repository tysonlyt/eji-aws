<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface HaveDashboard
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface HaveDashboard {

	/**
	 * Dashboard preparation.
	 *
	 * @return bool true on success, false on failure!
	 */
	public function preparation(): bool;

	/**
	 * Retrieve product settings action link.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function settings_link(): string;
}
