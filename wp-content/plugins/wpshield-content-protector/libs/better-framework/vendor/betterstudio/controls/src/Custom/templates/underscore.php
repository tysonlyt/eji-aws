<div {{{ utils.container_attributes(props, "bf-custom-field-wrapper") }}}>
    <# if(! _.isEmpty(props.callback)) { #>
        <div class="bf-custom-field-view"
             data-callback="{{ props.callback }}"
             data-callback-args="{{ JSON.stringify( props.callback_args || '' ) }}"
             data-token="{{ props.token }}"
        ><?php _e( 'Loading...', 'better-studio' ); ?></div>
    <# } else { #>
        {{{ props.input }}}
    <# } #>

        <#
        if(props.bsControlLoaded) {
            eval(props['js-code'] || "");
        }
     #>

    <# if( !_.isEmpty(props['css-code'] ) ) { #>
         <style>{{{ props['css-code'] }}}</style>
    <# } #>

    <input type="hidden" class="bf-custom-field-values {{props.input_class}}" value="{{props.value}}" data-setting="{{ props.input_name }}"/>
</div>
