<?php

use BetterFrameworkPackage\Component\Control;

/**
 * @var array $props
 */

?>
<div <?php \BetterFrameworkPackage\Component\Control\container_attributes( $props, 'bf-editor-wrapper' ); ?>>

		<pre class="bf-editor" data-lang="<?php echo esc_attr( $props['lang'] ?? 'text' ); ?>"
             data-max-lines="<?php echo esc_attr( $props['max-lines'] ?? 15 ); ?>"
             data-min-lines="<?php echo esc_attr( $props['min-lines'] ?? 10 ); ?>"></pre>

    <textarea name="<?php echo esc_attr( $props['input_name'] ); ?>"
              class="bf-editor-field <?php echo esc_attr( $props['input_class'] ?? '' ); ?>"><?php echo $props['value'] ?? ''; // escaped before in function that passes value to this ?></textarea>
</div>
