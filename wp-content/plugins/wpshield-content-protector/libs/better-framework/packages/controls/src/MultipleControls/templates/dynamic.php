<?php

use BetterFrameworkPackage\Component\Control;

use \BetterFrameworkPackage\Component\Control\{
	Features\ProFeature
};

/**
 * @var                                                                   $props array
 * @var \BetterStudio\Component\Control\MultipleControls\MultipleControls $this
 */

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-multiple-controls' ); ?>>

	<?php $this->render_items( $props ); ?>
</div>
