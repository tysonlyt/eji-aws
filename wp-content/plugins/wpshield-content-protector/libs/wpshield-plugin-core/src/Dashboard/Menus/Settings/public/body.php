<?php //phpcs:disable

?>
<div class="plugin-core-content">

    <div class="products-category">
        <strong class="category-title"><?php _e( 'Active Plugins', 'wpshield' ); ?></strong>
    </div>

    <div class="products-list products-list-3 products-active">

		<?php if ( ! empty( $plugins['actives'] ) ): ?>
			<?php foreach ( $plugins['actives'] as $plugin ): ?>
                <div class="product-item"
                     style="<?php echo esc_attr( isset( $plugin['color'] ) ? '--bf-primary-color:' . $plugin['color'] : '' ); ?>">

                    <div class="product-header">
                        <div class="product-thumbnail">
                            <img src="<?php echo esc_attr( $plugin['thumbnail'] ?? '' ); ?>" alt="">
                        </div>

                        <h6 class="product-title">
							<?php echo esc_html( $plugin['name'] ?? '' ) ?>
                            <span class="product-version"><?php echo esc_html( $plugin['version'] ?? '' ) ?></span>
                        </h6>
                    </div>

                    <div class="product-banner">
                        <img src="<?php echo esc_attr( $plugin['banner'] ?? '' ); ?>" alt="">
                    </div>

                    <p class="product-desc"><?php echo esc_html( $plugin['description'] ?? '' ); ?></p>

                    <div class="product-buttons">
                        <a class="product-button product-button-primary product-button-setting-plugin"
                           href="<?php echo esc_attr(
							   add_query_arg(
								   [
									   'page' => sprintf( 'wpshield/%s', $plugin['slug'] ?? '' ),
								   ],
								   sprintf( '%sadmin.php', admin_url() )
							   )
						   ); ?>">
							<?php echo bf_get_icon_tag( 'bsai-admin-settings' ); ?>
							<?php echo esc_html(
								! empty( $plugin['setting_label'] ) ?
									$plugin['setting_label'] : __( 'Setting Panel', 'wpshield' )
							); ?>
                        </a>

						<?php if ( isset( $plugin['new_version'] ) ): ?>
                            <a data-slug="<?php echo esc_attr( $plugin['slug'] ?? '' ) ?>"
                               class="product-button product-button-secondary product-button-update"
                               href="<?php echo esc_attr( $plugin['update_link'] ?? '' ) ?>">
								<?php echo bf_get_icon_tag( 'bsai-refresh' ); ?>
								<?php echo esc_html(
									! empty( $plugin['setting_label'] ) ?
										$plugin['setting_label'] : __( 'Update Now', 'wpshield' )
								); ?>
                            </a>
						<?php endif; ?>
                    </div>
                </div>
			<?php endforeach; ?>
		<?php endif; ?>
    </div>

    <div class="products-category">
        <strong class="category-title"><?php _e( 'Our Related Security Plugins', 'wpshield' ); ?></strong>
    </div>

    <div class="products-list products-list-3 products-related">
		<?php if ( ! empty( $plugins['related_plugins'] ) ): ?>
			<?php foreach ( $plugins['related_plugins'] as $plugin ): ?>
                <div class="product-item"
                     style="<?php echo esc_attr( isset( $plugin['color'] ) ? '--bf-primary-color:' . $plugin['color'] : '' ); ?>">

                    <div class="product-header">
                        <div class="product-thumbnail">
                            <img src="<?php echo esc_attr( $plugin['thumbnail'] ?? '' ); ?>" alt="">
                        </div>

                        <h6 class="product-title">
							<?php echo esc_html( $plugin['name'] ?? '' ); ?>
                        </h6>
                    </div>

                    <div class="product-banner">
                        <img src="<?php echo esc_attr( $plugin['banner'] ?? '' ); ?>" alt="">
                    </div>

                    <p class="product-desc"><?php echo esc_html( $plugin['description'] ?? '' ); ?></p>

                    <div class="product-buttons">

						<?php $is_released = ! empty( $plugin['state'] ) && 'coming-soon' !== $plugin['state']; ?>

                        <a class="product-button product-button-secondary <?php echo $is_released ? 'product-button-install' : 'product-button-disable' ?>"
                           style="<?php echo $is_released ? sprintf( 'color:#fff;--btn-bg-color:%s;', $plugin['color'] ) : '' ?>"
                           target="<?php echo esc_attr( isset( $plugin['is_premium'] ) && $is_released && $plugin['is_premium'] ? '_blank' : '_self' ) ?>"
                           href="<?php echo esc_attr( isset( $plugin['is_premium'] ) && $is_released && $plugin['is_premium'] ? 'https://getwpshield.com/account' : '#' ); ?>"
                           data-slug="<?php echo esc_attr( $plugin['slug'] ?? '' ) ?>">
							<?php if ( isset( $plugin['is_premium'] ) && $is_released && $plugin['is_premium'] ): ?>
								<?php esc_html_e( 'Download', 'wpshield' ); ?>
							<?php elseif ( $is_released ): ?>
								<?php esc_html_e( 'Free Install', 'wpshield' ); ?>
							<?php else: ?>
								<?php esc_html_e( 'Coming Soon...', 'wpshield' ); ?>
							<?php endif; ?>
                        </a>
                    </div>
                </div>
			<?php endforeach; ?>
		<?php endif; ?>
    </div>
</div>
