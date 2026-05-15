<?php

use BetterFrameworkPackage\Component\Control;

use \BetterFrameworkPackage\Component\Control\IconSelect\{
	Helpers,
	BsIcons,
	Fontawesome
};

// Default selected
$current = [
	'key'       => '',
	'title'     => __( 'Chose an Icon', 'better-studio' ),
	'width'     => '',
	'height'    => '',
	'type'      => '',
	'font_code' => '',
];

if ( isset( $props['value'] ) ) {

	if ( is_array( $props['value'] ) ) {

		if ( ! isset( $props['value']['icon'] ) ) {

			$props['value']['icon'] = '';
		}
		if ( ! isset( $props['value']['type'] ) ) {

			$props['value']['type'] = '';
		}

		$current['font_code'] = $props['value']['font_code'] ?? '';

		if ( in_array( $props['value']['type'], [ 'custom-icon', 'custom' ] ) ) {
			$current['key']    = $props['value']['icon'];
			$current['title']  = \BetterFrameworkPackage\Component\Control\IconSelect\Helpers::icon( $props['value'] ?? '' ) . ' ' . __( 'Custom icon', 'better-studio' );
			$current['width']  = $props['value']['width'] ?? '';
			$current['height'] = $props['value']['height'] ?? '';
			$current['type']   = 'custom-icon';
		} else {

			// Fontawesome icon
			if ( substr( $props['value']['icon'], 0, 3 ) == 'fa-' ) {

				$fontawesome = new \BetterFrameworkPackage\Component\Control\IconSelect\Fontawesome();

				if ( isset( $fontawesome->icons[ $props['value']['icon'] ] ) ) {
					$current['key']    = $props['value']['icon'];
					$current['title']  = \BetterFrameworkPackage\Component\Control\IconSelect\Helpers::icon( $props['value'] ) . $fontawesome->icons[ $props['value']['icon'] ]['label'];
					$current['width']  = ! empty( $props['value']['width'] ) ? $props['value']['width'] : '';
					$current['height'] = ! empty( $props['value']['height'] ) ? $props['value']['height'] : '';
					$current['type']   = 'fontawesome';
				}
			} // BetterStudio Font Icon
            elseif ( substr( $props['value']['icon'], 0, 5 ) == 'bsfi-' ) {

				$bs_icons = new \BetterFrameworkPackage\Component\Control\IconSelect\BsIcons();

				if ( isset( $bs_icons->icons[ $props['value']['icon'] ] ) ) {
					$current['key']    = $props['value']['icon'];
					$current['title']  = \BetterFrameworkPackage\Component\Control\IconSelect\Helpers::icon( $props['value'] ) . $bs_icons->icons[ $props['value']['icon'] ]['label'];
					$current['width']  = ! empty( $props['value']['width'] ) ? $props['value']['width'] : '';
					$current['height'] = ! empty( $props['value']['height'] ) ? $props['value']['height'] : '';
					$current['type']   = 'bs-icons';
				}
			}
		}
	} elseif ( ! empty( $props['value'] ) ) {

		// Fontawesome icon
		if ( substr( $props['value'], 0, 3 ) == 'fa-' ) {

			$fontawesome = new \BetterFrameworkPackage\Component\Control\IconSelect\Fontawesome();

			if ( isset( $fontawesome->icons[ $props['value'] ] ) ) {
				$current['key']    = $props['value'];
				$current['title']  = \BetterFrameworkPackage\Component\Control\IconSelect\Helpers::icon( $props['value'] ) . $fontawesome->icons[ $props['value'] ]['label'];
				$current['width']  = '';
				$current['height'] = '';
				$current['type']   = 'fontawesome';
			}
		} // BetterStudio Font Icon
        elseif ( substr( $props['value'], 0, 5 ) == 'bsfi-' ) {

			$bs_icons = new \BetterFrameworkPackage\Component\Control\IconSelect\BsIcons();

			if ( isset( $bs_icons->icons[ $props['value'] ] ) ) {
				$current['key']    = $props['value'];
				$current['title']  = \BetterFrameworkPackage\Component\Control\IconSelect\Helpers::icon( $props['value'] ) . $bs_icons->icons[ $props['value'] ]['label'];
				$current['width']  = ! empty( $props['value']['width'] ) ? $props['value']['width'] : '';
				$current['height'] = ! empty( $props['value']['height'] ) ? $props['value']['height'] : '';
				$current['type']   = 'bs-icons';
			}
		}
	}
}

$input_class = esc_attr( $props['input_class'] ?? '' );

$print_value = isset( $props['value'] ) && is_array( $props['value'] ) ? $props['value'] : [];
unset( $print_value['icon_tag'] );

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-icon-modal-handler', [ 'id' => 'bf-icon-modal-handler-' . mt_rand() ] ); ?>>

    <div class="select-options">
        <span class="selected-option"><?php echo $current['title']; // escaped before in function that passes value to this ?></span>
    </div>

    <input type="hidden" class="icon-input <?php echo $input_class; // escaped before ?>"
           name="<?php echo esc_attr( $props['input_name'] ); ?>"
           value="<?php echo esc_attr( json_encode( $print_value ) ); ?>"/>
</div><!-- modal handler container -->
