<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */
$style = ! empty( $props['layout'] ) ? $props['layout'] : 'style-1';

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'bf-section-heading', 'bf-clearfix', $style ] ); ?>>
    <div class="bf-section-heading-title bf-clearfix">
		<h3>
			<?php if ( ! empty( $props['icon'] ) ) { ?>
				<?php \BetterFrameworkPackage\Component\Control\print_icon( $props['icon'] ); ?>
			<?php } ?>
			<?php echo esc_html( $props['name'] ); ?>
		</h3>
	</div>
	<?php if ( ! empty( $props['desc'] ) ) { ?>
		<div class="bf-section-heading-desc bf-clearfix"><?php echo $props['desc']; ?></div>
	<?php } ?>
</div>
