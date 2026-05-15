<?php // phpcs:ignoreFile ?>
<#
	var value = _.isArray(props.value) || _.isObject(props.value) ? props.value: {};
    var get = function(key) {

        for(var index in props.choices) {

            var current_key = props.choices[index] && props.choices[index][0];

            if(current_key == key) {

                return props.choices[index][1] || {};
            }
        }

        return {};
    };
#>
<div {{{ utils.container_attributes(props, "bf-sorter-groups-container") }}}>

    <input name="{{ props.input_name }}"
           data-setting="{{ props.input_name }}"
           value="{{ JSON.stringify(value) }}"
           class="{{ props.input_class }}" type="hidden"/>

    <ul id="bf-sorter-group-{{props.id}}"
	    class="bf-sorter-list bf-sorter-{{props.id}}">
        <#
            if(_.size(value) > 0) {

                for(var key in value) {

                    var item = value[key],
                        item_id = _.isObject(item) ? item.id : key,
                        label = _.isObject(item) ? item.label : item;
                #>
                    <li id="bf-sorter-group-item-{{props.id}}-{{item_id}}"
                        class="{{get(item_id)['css-class'] }} bf-sorter-item"
                        style="{{get(item_id).css }}" data-id="{{ item_id }}">
                        {{ label }}
                    </li>
        <#
                }
            } else {

                for(var index in props.choices) {

                    var item_id = props.choices[index][0];
                    var item = props.choices[index][1],
                        label = _.isObject(item) ? item.label : item;
                    #>
                    <li id="bf-sorter-group-item-{{props.id}}-{{item_id}}"
                        class="{{ item['css-class'] }} bf-sorter-item"
                        style="{{ item['css'] }}"  data-id="{{ item_id }}">
                        {{ label }}
                    </li>
             <# } #>
        <#  } #>
	</ul>
</div>

