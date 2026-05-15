<?php

namespace WPShield\Plugin\ContentProtector\Components\TextCopyProtector;

/**
 * Class Utilities
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\TextCopyProtector
 */
class Utilities {

	/**
	 * Get list of disabled shortcuts.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_disabled_shortcuts(): array {

		return [
			'ctrl_a',
			'ctrl_c',
			'ctrl_x',
			'ctrl_v',
			'cmd_a',
			'cmd_c',
			'cmd_x',
			'cmd_v',
		];
	}
}
