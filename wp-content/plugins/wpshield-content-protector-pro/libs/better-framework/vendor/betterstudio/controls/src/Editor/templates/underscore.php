<?php // phpcs:ignoreFile ?>
<#
var esc_textarea = function(text) {

	text = text.toString();
	text = text.replace(/</g,'&lt;').replace(/>/g,'&gt;');
	text = text.replace(/"/g,'&quot;').replace(/'/g,'&#039;');

	return text;
};
#>
<div {{{ utils.container_attributes(props, "bf-editor-wrapper") }}}>
		<pre class="bf-editor" data-lang="{{props.lang || 'text'}}"
		     data-max-lines="{{ props['max-lines'] || 15 }}"
		     data-min-lines="{{ props['min-lines'] || 10 }}"></pre>

	<textarea data-setting="{{ props.input_name }}"
	          class="bf-editor-field {{ props.input_class }}">{{ esc_textarea(props.value) }}</textarea>
</div>
