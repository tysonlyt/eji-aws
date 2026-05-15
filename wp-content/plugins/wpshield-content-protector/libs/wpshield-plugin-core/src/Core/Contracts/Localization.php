<?php


namespace WPShield\Core\PluginCore\Core\Contracts;

/**
 * Interface Localization
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Core\Contracts
 */
interface Localization {

	/**
	 * The data itself. The data should be multi-dimensional array.
	 *
	 * @since 1.0.0
	 * @return array [
	 * @type string $handle
	 * @type string $object
	 * @type string $l10n_data
	 * ]
	 */
	public function l10n(): array;
}
