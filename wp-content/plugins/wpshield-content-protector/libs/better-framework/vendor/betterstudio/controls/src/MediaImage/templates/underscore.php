<?php
    use BetterFrameworkPackage\Component\Control;
?>
<#
    var hidden = _.isEmpty(props.value) ? ' style="display: none"' : '';
#>
<div {{{ utils.container_attributes(props, "bs-control-media-image") }}}>

    <input type="{{ props.show_input  ? 'text' : 'hidden' }}"
           data-setting="{{ props.input_name }}"
           value="{{ props.value }}"
           placeholder="{{ props.input_placeholder || '<?php esc_attr_e( 'Image external link...', 'better-studio' ); ?>' }}"
           class="bf-media-image-input ltr {{ props.input_class }}"
    >
	<# if ( ! props.hide_buttons ) { #>

        <a href="#"
           class="button button-primary bf-main-button bf-media-image-upload-btn {{props.upload_button_class}}"
           data-media-title="{{ props.media_title || '<?php esc_attr_e( 'Upload', 'better-studio' ); ?>' }}"
           data-button-text="{{ props.media_button || '<?php esc_attr_e( 'Upload', 'better-studio' ); ?>' }}"
           data-size="{{ props['preview-size'] || 'thumbnail' }}"
        >
	        <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?>
            {{{  props.upload_label || '<?php esc_attr_e( 'Upload', 'better-studio' ); ?>' }}}

        <a href="#"
           class="button bf-media-image-remove-btn"{{{ hidden }}}><i
                    class="fa fa-remove"></i>
            {{{ props.remove_label || '<?php esc_attr_e( 'Remove', 'better-studio' ); ?>' }}}
        </a>
	<# } #>

	<# if( ! props.hide_preview ) {  #>

        <div class="bf-media-image-preview"{{{ hidden }}}>
            <img src="{{ props.preview_image_url ||props.value }}">
        </div>

	<# } #>
</div>
