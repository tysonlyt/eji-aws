<?php

use BetterFrameworkPackage\Component\Control;

use \BetterFrameworkPackage\Component\Control\{
	Features\ProFeature
};

/**
 * @var $props array
 */

$array_value = [];

if ( isset( $props['value'] ) ) {

	$value = $props['value'];

	if ( is_string( $props['value'] ) ) {

		$value = explode( ',', $props['value'] );
	}

	$array_value = (array) $value;
}

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'bf-advanced-select', $props['status_class'] ?? '' ] ); ?>>
    <ul class="bf-advanced-select-group">

		<?php
        foreach ( $props['choices'] as [$option_id, $option] ) {

			$active_class = in_array( $option_id, $array_value, false ) ? ' active' : '';
			?>

            <li class="<?php echo esc_attr( $option['classes'] . $active_class ); ?>"
				<?php echo \BetterFrameworkPackage\Component\Control\Features\ProFeature::element_data_attributes( $option ); ?>
                data-value="<?php echo esc_attr( $option_id ); ?>"
                style="<?php echo esc_attr( $option['inline_styles'] ); ?>">

				<?php if ( ! empty( $option['icon'] ) ) { ?>
                    <span class="icon"><?php \BetterFrameworkPackage\Component\Control\print_icon( $option['icon']['icon'] ); ?></span>
				<?php } ?>

				<?php if ( ! empty( $option['label'] ) ) { ?>
                    <span class="label"><?php echo $option['label']; ?></span>
				<?php } ?>

				<?php if ( ! empty( $option['badge'] ) ) { ?>
                    <span class="badge <?php echo esc_attr( $option['badge']['classes'] ); ?>"
                          style="<?php echo esc_attr( $option['badge']['inline_styles'] ); ?>">
                    	<?php if ( ! empty( $option['badge']['icon'] ) ) { ?>
                            <span class="icon"><?php \BetterFrameworkPackage\Component\Control\print_icon( $option['badge']['icon']['icon'] ); ?></span>
	                    <?php } ?>
						<?php echo $option['badge']['label']; ?>
                    </span>
				<?php } ?>


				<?php if ( ! empty( $option['is_pro'] ) ) { ?>
					<?php \BetterFrameworkPackage\Component\Control\print_icon( 'dashicons-lock', 'lock-icon' ); ?>
				<?php } ?>
            </li>
		<?php } ?>
    </ul>

    <input type="hidden"
           name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           class="value <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"/>
</div>
