<?php

if ( ! empty( $props['value'] ) ) {
	$on_checked  = 'selected';
	$off_checked = '';

} else {
	$on_checked  = '';
	$off_checked = 'selected';
}

?>
<#
    var on_checked  = '';
    var off_checked = 'selected';

    if ( props.value == 1 ) {

        on_checked  = 'selected';
        off_checked = '';
    }
#>
<div {{{ utils.container_attributes(props, "bf-switch") }}}>

    <a class="cb-enable {{ on_checked }}"><span>{{ ! _.isEmpty( props['on-label'] ) ? props['on-label'] : '<?php esc_attr_e( 'On', 'better-studio' ); ?>' }}</span></a>
    <a class="cb-disable {{off_checked}}"><span>{{ ! _.isEmpty( props['off-label'] ) ? props['off-label'] : '<?php esc_attr_e( 'Off', 'better-studio' ); ?>' }}</span></a>

    <input type="hidden"
           data-setting="{{ props.input_name }}"
           class="checkbox {{props.input_class}}"
           value="{{Number( props.value||0 )}}">
</div>
