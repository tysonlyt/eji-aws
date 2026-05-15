<?php
    use BetterFrameworkPackage\Component\Control;
?>
<#
    var value;

    if(_.isObject(props.value) && _.size(props.value) ) {

        value = props.value;
    } else {

        value = {
            img: _.isString(props.value) ? props.value : "",
            type: "cover"
        };
    }


    if( value.type === "no-repeat" ) {

        value.type = "top-left";
    }
#>

<div {{{ utils.container_attributes(props, "bf-control-background-image") }}}>
	<a href="#" class="button button-primary bf-main-button bf-background-image-upload-btn"
	   data-mediatitle="{{ props.media_title || '<?php esc_attr_e( 'Upload', 'better-studio' ); ?>' }}"
	   data-buttontext="{{ props.button_text || '<?php esc_attr_e( 'Upload', 'better-studio' ); ?>' }}"
     >
	<?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?>

        {{{ props.upload_label || '<?php esc_attr_e( 'Upload', 'better-studio' ); ?>' }}}
    </a>

	<a href="#"
	   class="bf-button button bf-background-image-remove-btn"{{{ ! value.img  ? ' style="display: none"' : '' }}} >
	<?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-remove' ); ?>
        {{{ props.remove_label || '<?php esc_attr_e( 'Remove', 'better-studio' ); ?>' }}}
	</a>

	<br/>

	<div class="bf-background-image-uploader-select-container bf-select-option-container {{ ! value.img ? "hidden" : "" }}">
		<select name="{{ props.input_name }}[type]"
		        id="{{ props.id }}-select" class="bf-background-image-uploader-select">

			<optgroup label="<?php esc_attr_e( 'Full Background Image', 'better-studio' ); ?>">
				<option value="cover" {{{ value.type === "cover" ? 'selected="selected"' : '' }}}><?php _e( 'Full Cover', 'better-studio' ); ?></option>
				<option value="fit-cover"{{{ value.type === "fit-cover" ? 'selected="selected"' : '' }}}><?php _e( 'Fit Cover', 'better-studio' ); ?></option>
				<option value="parallax" {{{ value.type === "parallax" ? 'selected="selected"' : '' }}}><?php _e( 'Parallax', 'better-studio' ); ?></option>
			</optgroup>

			<optgroup label="<?php esc_attr_e( 'Repeated Background Image', 'better-studio' ); ?>">
				<option value="repeat" {{{ value.type === "repeat" ? 'selected="selected"' : '' }}}><?php _e( 'Full Cover', 'better-studio' ); ?></option>
				<option value="repeat-y" {{{ value.type === "repeat-y" ? 'selected="selected"' : '' }}}><?php _e( 'Repeat Horizontal', 'better-studio' ); ?></option>
				<option value="repeat-x" {{{ value.type === "repeat-x" ? 'selected="selected"' : '' }}}><?php _e( 'Repeat Vertical', 'better-studio' ); ?></option>
				<option value="no-repeat" {{{ value.type === "no-repeat" ? 'selected="selected"' : '' }}}><?php _e( 'No Repeat', 'better-studio' ); ?></option>
			</optgroup>

			<optgroup label="<?php esc_attr_e( 'Static Background Image Position', 'better-studio' ); ?>">
				<option value="top-left" {{{ value.type === "top-left" ? 'selected="selected"' : '' }}}><?php _e( 'Top Left', 'better-studio' ); ?></option>
				<option value="top-center" {{{ value.type === "top-center" ? 'selected="selected"' : '' }}}><?php _e( 'Top Center', 'better-studio' ); ?></option>
				<option value="top-right" {{{ value.type === "top-right" ? 'selected="selected"' : '' }}}><?php _e( 'Top Right', 'better-studio' ); ?></option>
				<option value="left-center" {{{ value.type === "left-center" ? 'selected="selected"' : '' }}}><?php _e( 'Left Center', 'better-studio' ); ?></option>
				<option value="center-center" {{{ value.type === "center-center" ? 'selected="selected"' : '' }}}><?php _e( 'Center Center', 'better-studio' ); ?></option>
				<option value="right-center" {{{ value.type === "right-center" ? 'selected="selected"' : '' }}}><?php _e( 'Right Center', 'better-studio' ); ?></option>
				<option value="bottom-left" {{{ value.type === "bottom-left" ? 'selected="selected"' : '' }}}><?php _e( 'Bottom Left', 'better-studio' ); ?></option>
				<option value="bottom-center" {{{ value.type === "bottom-center" ? 'selected="selected"' : '' }}}><?php _e( 'Bottom Center', 'better-studio' ); ?></option>
				<option value="bottom-right" {{{ value.type === "bottom-right" ? 'selected="selected"' : '' }}}><?php _e( 'Bottom Right', 'better-studio' ); ?></option>
			</optgroup>
		</select>
	</div>

	<input type="hidden" name="{{ props.input_name }}[img]"
	       class="bf-background-image-input {{ props.input_class }}"
	       value="{{value.img}}" data-setting="{{ props.input_name }}">

	<div class="bf-background-image-preview"{{{ ! value.img  ? ' style="display: none"' : '' }}}>
		<img src="{{value.img}}"/>
	</div>

</div>
