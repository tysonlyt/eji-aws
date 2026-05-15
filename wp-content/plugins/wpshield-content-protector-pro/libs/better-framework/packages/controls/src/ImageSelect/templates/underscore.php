<#

    var choices = props.choices || [];

    var get = function(key) {

        for(var index in choices) {

            var current_key = choices[index] && choices[index][0];

            if(current_key == key) {

                return choices[index][1] || {};
            }
        }

        return {};
    };
	var value = props.value;
    var selected = get(value);
#>

<div {{{ utils.container_attributes(props, ["better-select-image", props.input_name]) }}}>
    <div class="select-options">
        <span class="selected-option">{{{ selected.label || props.default_text || '<?php esc_attr_e( 'chose one...', 'better-studio' ); ?>' }}}</span>
        <div class="better-select-image-options">
            <ul class="options-list {{ props.list_style || 'grid-2-column' }} bf-clearfix">
                <# for(var index in choices) {
                    var key     = choices[index][0];
                    var option  = choices[index][1] || {};
                #>
                <li data-value="{{ key }}" data-label="{{ option.label }}" class="image-select-option {{ key === value ? 'selected' : '' }}">
                    <img src="{{ option.img }}" alt="{{ option.label }}"/><p>{{{ option.label }}}</p>
                </li>
                <# } #>
            </ul>
        </div>
    </div>
    <input type="hidden" data-setting="{{ props.input_name }}" id="{{ props.input_name }}" value="{{ value }}" class="{{ props.input_class }}"/>
</div>
