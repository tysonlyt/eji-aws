<?php // phpcs:ignoreFile ?>
<#

	var esc_textarea = function (text) {

		text = text.toString();
		text = text.replace(/</g,'&lt;').replace(/>/g,'&gt;');
		text = text.replace(/"/g,'&quot;').replace(/'/g,'&#039;');

		return text;
	};

	var input_class = props.input_class || "";

	if( props.rtl ) {

		input_class += " rtl";
	}

	if( props.ltr ) {

		input_class += " ltr";
	}
#>
<div {{{ utils.container_attributes(props, ["bs-control-textarea", input_class]) }}}>
	<textarea data-setting="{{ props.input_name }}"
	          class="{{ input_class }}"
	          placeholder="{{ props.placeholder }}"
	>{{ esc_textarea( props.value ) }}</textarea>
</div>