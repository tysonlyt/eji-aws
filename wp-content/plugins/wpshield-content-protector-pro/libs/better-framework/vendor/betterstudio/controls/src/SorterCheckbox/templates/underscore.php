<?php // phpcs:ignoreFile ?>
<#
    var value       = _.isObject(props.value) || _.isArray(props.value) ? props.value : {};
    var check_all   =  (! _.isSet(props.check_all) || props.check_all ) && _.size(value) == 0;
    var get = function(key) {

        for(var index in props.choices) {

            var current_key = props.choices[index] && props.choices[index][0];

            if(current_key == key) {

                return props.choices[index][1] || {};
            }
        }

        return {};
    };
    var displayed = [];

    var activeItems = [];

    for(var index in value)  {

        if(value[index] && value[index].active ){

            activeItems.push(value[index].id || index);
        }
    }

#>
<div {{{ utils.container_attributes(props, "bf-sorter-groups-container") }}}>

    <input name="{{ props.input_name }}"
           data-setting="{{ props.input_name }}"
           value="{{ JSON.stringify(value) }}"
           class="{{ props.input_class }}" type="hidden"/>

    <ul id="bf-sorter-group-{{props.id}}"
        class="bf-sorter-list bf-sorter-{{props.id}}">

        <# // Options That Saved Before
            for(var index in value) {
                var item = value[index];

                if(! item) {
                    continue;
                }
                var item_id = item.id || index;

                var option = get(item_id);

                if(! _.isObject(option)) {

                    continue;
                }

               var isActive = check_all ||item.active || activeItems.indexOf(item_id) !== -1;

                #>
                <li id="bf-sorter-group-item-{{props.id}}-{{item_id}}"
                    class="{{option['css-selector']}} item-{{item_id}} checked-item bf-sorter-item"
                    style="{{option.css}}">
                    <label>
                        <input data-id="{{item_id}}"
                               class="{{props.input_class}}"
                               value="1" type="checkbox"  {{{ isActive ?  'checked="checked"' : '' }}}/>
                        {{{ option.label }}}
                    </label>
                </li>
        <#

                displayed.push(item_id);
            }
        #>

        <# // Options That Not Saved but are Active

            for(var index in props.choices) {

                var item_id = props.choices[index] && props.choices[index][0];

                if(displayed.indexOf(item_id) !== -1) {
                    continue;
                }

                var item = props.choices[index][1] || {};

                if( typeof item['css-class'] === "string" && item['css-class'].indexOf('active-item') === -1 ){

                    continue;
                }

                var isActive = check_all ||item.active || activeItems.indexOf(item_id) !== -1;
        #>
                <li id="bf-sorter-group-item-{{props.id}}-{{item_id}}"
                    class="{{item['css-class']}} item-{{item_id}} {{ check_all ? ' checked-item' : '' }} bf-sorter-item" {{{ check_all ?  'checked="checked"' : '' }}}
                    style="{{item.css}}">
                    <label>
                        <input data-id="{{item_id}}"
                               class="{{props.input_class}}"
                               value="1" type="checkbox" {{{ isActive ?  'checked="checked"' : '' }}}/>
                        {{{ item.label }}}
                    </label>
                </li>
        <#
                displayed.push(item_id);
            }
        #>

        <# // Disable Items

            for(var index in props.choices) {

                var item_id = props.choices[index] && props.choices[index][0];

                if(displayed.indexOf(item_id) !== -1) {
                    continue;
                }

                var item = props.choices[index][1]||{};
            #>
                <li id="bf-sorter-group-item-{{ props.id }}-{{ item_id }}"
                    class="{{ item['css-class'] }} item-{{ item_id }} bf-sorter-item"
                    style="{{ item.css }}">
                    <label>
                        <input data-id="{{item_id}}"
                               class="{{props.input_class}}"
                               value="0" type="checkbox" disabled/>
                        {{{ item.label }}}
                    </label>
                </li>
        <#
                displayed.push(item_id);
            }
        #>
    </ul>
</div>
