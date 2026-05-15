<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$values = [];

if ( isset( $props['std'] ) ) {

	$values = (array) $props['std'];

} elseif ( isset( $props['value'] ) ) {

	$values = (array) $props['value'];
}
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-image-preview' ); ?>>

    <div class="info-value <?php echo esc_attr( $props['class'] ?? '' ); ?>"
         style="text-align: <?php echo esc_attr( $props['align'] ?? 'center' ); ?>;">

		<?php foreach ( $values as $key => $image ) { ?>
            <img class="image-<?php echo $key; ?>" src="<?php echo esc_url( $image ); ?>">
		<?php } ?>
    </div>
</div>
