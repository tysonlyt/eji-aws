<?php // phpcs:ignoreFile ?>
<#
    var id = props.id || ("id-"+Math.random()).replace('0.','');
    var options = props.choices || [];
    var values = _.isObject(props.value) ||  _.isArray(props.value) ? props.value : {};
#>
<div {{{ utils.container_attributes(props, "bs-control-checkbox") }}}>

    <#
        for(var index in options) {
            var key       = options[index][0];
            var label     = options[index][1];
            var option_id = id + "-" + key;
            var checked   = ! _.isEmpty( values[key] ) ? 'checked' : '';
    #>
        <div class="bs-control-checkbox-option">
            <input type="checkbox" value="{{ key }}" id="{{ option_id }}"
                   data-key="{{ key }}"
                   class="{{ props.input_class }}"
				   {{ checked }}>
            <label for="{{ option_id }}">{{ label }}</label>
        </div>
	<# } #>
</div>
