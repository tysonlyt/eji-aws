<?php

namespace WPShield\Plugin\ContentProtector\Components\DeveloperToolsProtector;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\DeveloperToolsProtector
 */
class Utils {

	/**
	 * Get list of hotKeys.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_disabled_shortcuts(): array {

		return apply_filters( 'wpshield/content-protector/components/dev-tools/hot-keys',
			[
				//Windows & Linux OS hotKeys
				'{',
				'ctrl_{',
				'ctrl_shift_c',
				'ctrl_shift_i',
				'ctrl_shift_j',
				//Mac OS hotKeys
				'cmd_shift_4',
				'cmd_shift_3',
				'cmd_alt_i',
				'cmd_alt_u',
				'cmd_shift_c',
				'cmd_ctrl_shift_3',
				'cmd_shift_4_space',
				'cmd_option_i',
				'cmd_option_j',
				'cmd_option_c',
			]
		);
	}
}
