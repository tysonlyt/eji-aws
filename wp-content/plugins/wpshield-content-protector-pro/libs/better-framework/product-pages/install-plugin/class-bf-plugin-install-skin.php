<?php

if ( ! class_exists( 'Plugin_Installer_Skin' ) ) {

	require ABSPATH . '/wp-admin/includes/class-wp-upgrader-skins.php';
}


class BF_Plugin_Install_Skin extends Plugin_Installer_Skin {


	public function __construct( $args = [] ) {

		//phpcs:ignore
		$args['url'] = add_query_arg( $_GET );

		parent::__construct( $args );
	}

	/**
	 * @param string $feedback Message data.
	 * @param mixed  ...$args  Optional text replacements.
	 *
	 * @since 2.8.0
	 * @since 5.9.0 Renamed `$string` (a PHP reserved keyword) to `$feedback` for PHP 8 named parameter support.
	 */
	public function feedback( $feedback, ...$args ) {
		if ( isset( $this->upgrader->strings[ $feedback ] ) ) {
			$feedback = $this->upgrader->strings[ $feedback ];
		}

		if ( false !== strpos( $feedback, '%' ) ) {
			if ( $args ) {
				$args     = array_map( 'strip_tags', $args );
				$args     = array_map( 'esc_html', $args );
				$feedback = vsprintf( $feedback, $args );
			}
		}
		if ( empty( $feedback ) ) {
			return;
		}

		$this->show_message( $feedback );
	}


	public function after() {

		if ( ! empty( $this->options['return_link'] ) ) {

			//phpcs:ignore
			$install_actions['plugins_page'] = '<a href="admin.php?page=' . esc_attr( $_REQUEST['page'] ) . '">' . esc_html__( 'Return to Plugin Installer', 'better-studio' ) . '</a>';
		}

		if ( ! empty( $install_actions ) ) {
			$this->feedback( implode( ' ', (array) $install_actions ) );
		}
	}


	public function show_message( $message ) {

		if ( is_wp_error( $message ) ) {
			if ( $message->get_error_data() && is_string( $message->get_error_data() ) ) {
				$message = $message->get_error_message() . ': ' . $message->get_error_data();
			} else {
				$message = $message->get_error_message();
			}
		}

		echo wp_kses( "<p>$message</p>\n", [ 'p' => [] ] );
	}
}
