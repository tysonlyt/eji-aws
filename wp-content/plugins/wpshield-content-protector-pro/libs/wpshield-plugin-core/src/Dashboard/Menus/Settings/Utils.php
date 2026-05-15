<?php


namespace WPShield\Core\PluginCore\Dashboard\Menus\Settings;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Dashboard\Menus\Settings
 */
class Utils {

	/**
	 * Retrieve the excerpt content of plugin.
	 *
	 * @param string $text
	 * @param int    $limit
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function the_excerpt_content( string $text, int $limit = 50 ): string {

		return sprintf( '%s...', substr( $text, 0, $limit ) );
	}
}
