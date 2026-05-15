<?php

namespace WPShield\Core\PluginCore;

if ( ! function_exists( 'wpshield_plugin_core_is_registered_product' ) ) {

	/**
	 * Retrieve the product is registered status.
	 *
	 * @param int $product_id
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	function wpshield_plugin_core_is_registered_product( int $product_id ): bool {

		$option_name = sprintf( '%s-register-info', $product_id );
		$options     = get_option( $option_name );

		return ! empty( $options['purchase_code'] );
	}
}
