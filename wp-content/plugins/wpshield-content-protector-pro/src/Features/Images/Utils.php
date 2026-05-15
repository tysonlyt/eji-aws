<?php


namespace WPShield\Plugin\ContentProtectorPro\Features\Images;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Features\Images
 */
class Utils {

	/**
	 * Remove anchor tag that wrapped with $tag
	 *
	 * @param string &$content
	 * @param string  $tag
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function remove_parent_link( string &$content, string $tag ): string {

		$pattern = sprintf(
			'/<a.*?(<%s.*?>)<\/a>/',
			$tag
		);

		return preg_replace( $pattern, '$1', $content );
	}
}
