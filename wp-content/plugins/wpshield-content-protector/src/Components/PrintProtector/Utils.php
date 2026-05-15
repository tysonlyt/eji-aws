<?php

namespace WPShield\Plugin\ContentProtector\Components\PrintProtector;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\PrintProtector
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
			'ctrl_p',
			'cmd_option_p',
			'cmd_p',
		];
	}
}
