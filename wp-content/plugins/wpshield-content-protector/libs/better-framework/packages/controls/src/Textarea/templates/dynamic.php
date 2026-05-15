<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$classes = $props['input_class'] ?? '';
if ( ! empty( $props['rtl'] ) ) {

	$classes .= ' rtl';
}
if ( ! empty( $props['ltr'] ) ) {

	$classes .= ' ltr';
}
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'bs-control-textarea', $classes ] ); ?>>

<textarea name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
          class="<?php echo esc_attr( $classes ); ?>"
          placeholder="<?php echo esc_attr( $props['placeholder'] ?? '' ); ?>"
><?php echo esc_textarea( $props['value'] ?? '' ); ?></textarea>
</div>
