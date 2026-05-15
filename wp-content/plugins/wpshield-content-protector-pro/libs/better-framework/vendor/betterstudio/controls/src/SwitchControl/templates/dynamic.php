<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var $props array
 */

if ( ! empty( $props['value'] ) ) {
	$on_checked  = 'selected';
	$off_checked = '';

} else {
	$on_checked  = '';
	$off_checked = 'selected';
}

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-switch' ); ?>>

    <a class="cb-enable <?php echo esc_attr( $on_checked ); ?>"><span><?php echo esc_html( $props['on-label'] ?? __( 'On', 'better-studio' ) ); ?></span></a>
    <a class="cb-disable <?php echo esc_attr( $off_checked ); ?>"><span><?php echo esc_html( $props['off-label'] ?? __( 'Off', 'better-studio' ) ); ?></span></a>

    <input type="hidden" name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           value="<?php echo (int) ( $props['value'] ?? '' ); ?>"
           class="checkbox <?php echo esc_attr( $props['input_class'] ?? '' ); ?>">
</div>
