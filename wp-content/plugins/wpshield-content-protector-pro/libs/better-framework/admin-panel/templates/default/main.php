<?php
$template = $this->template;

$lang = bf_get_current_lang();

?>
<div class="panel-wrapper">
    <div id="bf-panel"
         class="bf-fields-style bf-panel panel-<?php echo esc_attr( $template['id'] ); ?> <?php echo isset( $template['css-class'] ) ? implode( ' ', $template['css-class'] ) : ''; ?>">

        <header class="bf-page-header">
            <div class="bf-page-header-topbar"><?php do_action( 'better-framework/admin-panel/' . $template['id'] . '/topbar/' ); ?></div>
			<?php

			$actions = apply_filters( 'better-framework/admin-panel/' . $template['id'] . '/actions/top/', '' );

			if ( ! empty( $actions ) ) {
				?>
                <div class="bf-page-actions-bar bf-page-actions-bar-top bf-clearfix">
                    <div class="bf-page-actions-bar-inner">
						<?php echo $actions; ?>
                    </div>
                </div>
			<?php } ?>
            <div class="bf-page-header-inner bf-clearfix">
                <div class="bf-page-header-content bf-clearfix">
					<?php if ( isset( $template['data']['panel-logo'] ) ) { ?>
                        <div class="bf-page-logo">
                            <img src="<?php echo esc_attr( $template['data']['panel-logo'] ); ?>"
                                 alt="<?php echo esc_attr( $template['data']['panel-name'] ); ?>"/>
                        </div>
					<?php } ?>

					<?php if ( isset( $template['data']['panel-pre-name'] ) ) { ?>
                        <h2 class="pre-title"><?php echo esc_html( $template['data']['panel-pre-name'] ); ?></h2>
					<?php } ?>

                    <h2 class="page-title"><?php echo esc_html( $template['data']['panel-name'] ); ?></h2>

					<?php
					if ( ! empty( $template['desc'] ) ) {
						echo '<div class="page-desc">' . wp_kses( $template['desc'], bf_trans_allowed_html() ) . '</div>';  // escaped before
					}
                    ?>

                    <div class="sticky-actions-bar">
                        <div class="bf-options-change-notice"><?php esc_html_e( 'Options Changed', 'better-studio' ); ?></div>
                        <a class="bf-save-button button button-primary button-large bf-main-button"
                           data-confirm="<?php echo $lang == 'all' ? esc_attr( $template['texts']['save-confirm-all'] ) : esc_attr( $template['texts']['save-confirm'] ); ?>">
							<?php echo bf_get_icon_tag( 'bsai-save-clean' ); ?><?php echo $lang == 'all' ? esc_html( $template['texts']['save-button-all'] ) : esc_html( $template['texts']['save-button'] ); ?>
                        </a>
                    </div>
                </div>

				<?php

				ob_start();

				?>
                <a class="bf-save-button button button-primary button-large bf-main-button"
                   data-confirm="<?php echo $lang == 'all' ? esc_attr( $template['texts']['save-confirm-all'] ) : esc_attr( $template['texts']['save-confirm'] ); ?>">
					<?php echo bf_get_icon_tag( 'bsai-save-clean' ); ?><?php echo $lang == 'all' ? esc_html( $template['texts']['save-button-all'] ) : esc_html( $template['texts']['save-button'] ); ?>
                </a>
                <a class="button button-large bf-reset-button button-light"
                   data-confirm="<?php echo $lang == 'all' ? esc_attr( $template['texts']['reset-confirm-all'] ) : esc_attr( $template['texts']['reset-confirm'] ); ?>">
					<?php echo $lang == 'all' ? esc_html( $template['texts']['reset-button-all'] ) : esc_html( $template['texts']['reset-button'] ); ?>
                </a>
                <div class="bf-options-change-notice"><?php esc_html_e( 'Options Changed', 'better-studio' ); ?></div>
                <input type="hidden" id="bf-panel-id" value="<?php echo esc_attr( $template['id'] ); ?>"/>
				<?php

				$actions = ob_get_clean();

				$actions = apply_filters( 'better-framework/admin-panel/' . $template['id'] . '/actions/bottom/', $actions );

				if ( ! empty( $actions ) ) {
					?>
                    <div class="bf-page-actions-bar bf-page-actions-bar-bottom bf-clearfix <?php echo strstr( $actions, 'bf-panel-navigation' ) ? 'bf-page-actions-bar-with-nav' : ''; ?>">
                        <div class="bf-page-actions-bar-inner">
							<?php echo $actions; ?>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </header>

        <div class="bf-centered-content">
		    <?php echo Better_Framework::admin_notices()->show_notice(); ?>
        </div>

        <div id="bf-main" class="bf-clearfix">
            <div id="bf-nav"><?php echo $template['tabs']; // escaped before in generating ?></div>

            <div id="bf-content">
                <form id="bf_options_form">
					<?php echo $template['fields']; // escaped before in generating ?>
                </form>
            </div>
        </div>
    </div>
