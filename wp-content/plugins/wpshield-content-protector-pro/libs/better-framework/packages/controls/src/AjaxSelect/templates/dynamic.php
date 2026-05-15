<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var $props array
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-ajax_select-field-container' ); ?>>

    <div class="bf-ajax_select-input-container">
        <input type="text" class="bf-ajax-suggest-input" data-single="<?php echo esc_attr( $props['single'] ?? '' ); ?>"
               placeholder="<?php echo esc_attr( $props['placeholder'] ?? '' ); ?>"
        />
        <span class="bf-search-loader">
            <?php \BetterFrameworkPackage\Component\Control\print_icon( 'fa-search' ); ?>
		</span>

        <ul class="bf-ajax-suggest-search-results" style="display:none"></ul>
    </div>

    <input type="hidden" name="<?php echo $props['input_name']; ?>"
           class="<?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
           value="<?php echo esc_attr( $props['value'] ?? '' ); ?>"
           data-callback="<?php echo esc_attr( $props['callback'] ?? '' ); ?>"
           data-token="<?php echo esc_attr( $props['token'] ?? '' ); ?>"
    >

    <ul class="bf-ajax-suggest-controls">
		<?php foreach ( $props['values'] ?? [] as $value ) { ?>
            <li data-id="<?php echo esc_attr( $value['id'] ?? '' ); ?>">
                <?php echo $value['label'] ?? ''; ?>
                <span class="bf-icon del-icon del"></span>
            </li>
		<?php } ?>
    </ul>
</div>
