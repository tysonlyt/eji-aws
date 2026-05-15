<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array                                                   $props
 * @var array                                                   $options
 * @var BetterStudio\Component\Control\Repeater\RepeaterControl $this
 */


if ( empty( $props['options'] ) ) {
	$props['options'] = [];
}

if ( empty( $props['value'] ) ) {

	$props['value'] = [
		array_fill_keys( array_keys( $props['options'] ), '' ),
	];
}

$is_close = isset( $props['state'] ) && 'close' === $props['state'];

if ( empty( $props['input_name'] ) ) {

	if ( ! empty( $props['id'] ) ) {

		$name_format = sprintf( '%s[{{iteration}}][{{control_id}}]', $props['id'] );

	} else {

		$name_format = '{{iteration}}[{{control_id}}]';
	}
} else {

	$name_format = $props['input_name'];
}

?>


<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-repeater' ); ?>>

    <!-- Repeater Container -->
    <div class="bf-repeater-items-container bf-clearfix">
		<?php

		$iteration = 0;
		foreach ( $props['value'] ?? [] as $saved_key => $saved_val ) {

			$current_format = strtr( $name_format, [ '{{iteration}}' => $iteration ] );

			?>
            <!-- Repeater Item -->
            <div class="bf-repeater-item">
                <div class="bf-repeater-item-title">
                    <h5>
                        <span class="handle-repeater-title-label"><?php echo $props['item_title'] ?? __( 'Item', 'better-studio' ); ?></span>
                        <span class="handle-repeater-item<?php echo $is_close ? ' closed' : ''; ?>"></span>
                        <span class="bf-remove-repeater-item-btn">
                        <?php echo $props['delete_label'] ?? __( 'Delete', 'better-studio' ); ?>
                </span>
                    </h5>
                </div>
                <div class="repeater-item-container bf-clearfix"<?php echo $is_close ? ' style="display:none"' : ''; ?>>
					<?php $this->render_items( $props['options'], $current_format, $props['input_class'] ?? '', $saved_val, $options ?? [] ); ?>
                </div>
            </div>
			<?php
			$iteration ++;
		}
        ?>

    </div>

    <div style="display: none">
        <noscript type="text/html" class="repeater-item-tmpl">
            <!-- Repeater Item -->
            <div class="bf-repeater-item">
                <div class="bf-repeater-item-title">
                    <h5>
                        <span class="handle-repeater-title-label"><?php echo $props['item_title'] ?? __( 'Item', 'better-studio' ); ?></span>
                        <span class="handle-repeater-item"></span>
                        <span class="bf-remove-repeater-item-btn">
                    <?php echo $props['delete_label'] ?? __( 'Delete', 'better-studio' ); ?>
                </span>
                    </h5>
                </div>
                <div class="repeater-item-container bf-clearfix">
					<?php

					$default = current( $props['default'] ?? [] );
					$values  = [];

					$this->render_items( $props['options'], $name_format, $props['input_class'] ?? '', $values, $options ?? [] );
					?>
                </div>
            </div>
        </noscript>
    </div>

    <button class="bf-clone-repeater-item button button-primary bf-button bf-main-button<?php echo isset( $props['widget_field'] ) ? ' bf-widget-clone-repeater-item bf-button bf-main-button' : ''; ?>"
            type="button">
		<?php echo $props['add_label'] ?? __( 'Add', 'better-studio' ); ?>
    </button>
</div>
