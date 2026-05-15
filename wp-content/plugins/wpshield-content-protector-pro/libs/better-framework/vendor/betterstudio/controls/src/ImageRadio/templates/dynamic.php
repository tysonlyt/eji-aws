<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */


$value = $props['value'] ?? '';

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-image-radio' ); ?>>

    <input type="hidden" name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( $value ); ?>"
    >

	<?php
    foreach ( $props['choices'] ?? [] as [$key, $item] ) {
		$is_checked = $key === $value;
		?>

        <div class="bf-image-radio-option <?php echo $is_checked ? 'checked' : ''; ?>"
             data-id="<?php echo esc_attr( $key ); ?>">

            <label>
                <img src="<?php echo esc_url( $item['img'] ?? '' ); ?>"
                     alt="<?php echo esc_attr( $item['label'] ?? '' ); ?>"
                     class="<?php echo esc_attr( $item['class'] ?? '' ); ?>"
                >
				<?php if ( isset( $item['label'] ) ) { ?>
                    <p class="item-label"><?php echo $item['label'] ?? ''; ?></p>
				<?php } ?>
            </label>
        </div>
	<?php } ?>
</div>
