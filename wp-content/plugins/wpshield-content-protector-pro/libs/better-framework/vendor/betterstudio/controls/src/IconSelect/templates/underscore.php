<?php // phpcs:ignoreFile ?>
<#

function iconTag(icon) {

    if(! icon|| !icon.id ) {

        return '';
    }

    var icon_id = icon.id.toString();

    if (icon_id.substr(0, 3) == 'fa-') {

     return '<i class="bf-icon fa ' + icon_id + '"></i>';

    } else if (icon_id.substr(0, 5) == 'bsfi-') { // BetterStudio Font Icon

        return '<i class="bf-icon ' + icon_id + '"></i>';

    } else if (icon_id.substr(0, 10) == 'dashicons-') { // Dashicon

        return '<i class="bf-icon dashicons dashicons-' + icon + '"></i>';
    } else if (icon_id.substr(0, 5) == 'bsai-') { // Better Studio Admin Icon

        return '<i class="bf-icon ' + icon_id + '"></i>';
    }


    if (icon) {  // Custom Icon -> as URL

        return '<i class="bf-icon bf-custom-icon bf-custom-icon-url"><img src="' + icon_id + '"></i>';
    }

    return '';
}

var dataValue = _.isObject(props.value) ? props.value : {};
var icon = _.isObject(dataValue.icon) ? dataValue.icon : {};
var theID = Math.ceil(Math.random()*1000);
#>

<div {{{ utils.container_attributes(props, "bf-icon-modal-handler", {id: "bf-icon-modal-handler-"+ theID}) }}}>

    <div class="select-options">
        <# var tagIcon = props.icon_tag ? props.icon_tag : iconTag(dataValue); #>
        <span class="selected-option">{{{tagIcon}}} {{{dataValue.label}}}</span>
    </div>

    <input type="hidden" class="icon-input {{props.input_class}}"
           data-setting="{{ props.input_name }}"
           value="{{icon.key}}"/>

    <input type="hidden" class="icon-input-type {{props.input_class}}"
           data-setting="{{ props.input_name }}"
           value="{{icon.type}}"/>

    <input type="hidden" class="icon-input-height {{props.input_class}}"
           data-setting="{{ props.input_name }}"
           value="{{icon.height}}"/>

    <input type="hidden" class="icon-input-width {{props.input_class}}"
           data-setting="{{ props.input_name }}"
           value="{{icon.width}}"/>

    <input type="hidden" class="icon-input-font-code {{props.input_class}}"
           data-setting="{{ props.input_name }}"
           value="{{icon.font_code}}"/>
</div><!-- modal handler container -->

