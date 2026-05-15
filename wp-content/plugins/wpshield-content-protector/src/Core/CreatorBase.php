<?php


namespace WPShield\Plugin\ContentProtector\Core;

use WPShield\Plugin\ContentProtector\ContentProtectorSetup as Plugin;

/**
 * Class CreatorBase
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Core
 */
trait CreatorBase {

	/**
	 * Store instance of main module.
	 *
	 * @var Plugin $plugin
	 */
	protected $plugin;

	/**
	 * @return Plugin
	 */
	public function get_plugin(): Plugin {

		return $this->plugin;
	}

	/**
	 * @param Plugin $plugin
	 */
	public function set_plugin( Plugin $plugin ): void {

		$this->plugin = $plugin;
	}
}
