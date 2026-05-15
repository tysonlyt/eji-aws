<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Mounter
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Installable {

	/**
	 * Retrieve module identifier.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string;

	/**
	 * Retrieve module enable status.
	 *
	 * @since 1.0.0
	 * @return bool true on enable, false when module disable.
	 */
	public function active(): bool;
}
