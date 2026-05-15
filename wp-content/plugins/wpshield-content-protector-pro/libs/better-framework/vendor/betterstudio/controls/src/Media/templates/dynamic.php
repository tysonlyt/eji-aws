<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-media' ); ?>>

    <input type="text" name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"
    >
    <a href="#" class="bf-main-button button button-primary bf-media-upload-btn"
       data-mediatitle="<?php echo esc_attr( $props['media_title'] ?? __( 'Upload', 'better-studio' ) ); ?>"
       data-buttontext="<?php echo esc_attr( $props['button_text'] ?? __( 'Upload', 'better-studio' ) ); ?>"
    > <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?> <?php echo( $props['button_text'] ?? __( 'Upload', 'better-studio' ) ); ?></a>
</div>
