<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$hidden = empty( $props['value'] ) ? ' style="display: none"' : '';
?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-media-image' ); ?>>

    <input type="<?php echo esc_attr( ! empty( $props['show_input'] ) ? 'text' : 'hidden' ); ?>"
           name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"
           placeholder="<?php echo esc_attr( $props['input_placeholder'] ?? __( 'Image external link...', 'better-studio' ) ); ?>"
           class="bf-media-image-input ltr <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
    >
	<?php if ( empty( $props['hide_buttons'] ) ) { ?>

        <a href="#"
           class="button button-primary bf-main-button bf-media-image-upload-btn <?php echo esc_attr( $props['upload_button_class'] ?? '' ); ?>"
           data-data-type="<?php echo esc_attr( $props['data-type'] ?? 'src' ); ?>"
           data-media-title="<?php echo esc_attr( $props['media_title'] ?? __( 'Upload', 'better-studio' ) ); ?>"
           data-button-text="<?php echo esc_attr( $props['media_button'] ?? __( 'Upload', 'better-studio' ) ); ?>"
           data-size="<?php echo esc_attr( $props['preview-size'] ?? 'thumbnail' ); ?>"
        >
	        <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?>
           <?php echo $props['upload_label'] ?? __( 'Upload', 'better-studio' ); ?></a>

        <a href="#"
           class="button bf-media-image-remove-btn"<?php echo $hidden; ?>>
	        <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-remove' ); ?> <?php echo $props['remove_label'] ?? __( 'Remove', 'better-studio' ); ?>
        </a>
	<?php } ?>

	<?php if ( empty( $props['hide_preview'] ) ) { ?>

        <div class="bf-media-image-preview"<?php echo $hidden; ?>>
            <img src="<?php echo esc_url( $props['preview_image_url'] ?? '' ); ?>">
        </div>

	<?php } ?>
</div>
