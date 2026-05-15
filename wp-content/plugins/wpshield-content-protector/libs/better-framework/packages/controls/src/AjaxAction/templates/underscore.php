<?php // phpcs:ignoreFile ?>
<div {{{ utils.container_attributes(props, "bf-ajax_action-field-container") }}}>
    <a class="button bf-action-button bf-button bf-main-button {{ props['button-class'] }}"
       data-callback="{{ props.callback }}"
       data-token="{{ props.token }}"
       data-event="{{ props['js-event'] }} "
       data-confirm="{{ props.confirm }}"
    >{{{ props['button-name'] }}}</a>
</div>
