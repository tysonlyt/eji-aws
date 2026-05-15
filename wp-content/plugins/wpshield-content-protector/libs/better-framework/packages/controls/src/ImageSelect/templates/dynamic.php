<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array                                                           $props
 * @var \BetterFrameworkPackage\Component\Control\ImageSelect\ImageSelectControl $this
 */
$selected   = $this->find_choice( $props['choices'] ?? [], $props['value'] );
$input_name = esc_attr( $props['input_name'] );
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'better-select-image', $input_name ] ); ?>>

    <div class="select-options">
        <span class="selected-option"><?php echo $selected['label'] ?? $props['default_text'] ?? __( 'chose one...', 'better-studio' ); ?></span>
        <div class="better-select-image-options">
            <ul class="options-list <?php echo esc_attr( $props['list_style'] ?? 'grid-2-column' ); ?> bf-clearfix">
				<?php
                foreach ( $props['choices'] ?? [] as [$key, $option] ) {
					echo '<li data-value="' . esc_attr( $key ) . '" data-label="' . esc_attr( $option['label'] ) . '" class="image-select-option ' . ( $key === $props['value'] ? 'selected' : '' ) . '">
        <img src="' . esc_attr( $option['img'] ) . '" alt="' . esc_attr( $option['label'] ) . '"/><p>' . $option['label'] . '</p>
    </li>';
				}
                ?>
            </ul>
        </div>
    </div>
    <input type="hidden" name="<?php echo $input_name; ?>" id="<?php echo $input_name; ?>"
           value="<?php echo esc_attr( $props['value'] ); ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"/>
</div>
