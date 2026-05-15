<?php // phpcs:ignoreFile ?>
<div {{{ utils.container_attributes(props, "bs-control-image-radio") }}}>

    <input type="hidden"
           data-setting="{{ props.input_name }}"
           value="{{ props.value }}"
           class="{{ props.input_class }}"
    >

    <#
    var options = props.choices || [];


    for(var index in options) {
        var key         = options[index][0];
        var item        = options[index][1] || {};
        var is_checked  = key === props.value;
    #>
        <div class="bf-image-radio-option {{ is_checked ? 'checked' : '' }}" data-id="{{ key }}">

            <label>
                <img src="{{ item.img }}"
                     alt="{{ item.label }}"
                     class="{{ item['class'] }}"
                >
                <# if ( item.label ) { #>
                    <p class="item-label">{{ item.label }}</p>
                <# } #>
            </label>
        </div>
    <# } #>
</div>