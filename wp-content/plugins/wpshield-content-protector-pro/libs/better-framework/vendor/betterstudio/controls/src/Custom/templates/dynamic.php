<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-custom-field-wrapper' ); ?>>

	<?php

	if ( ! empty( $props['callback'] ) ) {

		if ( ! empty( $props['deferred'] ) ) {

			printf(
				'<div class="bf-custom-field-view" data-callback="%s" data-callback-args="%s" data-token="%s">Loading...</div>',
				esc_attr( $props['callback'] ),
				esc_attr( json_encode( $props['callback_args'] ?? [] ) ),
				esc_attr( $props['token'] ?? '' )
			);
		} else {
			echo call_user_func_array( $props['callback'], $props['callback_args'] ?? [] );
		}
	} elseif ( isset( $props['input'] ) ) {

		echo $props['input'];  // escaped before
	}

	if ( isset( $props['js-code'] ) ) {

		echo '<script>', $props['js-code'], '</script>';
	}

	if ( isset( $props['css-code'] ) ) {

		echo '<style>', $props['css-code'], '</style>';
	}
	?>


	<?php
	$value = $props['value'] ?? '';
	?>
    <input type="hidden" class="bf-custom-field-values <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( is_array( $value ) || is_object( $value ) ? wp_json_encode( $value ) : $value ); ?>"/>
</div>
