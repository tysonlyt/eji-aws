<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-ajax_action-field-container' ); ?>>

    <a class="button bf-action-button bf-button bf-main-button <?php echo esc_attr( $props['button-class'] ?? '' ); ?>"
       data-callback="<?php echo esc_attr( $props['callback'] ?? '' ); ?>"
       data-args="<?php echo esc_attr( \json_encode( $props['args'] ?? [] ) ); ?>"
       data-token="<?php echo esc_attr( $props['token'] ?? '' ); ?>"
       data-event="<?php echo esc_attr( $props['js-event'] ?? '' ); ?>"
       data-confirm="<?php echo esc_attr( $props['confirm'] ?? '' ); ?>"
    ><?php echo $props['button-name'] ?? ''; // escaped before ?></a>
</div>
