<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

// stripcslashes for when json is splashed!

if ( ! empty( $props['value'] ) && is_array( $props['value'] ) ) {

	$value = $props['value'];
} else {

	$value = [
		'img'  => is_string( $props['value'] ) ? $props['value'] : '',
		'type' => 'cover',
	];
}


// version < 2 compatibility
if ( $value['type'] === 'no-repeat' ) {
	$value['type'] = 'top-left';
}

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-control-background-image' ); ?>>

    <a href="#" class="button button-primary bf-main-button bf-background-image-upload-btn"
       data-mediatitle="<?php echo esc_attr( $props['media_title'] ?? __( 'Upload', 'better-studio' ) ); ?>"
       data-buttontext="<?php echo esc_attr( $props['button_text'] ?? __( 'Upload', 'better-studio' ) ); ?>">
	    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?>
	    <?php echo $props['upload_label'] ?? __( 'Upload', 'better-studio' ); ?>
    </a>

    <a href="#"
       class="bf-button button bf-background-image-remove-btn"<?php echo empty( $value['img'] ) ? ' style="display: none"' : ''; ?>>
	    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-remove' ); ?>
        <?php echo $props['remove_label'] ?? __( 'Remove', 'better-studio' ); ?>
    </a>

    <br/>

    <div class="bf-background-image-uploader-select-container bf-select-option-container <?php echo empty( $value['img'] ) ? 'hidden' : ''; ?>">
        <select name="<?php echo $props['input_name'] . '[type]'; ?>"
                id="<?php echo $props['id'] . '-select'; ?>" class="bf-background-image-uploader-select">

            <optgroup label="<?php esc_attr_e( 'Full Background Image', 'better-studio' ); ?>">
                <option value="cover" <?php echo $value['type'] === 'cover' ? 'selected="selected"' : ''; ?>><?php _e( 'Full Cover', 'better-studio' ); ?></option>
                <option value="fit-cover" <?php echo $value['type'] === 'fit-cover' ? 'selected="selected"' : ''; ?>><?php _e( 'Fit Cover', 'better-studio' ); ?></option>
                <option value="parallax" <?php echo $value['type'] === 'parallax' ? 'selected="selected"' : ''; ?>><?php _e( 'Parallax', 'better-studio' ); ?></option>
            </optgroup>

            <optgroup label="<?php esc_attr_e( 'Repeated Background Image', 'better-studio' ); ?>">
                <option value="repeat" <?php echo $value['type'] === 'repeat' ? 'selected="selected"' : ''; ?>><?php _e( 'Full Cover', 'better-studio' ); ?></option>
                <option value="repeat-y" <?php echo $value['type'] === 'repeat-y' ? 'selected="selected"' : ''; ?>><?php _e( 'Repeat Horizontal', 'better-studio' ); ?></option>
                <option value="repeat-x" <?php echo $value['type'] === 'repeat-x' ? 'selected="selected"' : ''; ?>><?php _e( 'Repeat Vertical', 'better-studio' ); ?></option>
                <option value="no-repeat" <?php echo $value['type'] === 'no-repeat' ? 'selected="selected"' : ''; ?>><?php _e( 'No Repeat', 'better-studio' ); ?></option>
            </optgroup>

            <optgroup label="<?php esc_attr_e( 'Static Background Image Position', 'better-studio' ); ?>">
                <option value="top-left" <?php echo $value['type'] === 'top-left' ? 'selected="selected"' : ''; ?>><?php _e( 'Top Left', 'better-studio' ); ?></option>
                <option value="top-center" <?php echo $value['type'] === 'top-center' ? 'selected="selected"' : ''; ?>><?php _e( 'Top Center', 'better-studio' ); ?></option>
                <option value="top-right" <?php echo $value['type'] === 'top-right' ? 'selected="selected"' : ''; ?>><?php _e( 'Top Right', 'better-studio' ); ?></option>
                <option value="left-center" <?php echo $value['type'] === 'left-center' ? 'selected="selected"' : ''; ?>><?php _e( 'Left Center', 'better-studio' ); ?></option>
                <option value="center-center" <?php echo $value['type'] === 'center-center' ? 'selected="selected"' : ''; ?>><?php _e( 'Center Center', 'better-studio' ); ?></option>
                <option value="right-center" <?php echo $value['type'] === 'right-center' ? 'selected="selected"' : ''; ?>><?php _e( 'Right Center', 'better-studio' ); ?></option>
                <option value="bottom-left" <?php echo $value['type'] === 'bottom-left' ? 'selected="selected"' : ''; ?>><?php _e( 'Bottom Left', 'better-studio' ); ?></option>
                <option value="bottom-center" <?php echo $value['type'] === 'bottom-center' ? 'selected="selected"' : ''; ?>><?php _e( 'Bottom Center', 'better-studio' ); ?></option>
                <option value="bottom-right" <?php echo $value['type'] === 'bottom-right' ? 'selected="selected"' : ''; ?>><?php _e( 'Bottom Right', 'better-studio' ); ?></option>
            </optgroup>
        </select>
    </div>

    <input type="hidden" name="<?php echo esc_attr( $props['input_name'] . '[img]' ); ?>"
           class="bf-background-image-input <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( $value['img'] ?? '' ); ?>">


    <div class="bf-background-image-preview"<?php echo empty( $value['img'] ) ? ' style="display: none"' : ''; ?>>
        <img src="<?php echo esc_url( $value['img'] ); ?>"/>
    </div>
</div>
