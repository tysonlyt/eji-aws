<?php

/**
 * @var string $input
 * @var array  $props
 * @var array  $options
 */

use BetterFrameworkPackage\Component\Control as LibRoot;

use \BetterFrameworkPackage\Component\Control\{
	ModalConnector\ModalConnectorControl
};

$css_class = sprintf( '%s-app-connector', $props['modal']['service'] ?? '' );

?>

<div class="bs-modal-connector" <?php echo \BetterFrameworkPackage\Component\Control\ModalConnector\ModalConnectorControl::element_data_attributes( $props ); ?>>
    <div class="bs-modal-connector-accounts">
        <select class="<?php echo $css_class; ?>"
                name="<?php echo $props['id'] ?? ''; ?>"
                data-connector-name="<?php echo sprintf( '%s/accounts', $props['modal']['service'] ?? '' ); ?>"
                id="<?php echo $props['id'] ?? ''; ?>">
			<?php if ( ! empty( $props['options'] ) ) : ?>
				<?php foreach ( $props['options'] as $option ) : ?>
					<?php echo $option; ?>
				<?php endforeach; ?>
			<?php else : ?>
                <option value="0"><?php _e( '__Not Selected__', 'better-studio' ); ?></option>
			<?php endif; ?>
        </select>
    </div>
    <a class="bs-mc-insert-item button button-primary" href="#">
		<?php \BetterFrameworkPackage\Component\Control\print_icon( 'bsfi-plus' ); ?>
		<?php _e( $props['add_label'] ?? '', 'better-studio' ); ?>
    </a>
</div>
