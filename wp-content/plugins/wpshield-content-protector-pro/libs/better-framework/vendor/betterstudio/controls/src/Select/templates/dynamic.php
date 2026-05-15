<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var BetterStudio\Component\Control\Select\SelectControl $this
 * @var array                                               $props
 * @var array                                               $options
 */

$is_multiple = ! empty( $props['multiple'] );
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'bf-select-option-container', $is_multiple ? 'multiple' : '' ] ); ?>>

    <select name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
            class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
		<?php echo $is_multiple ? 'multiple' : ''; ?>
    >
		<?php $this->print_options( $props['options'] ?? [] ); ?>
    </select>
</div>
