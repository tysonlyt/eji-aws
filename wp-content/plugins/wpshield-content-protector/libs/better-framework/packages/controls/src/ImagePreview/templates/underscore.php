<?php // phpcs:ignoreFile ?>
<#

    var cast = function(value) {

        if(_.isEmpty(value)) {

            return [];
        }

        return _.isArray(value) || _.isObject(value) ? value : [value];
    };

    var values = cast(props.std);

    if(_.isEmpty(values)) {
        values = cast(props.value);
    }
#>
<div {{{ utils.container_attributes(props, "bs-control-image-preview") }}}>
    <div class="info-value {{ props['class'] }}"
         style="text-align: {{ props.align || "center" }}">

        <# for(var key in values) { #>
            <img class="image-{{ key }}" src="{{ values[key] }}">
        <# } #>
    </div>
</div>