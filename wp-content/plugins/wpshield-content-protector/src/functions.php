<?php

if ( ! function_exists( 'wpshield_cp_option' ) ) {

	/**
	 * Get Content Protector option.
	 *
	 * @param string $option_key
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	function wpshield_cp_option( string $option_key ) {

		return bf_get_option( $option_key, WpShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->product_id() );
	}
}

if ( ! function_exists( 'wpshield_cp_is_amp' ) ) {
	/**
	 * Detects active AMP page & plugin
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	function wpshield_cp_is_amp() {

		static $is_amp;

		if ( ! is_null( $is_amp ) ) {
			return $is_amp;
		}

		// BetterAMP plugin
		if ( function_exists( 'is_better_amp' ) && is_better_amp() ) {

			$is_amp = 'better';

		} elseif ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {

			// Official AMP Plugin
			$is_amp = 'amp';

		} else {

			$is_amp = false;
		}

		return $is_amp;
	}
}

if ( ! function_exists( 'wpshield_cp_list_pages' ) ) {

	/**
	 * List available pages.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function wpshield_cp_list_pages(): array {

		$results = [
			0 => __( 'None', 'wpshield-content-protector' ),
		];
		$pages   = get_pages( 'post_status=publish,private' );

		if ( ! $pages ) {

			return $results;
		}

		foreach ( $pages as $page ) {

			$results[ $page->ID ] = empty( $page->post_title ) ? wp_sprintf( '(page: %d)', $page->ID ) : $page->post_title;
		}

		return $results;
	}
}

if ( ! function_exists( 'wpshield_cp_get_roles' ) ) {

	/**
	 * Get list of all wp roles.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function wpshield_cp_get_roles(): array {

		global $wp_roles;

		if ( ! isset( $wp_roles ) ) {

			//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			$wp_roles = new \WP_Roles();
		}

		return $wp_roles->get_names();
	}
}

if ( ! function_exists( 'wpshield_cp_get_post_types' ) ) {

	/**
	 * Get list of all wp post types.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function wpshield_cp_get_post_types(): array {

		return array_map( 'wpshield_cp_field_label_sanitizer',
			get_post_types(
				[
					'public' => true,
				]
			)
		);
	}
}

if ( ! function_exists( 'wpshield_cp_field_label_sanitizer' ) ) {

	/**
	 * Retrieve sanitized label.
	 *
	 * @param string $label
	 *
	 * @since 1.0.0
	 * @return string
	 */
	function wpshield_cp_field_label_sanitizer( string $label ): string {

		return wp_sprintf( '%ss', ucfirst( $label ) );
	}
}
