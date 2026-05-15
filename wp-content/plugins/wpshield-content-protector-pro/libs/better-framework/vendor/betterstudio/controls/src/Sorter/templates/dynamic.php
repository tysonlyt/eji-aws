<?php

use function \BetterFrameworkPackage\Component\Control\{
	allowed_html,
	container_attributes
};

/**
 * @var array $props
 */
$value = isset( $props['value'] ) && is_array( $props['value'] ) ? $props['value'] : [];

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-sorter-groups-container' ); ?>>

    <input name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           value="<?php echo esc_attr( json_encode( $value ) ); ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>" type="hidden"/>

    <ul id="bf-sorter-group-<?php echo esc_attr( $props['id'] ?? '' ); ?>"
        class="bf-sorter-list bf-sorter-<?php echo esc_attr( $props['id'] ?? '' ); ?>">

		<?php

		if ( count( $value ) > 0 ) {

			foreach ( $value as $item ) {

				$item_id = $item['id'];
				$option  = $props['options'][ $item_id ] ?? $item;
				$label   = wp_kses( ( is_array( $option ) ? ( $option['label'] ?? ' - ' ) : $option ), \BetterFrameworkPackage\Component\Control\allowed_html() );
				?>
                <li id="bf-sorter-group-item-<?php echo esc_attr( $props['id'] ?? '' ); ?>-<?php echo esc_attr( $item_id ); ?>"
                    data-id="<?php echo esc_attr( $item_id ); ?>"
                    class="<?php echo esc_attr( $option['css-class'] ?? '' ); ?>"
                    style="<?php echo esc_attr( $option['css'] ?? '' ); ?>">
					<?php echo $label; // escaped before ?>
                </li>
<?php
            }
		} else {
			foreach ( ( $props['options'] ?? [] ) as $item_id => $item ) {

				$label = wp_kses( is_array( $item ) ? $item['label'] : $item, \BetterFrameworkPackage\Component\Control\allowed_html() );
				?>

                <li id="bf-sorter-group-item-<?php echo esc_attr( $props['id'] ?? '' ); ?>-<?php echo esc_attr( $item_id ); ?>"
                    data-id="<?php echo esc_attr( $item_id ); ?>"
                    class="<?php echo isset( $item['css-class'] ) ? esc_attr( $item['css-class'] ) : ''; ?>"
                    style="<?php echo isset( $item['css'] ) ? esc_attr( $item['css'] ) : ''; ?>">
					<?php echo $label; // escaped before ?>
                </li>
<?php
            }
		}
		?>
    </ul>
</div>

