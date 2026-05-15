<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$unique_id = $props['id'] ?? 'item-' . mt_rand();

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-radio' ); ?>>

	<?php
    foreach ( $props['choices'] ?? [] as [$key, $label] ) {

		$input_id = $unique_id . '-' . $key;
		?>
        <div class="bf-radio-button-option">

            <input type="radio" name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
                   id="<?php echo $input_id; ?>"
                   class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
                   value="<?php echo esc_attr( $key ); ?>" <?php echo $props['value'] === $key ? 'checked' : ''; ?>>
            <label for="<?php echo $input_id; ?>"><?php echo $label; ?></label>
        </div>
	<?php } ?>
</div>
