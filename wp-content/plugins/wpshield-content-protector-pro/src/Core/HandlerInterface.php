<?php


namespace WPShield\Plugin\ContentProtectorPro\Core;

/**
 * Interface HandlerInterface
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core
 */
interface HandlerInterface {

	/**
	 * Retrieve handler identifier.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string;

	/**
	 * Filtering protector features.
	 *
	 * @param array $features
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_features( array $features ): array;
}
