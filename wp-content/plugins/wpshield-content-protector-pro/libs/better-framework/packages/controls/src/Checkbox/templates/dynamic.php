<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$input_name = esc_attr( $props['input_name'] ?? '' );

$value       = array_filter( ! empty( $props['value'] ) ? $props['value'] : [] );
$id          = $props['id'] ?? 'item-' . mt_rand();
$input_class = esc_attr( $props['input_class'] ?? '' );
?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-checkbox' ); ?>>

	<?php
	foreach ( $props['choices'] ?? [] as [$key, $label] ) {
		$option_id = $id . '-' . $key;
		$key_attr  = esc_attr( $key );
		?>

        <div class="bs-control-checkbox-option">
            <input type="checkbox" name="<?php echo $input_name; ?>[<?php echo $key_attr; ?>]"
                   value="<?php echo $key_attr; ?>" data-key="<?php echo $key_attr; ?>"
                   id="<?php echo $option_id; ?>"
                   class="<?php echo $input_class; ?>"
				<?php echo ! empty( $value[ $key ] ) ? 'checked' : ''; ?>>
            <label for="<?php echo $option_id; ?>"><?php echo $label; ?></label>
        </div>
	<?php } ?>
</div>
