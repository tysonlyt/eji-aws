<?php // phpcs:ignoreFile ?>
<div {{{ utils.container_attributes(props, ["bf-select-option-container", {multiple: !!props.multiple}]) }}}>

	<select data-setting="{{ props.input_name }}"
	        class="{{ props.input_class }}"
            {{ props.multiple ? "multiple" : "" }}
	>
    <#
        var optionElements = function(options) {

            if(!options) {

                return ;
            }

            for( var _key in  options){

                if (_.isObject(options[_key])){
                    continue;
                }

                #>
                    <option value="{{ _key }}">{{ options[_key] }}</option>
                <#
            }

            for(var key in options) {

                if (!_.isObject(options[key])){

                    continue;
                }

                var option = _.isObject(options[key]) ? options[key] : {};
                var disabled = typeof option.disabled !== 'undefined' && option.disabled ? ' disabled': '';

                if(option.options) { #>

                        <optgroup label="{{ option.label }}" {{ disabled }}>
                            <# optionElements(option.options); #>
                        </optgroup>

                <# } else if(option.raw) { #>

                    {{{ option.raw }}}

                <# } else if(option.label) { #>

                    <option value="{{ key }}" {{ disabled }}>{{ option.label }}</option>

                <# } else if (typeof option !== 'object') { #>
                    <option value="{{ key }}" {{ disabled }}>{{option}}</option>
                <# }
            }
        };

        optionElements( props.options );
    #>
	</select>
</div>
