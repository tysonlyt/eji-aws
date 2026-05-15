<?php

namespace WPShield\Plugin\ContentProtector\Components\ViewSourceProtector;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\ViewSourceProtector
 */
class Utils {

	/**
	 * Get list of hotKeys.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_disabled_shortcuts(): array {

		return [
			'ctrl_u',
			'cmd_option_u',
		];
	}
}
