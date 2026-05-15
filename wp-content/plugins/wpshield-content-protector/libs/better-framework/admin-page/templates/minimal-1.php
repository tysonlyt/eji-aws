<?php

/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */

$template = $this->template;

$args = $this->args;

?>
<div class="panel-wrapper">
	<div class="bf-fields-style bf-admin-page-wrap panel-<?php echo esc_attr( $template['id'] ?? '' ); ?> <?php echo isset( $template['css-class'] ) ? implode( ' ', $template['css-class'] ) : ''; ?>">

		<header class="bf-page-header">
			<div class="bf-page-header-topbar"><?php do_action( 'better-framework/admin-panel/' . $args['id'] . '/topbar/' ); ?></div>
			<?php

			$actions = apply_filters( 'better-framework/admin-panel/' . $args['id'] . '/actions/top/', '' );

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
					<?php if ( isset( $args['panel-logo'] ) ) { ?>
						<div class="bf-page-logo">
							<img src="<?php echo esc_attr( $args['panel-logo'] ); ?>"
							     alt="<?php echo esc_attr( $args['panel-name'] ); ?>"/>
						</div>
					<?php } ?>

					<?php if ( isset( $args['panel-pre-name'] ) ) { ?>
						<h2 class="pre-title"><?php echo esc_html( $args['panel-pre-name'] ); ?></h2>
					<?php } ?>

					<h2 class="page-title"><?php echo esc_html( $args['panel-name'] ); ?></h2>

					<?php
					if ( ! empty( $args['panel-desc'] ) ) {
						echo '<div class="page-desc">' . wp_kses( $args['panel-desc'], bf_trans_allowed_html() ) . '</div>';  // escaped before
					}
                    ?>
				</div>
			</div>
			<?php

			$actions = apply_filters( 'better-framework/admin-panel/' . $args['id'] . '/actions/bottom/', '' );

			if ( ! empty( $actions ) ) {
				?>
				<div class="bf-page-actions-bar bf-page-actions-bar-bottom bf-clearfix">
					<div class="bf-page-actions-bar-inner">
						<?php echo $actions; ?>
					</div>
				</div>
			<?php } ?>

		</header>

        <div class="bf-centered-content">
	        <?php echo Better_Framework::admin_notices()->show_notice(); ?>
        </div>

		<div class="bf-page-postbox">

            <div class="inside">
				<?php echo $body; // escaped before ?>
			</div>
		</div>

	</div>
</div>
