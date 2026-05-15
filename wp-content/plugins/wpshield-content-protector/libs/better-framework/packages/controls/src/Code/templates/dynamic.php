<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array                                           $props
 * @var BetterStudio\Component\Control\Code\CodeControl $this
 */

$language_attr = $this->language_attr( $props['language'] ?? '' );
?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bs-control-code' ); ?>>

<textarea class="bf-code-editor <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"
          data-lang="<?php echo $language_attr; ?>"
          name="<?php echo esc_attr( $props['input_name'] ?? '' ); ?>"
          placeholder="<?php echo esc_attr( $props['placeholder'] ?? '' ); ?>"
          data-line-numbers="<?php echo ! empty( $props['line_numbers'] ) ? 'enable' : 'disable'; ?>"
          data-auto-close-brackets="<?php echo ! empty( $props['auto_close_brackets'] ) ? 'enable' : 'disable'; ?>"
          data-auto-close-tags="<?php echo ! empty( $props['auto_close_tags'] ) ? 'enable' : 'disable'; ?>"
><?php echo esc_textarea( $props['value'] ?? '' ); ?></textarea>
</div>
