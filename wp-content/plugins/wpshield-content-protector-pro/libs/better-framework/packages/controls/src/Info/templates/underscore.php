<?php
    use BetterFrameworkPackage\Component\Control;
?>
<#
	var infoType = props['info-type'] || "info";
	var theIcon = function(type) {
		switch ( type ) {

			case 'help':
				return '<?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-support' ); ?> ';
			break;

			case 'warning':
				return '<?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-warning' ); ?> ';
			break;

			case 'danger':
				return '<?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-exclamation' ); ?> ';
			break;

			case 'info':
			default:
				return '<?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-info' ); ?> ';
			break;
		}
	};
#>
<div {{{ utils.container_attributes(props, ["bf-info-control", infoType, props.state || 'open', 'bf-clearfix' ]) }}}>
	<div class="bf-info-control-title bf-clearfix">
		<h3>
			{{{ theIcon( props['info-type'] ) }}}
			{{ props.label }}
		</h3>
	</div>
	<div class="bf-info-control-text bf-clearfix">
		{{{ props.value||props.std }}}
	</div>
</div>
