<?php

/**
 * @var $props array
 */

use BetterFrameworkPackage\Component\Control;

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-term-select-field' ); ?>>

	<div class="bf-field-term-select-wrapper bf-field-term-select-deferred"
	     data-taxonomy="<?php echo $props['taxonomy'] ?? 'category'; ?>"
	>
	</div>
	<div class="bf-field-term-select-help">
		<label><?php _e( 'Help: Click on check box to', 'better-studio' ); ?></label>
		<br>
		<div class="bf-checkbox-multi-state disabled none-state">
			<span data-state="none"></span>
		</div>
		<label><?php _e( 'Not Selected', 'better-studio' ); ?></label>
		<br>
		<div class="bf-checkbox-multi-state disabled active-state">
        <span class="bf-checkbox-active" style="display: inline-block;">
          <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-check' ); ?>
        </span>
		</div>
		<label><?php _e( 'Selected', 'better-studio' ); ?></label>
		<br>
		<div class="bf-checkbox-multi-state disabled deactivate-state">
        <span class="bf-checkbox-active" style="display: inline-block;">
          <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-times' ); ?>
        </span>
		</div>
		<label><?php _e( 'Excluded', 'better-studio' ); ?></label>
	</div>

	<input type="hidden"
	       name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
	       value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"
	       class="bf-term-select-value <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
	>
</div>
