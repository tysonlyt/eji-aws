<?php

$reg_info = bf_register_product_get_info( \WPShield\Core\PluginCore\PluginSetup::PRODUCT_ITEM_ID );
?>
<div class="bs-register-product bf-clearfix">
	<?php

	if ( ! isset( $reg_info['status'] ) || 'success' !== $reg_info['status'] ) :

		$desc = sprintf( __( 'Your license of %s is not registered. Place your license code to unlock automatic updates, access to support, and Plugins. <a target="_blank" href="https://betterstudio.com/account/license-manager/">Get a license code</a> from getwpshield.com account and paste it in following box.', 'wpshield' ), $product_name ?? '' );

		$icons = sprintf(
			'%1$s%2$s%3$s',
			bf_get_icon_tag( 'bsai-lock', 'register-product-icon' ),
			bf_get_icon_tag( 'bsai-key', 'register-product-icon bs-icon-green' ),
			bf_get_icon_tag( 'bsai-unlock', 'register-product-icon' )
		);

		//phpcs:ignore
		$page = esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ?? '' ) ) );
		bf_product_box( array(
			'icon'        => 'bsai-lock',
			'header'      => sprintf( __( 'Register %s', 'wpshield' ), $product_name ?? '' ),
			'has_loading' => true,
			'description' => '
		<div class="bs-product-desc">
		<div class="bs-icons-list">
            ' . $icons . '
	</div>
	<p>
		' . $desc . '
	</p>
</div>

<form action="" id="bs-register-product-form">
	' . wp_nonce_field( 'bs-register-product', 'bs-register-token', false ) . '
	<input type="hidden" name="page" value="' . $page . '">
	<input type="text" name="bs-purchase-code" id="bs-purchase-code" class="bs-purchase-code"
	       placeholder="' . esc_attr__( 'Enter Code and Hit Enter', 'wpshield' ) . '">
    <input type="hidden" name="item_id" value="wpshield-license" >
</form>

',
			'classes'     => array( 'bs-fullwidth-box' ),
		) );
		?>
	<?php else : ?>
		<?php

		$desc = sprintf( __( 'Your license of %s is registered.', 'wpshield' ), $product_name ?? '' );

		bf_product_box( array(
			'icon'        => 'bsai-unlock',
			'header'      => sprintf( __( 'Register %s', 'wpshield' ), $product_name ?? '' ),
			'has_loading' => true,
			'description' => '
		<div class="bs-product-desc">
		<div class="bs-icons-list">
            ' . bf_get_icon_tag( 'bsai-unlock', 'register-product-icon bs-icon-green' ) . '
	</div>
	<p>
		' . $desc . '
	</p>
</div>',
		) );
		?>
	<?php endif; ?>
</div>
