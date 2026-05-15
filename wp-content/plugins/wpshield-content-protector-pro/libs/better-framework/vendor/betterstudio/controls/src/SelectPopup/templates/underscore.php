<?php // phpcs:ignoreFile ?>
<#
	var current = {
		key: "",
		label: props.default_text || '<?php esc_attr_e( 'chose one...', 'better-studio' ) ?>',
		img: ""
	};

	var options = _.isObject( props.options ) ? props.options : {};
	var value = props.value;

	if( value ) {

		if( _.isObject( options[value] ) ) {

			_.extend(current, options[value], {
				key: value
			});
		}
	}

	var select_style = _.isEmpty( props.select_style ) ? 'creative' : 'regular-select';

	if(! _.isObject(props.texts) ) {

		props.texts = {};
	}

    var input_name = _.isString( props.input_name ) ? props.input_name : "";
#>

<div {{{ utils.container_attributes(props, ['better-' + select_style + '-style', "select-popup-field", "bf-clearfix", input_name], {"data-heading": props.label}) }}}>

<# if( select_style === "regular-select" ) { #>

		<span class="active-item-label">{{{ current.label }}}</span>

<# } else { #>
			<div class="select-popup-selected-image">
				<img src="{{ current.current_img || current.img }}">
			</div>
			<div class="select-popup-selected-info">
				<div class="active-item-text">{{{ props.texts.box_pre_title || '<?php  esc_attr_e('Active item', 'better-studio'); ?>' }}}</div>
				<div class="active-item-label">{{{ current.label }}}</div>
				<a href="#" class="button button-primary">{{{ props.texts.box_button || '<?php esc_attr_e('Change', 'better-studio'); ?>' }}}</a>
			</div>
<# } #>

<# if ( props.data2print ) { #>
    <div style="display: none">
	    <noscript id="{{ input_name.replace(/\-/g,'_') }}" class="select-popup-data" type="application/json">
            {{{ JSON.stringify(props.data2print) }}}
        </noscript>
    </div>
<# } #>

	<input type="hidden"  data-setting="{{ props.input_name }}" value="{{ current.key }}" class="select-value {{ props.input_class }}"/>
</div>