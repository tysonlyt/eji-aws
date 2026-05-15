<?php


class BF_Gutenberg_Shortcode_Render {

	public static function __callStatic( $shortcode_id, $arguments ) {

		$attrs   = &$arguments[0];
		$content = &$arguments[1];

		if ( ! $attrs ) {
			$attrs = [];
		}

		if ( ! is_array( $attrs ) ) {
			$attrs = (array) $attrs;
		}

		$rendered = BF_Shortcodes_Manager::handle_shortcodes( $attrs, $content, $shortcode_id );
		$rendered = trim( $rendered );

		if ( '' === $rendered || '' === trim( wp_strip_all_tags( html_entity_decode( $rendered ) ) ) ) {

			if ( is_admin() || bf_server_side_block_render() ) {

				return sprintf( '<h4 class="bf-gutenberg-shortocde-empty">[%1$s][/%1$s]</h4>', $shortcode_id );
			}
		}

		return $rendered;
	}
}
