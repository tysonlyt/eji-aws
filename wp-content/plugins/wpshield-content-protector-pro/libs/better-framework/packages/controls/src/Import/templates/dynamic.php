<?php

/**
 * @var array $props
 */
use BetterFrameworkPackage\Component\Control;

?>

<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-import' ); ?>>

    <input type="file" data-panel_id="<?php echo esc_attr( esc_attr( $props['panel_id'] ) ?? '' ); ?>"
           data-token="<?php echo esc_attr( esc_attr( $props['token'] ) ?? '' ); ?>"
           name="import-file-input" class="import-file-input">

    <a class="import-upload-btn button button-primary bf-main-button">
	    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?>

        <?php echo esc_html( $props['button_name'] ?? __( 'Import', 'better-studio' ) ); ?>
    </a>
</div>
