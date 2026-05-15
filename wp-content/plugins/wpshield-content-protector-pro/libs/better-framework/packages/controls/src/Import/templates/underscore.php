<?php
    use BetterFrameworkPackage\Component\Control;
?>
<div {{{ utils.container_attributes(props, "bs-control-import") }}}>
    <input type="file" name="import-file-input" class="import-file-input">

    <a class="import-upload-btn button button-primary bf-main-button"
       data-panel_id="{{ data.panel_id }}" data-token="{{ data.token }}">
	    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-upload' ); ?>
        {{{ data.button_name || '<?php esc_attr_e( 'Import', 'better-studio' ); ?>' }}}
    </a>
</div>
