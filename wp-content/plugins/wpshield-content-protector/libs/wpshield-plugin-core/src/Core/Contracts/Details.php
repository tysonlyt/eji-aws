<?php

namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Details
 *
 * @since 1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Details {

	/**
	 * Retrieve The full path and filename of the file. If used inside an include, the name of the included main plugin file is returned.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function file(): string;

	/**
	 * Retrieve plugin released version number.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function version(): string;

	/**
	 * Retrieve product name.
	 *
	 * @return string product name as string.
	 */
	public function product_name(): string;

	/**
	 * Retrieve product identifier.
	 *
	 * @return string product identifier as string.
	 */
	public function product_id(): string;
}
