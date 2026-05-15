<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var $props array
 */


$value       = isset( $props['value'] ) && is_array( $props['value'] ) ? $props['value'] : [];
$check_all   = ( ! isset( $props['check_all'] ) || $props['check_all'] ) && ! count( $value );
$input_class = esc_attr( $props['input_class'] ?? '' );
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-sorter-groups-container' ); ?>>

    <input value="<?php echo esc_attr( json_encode( $value ) ); ?>"
           name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>" type="hidden"/>

    <ul id="bf-sorter-group-<?php echo esc_attr( $props['id'] ?? '' ); ?>"
        class="bf-sorter-list bf-sorter-<?php echo esc_attr( $props['id'] ?? '' ); ?>">
		<?php
		// Options That Saved Before
		foreach ( $value as $_item_id => $item ) {
			if ( ! $item ) {
				continue;
			}

			$item_id = $item['id'] ?? $_item_id;

			if ( ! isset( $props['options'][ $item_id ] ) ) {
				continue;
			}
			?>
            <li id="bf-sorter-group-item-<?php echo esc_attr( $props['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>"
                class="<?php echo isset( $props['options'][ $item_id ]['css-class'] ) ? esc_attr( $props['options'][ $item_id ]['css-class'] ) : ''; ?> item-<?php echo esc_attr( $item_id ); ?> checked-item"
                style="<?php echo isset( $props['options'][ $item_id ]['css'] ) ? esc_attr( $props['options'][ $item_id ]['css'] ) : ''; ?>">
                <label>
                    <input class="<?php echo $input_class; // escaped before ?>"
                           data-id="<?php echo esc_attr( $item_id ); ?>"
                           value="1" type="checkbox" <?php echo ! empty( $item['active'] ) ? 'checked' : ''; ?>/>
					<?php echo $props['options'][ $item_id ]['label']; // escaped before ?>
                </label>
            </li>
			<?php

			unset( $props['options'][ $item_id ] );
		}

		// Options That Not Saved but are Active
		foreach ( $props['options'] ?? [] as $item_id => $item ) {

			// Skip Disabled Items
			if ( isset( $item['css-class'] ) && strpos( $item['css-class'], 'active-item' ) === false ) {
				continue;
			}
			?>
            <li id="bf-sorter-group-item-<?php echo esc_attr( $props['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>"
                class="<?php echo isset( $item['css-class'] ) ? esc_attr( $item['css-class'] ) : ''; ?> item-<?php echo esc_attr( $item_id ); ?> <?php echo $check_all ? ' checked-item' : ''; ?>" <?php echo $check_all ? ' checked="checked" ' : ''; ?>
                style="<?php echo isset( $item['css'] ) ? esc_attr( $item['css'] ) : ''; ?>">
                <label>
                    <input class="<?php echo $input_class; // escaped before ?>"
                           data-id="<?php echo esc_attr( $item_id ); ?>"
                           value="1" type="checkbox" <?php echo $check_all ? ' checked="checked" ' : ''; ?>/>
					<?php echo is_array( $item ) ? $item['label'] : $item; // escaped before ?>
                </label>
            </li>
			<?php

			unset( $props['options'][ $item_id ] );
		}

		// Disable Items
		foreach ( $props['options'] ?? [] as $item_id => $item ) {
			?>
            <li id="bf-sorter-group-item-<?php echo esc_attr( $props['id'] ); ?>-<?php echo esc_attr( $item_id ); ?>"
                class="<?php echo isset( $item['css-class'] ) ? esc_attr( $item['css-class'] ) : ''; ?> item-<?php echo esc_attr( $item_id ); ?>"
                style="<?php echo isset( $item['css'] ) ? esc_attr( $item['css'] ) : ''; ?>">
                <label>
                    <input class="<?php echo $input_class; // escaped before ?>"
                           data-id="<?php echo esc_attr( $item_id ); ?>"
                           value="0" type="checkbox" disabled/>
					<?php echo is_array( $item ) ? $item['label'] : $item; // escaped before ?>
                </label>
            </li>
<?php
		}
		?>
    </ul>
</div>
