<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */
$type = $props['info-type'] ?? 'info';
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'bf-info-control', $type, $props['state'] ?? 'open', 'bf-clearfix' ] ); ?>>

    <div class="bf-info-control-title bf-clearfix">
        <h3>
        <?php
        switch ( $type ) {

			case 'help':
				\BetterFrameworkPackage\Component\Control\print_icon( 'fa-support' );
	            break;

			case 'warning':
				\BetterFrameworkPackage\Component\Control\print_icon( 'fa-warning' );
	            break;

			case 'danger':
				\BetterFrameworkPackage\Component\Control\print_icon( 'fa-exclamation' );
	            break;

			case 'info':
			default:
				\BetterFrameworkPackage\Component\Control\print_icon( 'fa-info' );
	            break;
		}

			echo esc_html( $props['name'] ?? '' );
		?>
            </h3>
    </div>
    <div class="bf-info-control-text bf-clearfix">
		<?php echo $props['value'] ?? $props['std'] ?? ''; // escaped before ?>
    </div>
</div>
