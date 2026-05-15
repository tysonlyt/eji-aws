<?php

    use BetterFrameworkPackage\Component\Control;

?>
<div {{{ utils.container_attributes(props, "bf-ajax_select-field-container") }}}>
    <div class="bf-ajax_select-input-container">
        <input data-single="{{props.single || false}}" type="text" class="bf-ajax-suggest-input" placeholder="{{props.placeholder}}"/>
        <span class="bf-search-loader">
            <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-search' ); ?>
        </span>
        <ul class="bf-ajax-suggest-search-results"></ul>
    </div>

    <input type="hidden" value="{{props.value}}"
           class="{{props.input_class}}"
           data-setting="{{ props.input_name }}"
           data-callback="{{props.callback}}"
           data-token="{{props.token}}"
    >

    <ul class="bf-ajax-suggest-controls">
        <#
        // Performance optimization
        if(_.isEmpty(props.values) && ! _.isEmpty(props.value) ) {

            props.values = props.value.split(',')
        }

        if ( _.isArray(props.values) ) {
          for ( var key in props.values ) {
            var val = props.values[key],
                isValueObject = _.isObject(val);

            var label = isValueObject ? val.label: val,
                ID = isValueObject ? val.id: val;

        #>
        <li data-id="{{ ID }}">
            {{{ label }}}
            <span class="bf-icon del-icon del"></span>
        </li>
        <# } } #>
    </ul>
</div>
