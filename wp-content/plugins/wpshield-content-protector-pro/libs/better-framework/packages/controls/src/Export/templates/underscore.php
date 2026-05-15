<?php
    use BetterFrameworkPackage\Component\Control;
?>
<div {{{ utils.container_attributes(props, "bs-control-export") }}}>
    <button type="button" class="button button-primary"
       data-file-name="{props.file_name}}>"
       data-panel-id="{{props.panel_id}}">
	    <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-download' ); ?>
        {{ props.button_name || '<?php esc_html_e( 'Download Backup', 'better-studio' ); ?>' }}
    </button>
</div>
