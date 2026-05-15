<?php // phpcs:ignoreFile ?>
<#
	var style = props.layout ||"style-1";
#>
<div {{{ utils.container_attributes(props, ["bf-section-heading", "bf-clearfix", style]) }}}>

	<div class="bf-section-heading bf-clearfix {{ style }}">
		<div class="bf-section-heading-title bf-clearfix">
			<h3>{{ props.label }}</h3>
		</div>
        <# if( props.desc ) { #>
			<div class="bf-section-heading-desc bf-clearfix">{{{ props.desc }}}</div>
		<# } #>
	</div>
</div>

