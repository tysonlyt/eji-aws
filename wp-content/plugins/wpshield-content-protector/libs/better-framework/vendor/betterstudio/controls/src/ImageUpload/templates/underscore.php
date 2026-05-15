<?php // phpcs:ignoreFile ?>
<#
    var id = props.id || Math.ceil(Math.random()*1000);
    var value = props.value || '';
#>
<div {{{ utils.container_attributes(props, "bs-control-image-upload") }}}>

    <input type="text"
           data-setting="{{ props.input_name }}"
           class="{{ props.input_class }}"
           value="{{ value }}"
    >

    <label for="bf_image_upload_{{ id }}" class="button">
		<?php _e( 'Upload', 'better-studio' ) ?>

        <input type="file" id="bf_image_upload_{{ id }}"
               class="bf-image-upload-choose-file hidden button button-primary bf-main-button"
               name="bf_image_upload_{{ id }}"
        >
    </label>

    <div class="bf-image-upload-progress-bar">
        <div class="bar"></div>
    </div>

	<# if ( ! _.isEmpty(value) ) { #>
        <div class="bf-image-upload-preview">
            <img src="{{ value }}">
        </div>
	<# } #>

</div>
