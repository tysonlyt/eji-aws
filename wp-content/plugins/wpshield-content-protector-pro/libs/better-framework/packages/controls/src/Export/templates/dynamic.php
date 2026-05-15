<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-export' ); ?>>

    <button type="button" class="button button-primary"
       data-file-name="<?php echo esc_attr( $props['file_name'] ?? '' ); ?>"
       data-panel-id="<?php echo esc_attr( $props['panel_id'] ?? '' ); ?>">
	    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-download' ); ?>
        <?php echo esc_html( $props['button_name'] ?? __( 'Download Backup', 'better-studio' ) ); ?>
    </button>
</div>
