<?php // phpcs:ignoreFile ?>
<#
var options = props.choices || [];
var values = [];
if(lodash.isArray(props.value)) {

    values = props.value;

} else if( _.isString(props.value) ) {

    values = props.value.split(',');

} else if(! _.isUndefined(props.value)) {

    values = [props.value];
}

#>
<div {{{ utils.container_attributes(props, ["bf-advanced-select", props.status_class]) }}}>
    <ul class="bf-advanced-select-group">

        <# for(var option_index in options) {
            var option_id = options[option_index][0];
            var option = options[option_index][1];

            var classes = utils.classnames(option.classes, {
                active: values.indexOf(option_id) !== -1
            });
        #>

        <li class="{{ classes }}" data-value="{{ option_id }}" style="{{ option.inline_styles }}">

            <# if( ! lodash.isEmpty(option.icon) ) { #>
                <span class="icon icon-{{option_index}}-1"></span>
                <# utils.the_icon( '.icon-'+option_index+'-1', option.icon ) #>
            <# } #>

	        <# if( !lodash.isEmpty(option.label) ) { #>
                <span class="label">{{{ option.label }}}</span>
	        <# } #>

            <# if( !lodash.isEmpty(option.badge) ) { #>
            <span class="badge {{ option.badge.classes }}" style="{{ option.badge.inline_styles }}">
                    <# if( option.badge.icon ) { #>
                        <span class="icon icon-{{option_index}}-2"></span>
                       <# utils.the_icon( '.icon-'+option_index+'-2', option.badge.icon ) #>
                    <# } #>

                    {{{ option.badge.label }}}
                </span>
            <# } #>
        </li>
        <# } #>
    </ul>

    <input type="hidden"
           data-setting="{{ props.input_name }}"
           name="{{props.input_name}}"
           class="value {{ props.input_class }}"
           value="{{ props.value }}"/>
</div>