<?php

/**
 * @var array $props
 */
use BetterFrameworkPackage\Component\Control;

// default selected
$current = [
	'key'   => '',
	'label' => ! empty( $props['default_text'] ) ? wp_kses( $props['default_text'], \BetterFrameworkPackage\Component\Control\allowed_html() ) : esc_html__( 'chose one...', 'better-studio' ),
	'img'   => '',
];

if ( ! empty( $props['value'] ) ) {
	if ( isset( $props['options'][ $props['value'] ] ) ) {
		$current        = $props['options'][ $props['value'] ];
		$current['key'] = $props['value'];
	}
}

$input_name   = esc_attr( $props['input_name'] );
$select_style = empty( $props['select_style'] ) ? 'creative' : 'regular-select';

$container_attributes = [
    'data-heading' => $props['name'] ?? '',
];
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, [ 'better-' . $select_style . '-style', 'select-popup-field', 'bf-clearfix', $input_name ], $container_attributes ); ?>>

<?php
if ( $select_style === 'regular-select' ) {
	echo '<span class="active-item-label">' . $current['label'] . '</span>';
} else {
	?>
		<div class="select-popup-selected-image">
			<img src="<?php echo esc_attr( $current['current_img'] ?? $current['img'] ); ?>">
		</div>
		<div class="select-popup-selected-info">
			<div class="active-item-text"><?php echo $props['texts']['box_pre_title'] ?? _e( 'Active item', 'better-studio' ); ?></div>
			<div class="active-item-label"><?php echo $current['label']; ?></div>
			<a href="#" class="button button-primary"><?php echo $props['texts']['box_button'] ?? _e( 'Change', 'better-studio' ); ?></a>
		</div>
	<?php
}
?>

<?php if ( ! empty( $props['data2print'] ) ) { ?>
	<script id="<?php echo str_replace( '-', '_', sanitize_html_class( $input_name ) ); ?>" class="select-popup-data" type="application/json"><?php echo json_encode( $props['data2print'] ); ?></script>
	<?php
}

echo '<input type="hidden" name="' . $input_name . '" value="' . esc_attr( $current['key'] ) . '" class="select-value ' . esc_attr( $props['input_class'] ?? '' ) . '"/>';

echo '</div>';
