<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Bootstrap
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Bootstrap {

	/**
	 * Initialize all components to implements new features on your site.
	 *
	 * @since 1.0.0
	 * @return bool true on success,false when failure.
	 */
	public function init_components(): bool;
}
