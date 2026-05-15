<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-slider' ); ?>>

    <div class="bf-slider-slider"
         data-dimension="<?php echo esc_attr( $props['dimension'] ?? '' ); ?>"
         data-animation="<?php echo empty( $props['animation'] ) ? 'disable' : 'enable'; ?>"
         data-val="<?php echo (int) ( $props['value'] ?? 0 ); ?>"
         data-min="<?php echo (int) ( $props['min'] ?? 0 ); ?>"
         data-max="<?php echo (int) ( $props['max'] ?? 100 ); ?>"
         data-step="<?php echo (int) ( $props['step'] ?? 1 ); ?>"
    ></div>
    <input type="hidden" value="<?php echo (int) ( $props['value'] ?? 0 ); ?>"
           name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           class="bf-slider-input <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
    >
</div>
