<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$id = 'bf_image_upload_' . $props['id'];
?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-image-upload' ); ?>>

    <input type="text"
           name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"
    >

    <label for="<?php echo $id; ?>" class="button">
		<?php _e( 'Upload', 'better-studio' ); ?>

        <input type="file" id="<?php echo $id; ?>"
               class="bf-image-upload-choose-file hidden button button-primary bf-main-button"
               name="<?php echo esc_attr( 'bf_image_upload_' . $props['id'] ?? '' ); ?>"
        >
    </label>

    <div class="bf-image-upload-progress-bar">
        <div class="bar"></div>
    </div>

	<?php if ( ! empty( $props['value'] ) && filter_var( $props['value'], FILTER_VALIDATE_URL ) ) { ?>

        <div class="bf-image-upload-preview">
            <img src="<?php echo esc_url( $props['value'] ); ?>">
        </div>
	<?php } ?>

</div>
