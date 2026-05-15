<?php

/**
 * @var string $input
 * @var array  $props
 * @var array  $options
 */

use BetterFrameworkPackage\Component\Control as LibRoot;

use \BetterFrameworkPackage\Component\Control\{
	Features\ProFeature
};

?>

<div class="bs-pro-feature <?php echo ! empty( $props['pro_feature']['selectable'] ) ? 'bs-pro-feature-selectable' : ''; ?>" <?php echo \BetterFrameworkPackage\Component\Control\Features\ProFeature::element_data_attributes( $props ); ?>>
    <div class="bs-pro-feature-icon">
		<?php \BetterFrameworkPackage\Component\Control\print_icon( 'dashicons-lock' ); ?>
    </div>

    <div class="bs-pro-feature-control">
		<?php echo $input; ?>
    </div>
</div>
