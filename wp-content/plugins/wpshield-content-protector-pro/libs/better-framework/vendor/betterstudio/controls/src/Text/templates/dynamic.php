<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

$input_class = '';
if ( ! empty( $props['rtl'] ) ) {

	$input_class .= ' rtl';
}

if ( ! empty( $props['ltr'] ) ) {

	$input_class .= ' ltr';
}

if ( ! empty( $props['special-chars'] ) ) {

	$props['value'] = htmlspecialchars_decode( $props['value'] ?? '' );
}


$wrapper_classes = [ 'bs-control-text', $input_class ];
if ( ! empty( $props['prefix'] ) ) {
	$wrapper_classes[] = 'bf-field-with-prefix';
}
if ( ! empty( $props['suffix'] ) ) {
	$wrapper_classes[] = 'bf-field-with-suffix';
}

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, $wrapper_classes ); ?>>

	<?php

	if ( ! empty( $props['prefix'] ) ) {
		echo "<span class='bf-prefix-suffix'>{$props['prefix']}</span>";
	}
	?>
	<input type="<?php echo esc_attr( $props['input_type'] ?? 'text' ); ?>" name="<?php echo esc_attr( $props['input_name'] ); ?>"
		   class="<?php echo esc_attr( $props['input_class'] ?? '' ), $input_class; ?>"
		   placeholder="<?php echo esc_attr( $props['placeholder'] ?? '' ); ?>"
		   value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"
	>

	<?php

	if ( ! empty( $props['suffix'] ) ) {
		echo "<span class='bf-prefix-suffix'>{$props['suffix']}</span>";
	}
	?>

</div>
