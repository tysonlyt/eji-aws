<?php

use BetterFrameworkPackage\Component\Control as LibRoot;

/**
 * @var array $configs
 */
?>
<script type="text/html" id="tmpl-bs-modal-connector">

	<div class="bs-modal-connector-modal bs-modal-state-lock have-video">

		<a href="#" class="bs-close-modal"></a>

        <div class="bs-modal-connector-header">
            <h4 class="label">{{ title }}</h4>
        </div>

		<div class="bs-modal-connector-content">
            {{{body}}}
		</div>

		<div class="bs-modal-connector-footer">
            <a data-service="{{ serviceID }}"
               data-callback="{{ jsCallback }}"
               data-connector-element="{{ connector }}"
               class="button button-primary call-to-action-connector"
               href="#"
               target="_blank">{{ submit_text }}</a>
		</div>
	</div>
</script>

<?php
foreach ( $configs as $config ) {
	$config['template']['modal_id'] = $config['id']; // for template usage
	?>
	<script type="application/json" id="bs-modal-connector-<?php echo esc_attr( $config['id'] ); ?>"
	><?php echo json_encode( $config ); ?></script>
<?php } ?>
