<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$upload = __( 'Upload', 'better-studio' );
?>
<div {{{ utils.container_attributes(props, ["bs-control-media"]) }}}>

    <input type="text"
           data-setting="{{ props.input_name }}"
           class="{{props.input_class}}"
           value="{{props.value}}"
    >
    <a href="#" class="bf-main-button button button-primary bf-media-upload-btn"
       data-mediatitle="{{ !_.isEmpty(props.media_title) ? props.media_title : '<?php echo $upload; ?>' }}"
       data-buttontext="{{ !_.isEmpty(props.button_text) ? props.button_text : '<?php echo $upload; ?>' }}"
    ><?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?> {{{ !_.isEmpty(props.button_text) ? props.button_text : '<?php echo $upload; ?>' }}}</a>
</div>
