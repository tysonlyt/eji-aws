<?php //phpcs:disable

if ( is_admin() ) {

	return false;
}



$title   = ! empty( $title ) ? $title : ( $_POST['title'] ?? __( 'Content Protected!', 'wpshield-content-protector' ) );
$content = ! empty( $content ) ? $content : ( $_POST['content'] ?? __( 'The content of this website cannot be copied!', 'wpshield-content-protector' ) );
$icon    = ! empty( $icon ) && ! empty( $icon['icon'] ) ? bf_get_icon_tag( $icon, 'cp-icon' ) : ( $_POST['icon'] ?? bf_get_icon_tag( 'bsfi-warning-1', 'cp-icon' ) );
$color   = ! empty( $color ) ? $color : ( $_POST['color'] ?? '#DC1F1F' );

//Backward compatible
//when template missed default values and not set value in template settings!
if ( empty( $color ) ) {
	$color = '#DC1F1F';
}

if ( empty( $icon ) || empty( $icon['icon'] ) ) {
	$icon = bf_get_icon_tag( 'bsfi-warning-1', 'cp-icon' );
}

if ( function_exists( 'esc_html' ) ) {
	$title   = esc_html( $title );
	$content = esc_html( $content );
}

?>
<div class="cp-alert cp-alert-1" style="--cp-primary-color: <?php echo $color; ?>">
	<div class="cp-alert-inner" role="alert">
		<?php

		echo bf_get_icon_tag( 'bsfi-close', 'cp-close' );

		if ( ! empty( $icon ) ) {
			echo ! empty( $icon['icon'] ) ?
				bf_get_icon_tag( $icon['icon'], 'cp-icon' ) :
				(
				is_array( $icon ) ? bf_get_icon_tag( 'stop', 'cp-icon' ) : $icon
				);//before escaped
		}

		?>
		<h4 class="cp-alert-heading"><?php esc_html_e( $title ) ?></h4>

		<p class="cp-alert-message"><?php esc_html_e( $content ) ?></p>
	</div>
</div>
