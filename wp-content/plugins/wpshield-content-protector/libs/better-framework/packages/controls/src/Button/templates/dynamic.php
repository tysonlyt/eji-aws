<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-button-field-container' ); ?>>

	<a class="button button-primary bf-button bf-main-button <?php echo esc_attr( $props['class-name'] ?? '' ); ?>"
		<?php foreach ( $props['custom-attrs'] ?? [] as $key => $name ) { ?>
			<?php printf( '%s="%s"', sanitize_key( $key ), esc_attr( $name ) ); ?>
		<?php } ?>
	><?php echo $props['button_name'] ?? $props['name'] ?? ''; // escaped before ?></a>
</div>
