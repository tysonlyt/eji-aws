<?php // phpcs:ignoreFile ?>
<div {{{ utils.container_attributes(props, "bf-button-field-container") }}}>
	<a class="button button-primary bf-button bf-main-button {{ props['class-name'] }}"
	<# if(_.isObject(props['custom-attrs'])) {
	for(var key in props['custom-attrs']) { #>
	{{key}}="{{props['custom-attrs'][key]}}"
	<#    }
	}
	#>
	>{{ props.button_name || props.label }}</a>
</div>