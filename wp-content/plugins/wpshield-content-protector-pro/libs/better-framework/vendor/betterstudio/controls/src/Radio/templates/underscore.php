<?php // phpcs:ignoreFile ?>
<div {{{ utils.container_attributes(props, "bs-control-radio") }}}>
    <#
        var options = props.choices || {};
        var unique_id = props.id || ("id-"+Math.random()).replace('0.','');

    for( var index in options )  {
        var key = options[index][0];

        var input_id = unique_id + "-" + key;
    #>
        <div class="bf-radio-button-option">
            <input type="radio" class="{{ props.input_class }}" value="{{ key }}" name="{{ props.input_name }}"
                   data-setting="{{ props.input_name }}" id="{{ input_id }}" {{ props.value == key ? "checked" : "" }}>

            <label for="{{ input_id }}">{{ options[index][1] }}</label>
        </div>
    <# } #>
</div>