<?php

namespace WPShield\Core\PluginCore\Dashboard\Menus\License;

/**
 * Class Manager
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Dashboard\Menus\License
 */
class Manager extends \BF_Product_License {

	/**
	 * Store the identifier.
	 *
	 * @var string
	 */
	public $id = 'wpshield-license';

	/**
	 * Store the arguments.
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Initialize plugin core panel.
	 *
	 * @param array $args Configuration
	 *
	 * @since   1.0.0
	 */
	public function __construct( array $args = [] ) {

		parent::__construct();

		$this->args = $args;

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
	}

	/**
	 * @inheritDoc
	 *
	 * @param $item_data
	 */
	public function render_content( $item_data ): void {

		$product_name = $this->args['panel-sec-title'];

		if ( ! file_exists( __DIR__ . '/public/body.php' ) ) {

			return;
		}

		include __DIR__ . '/public/body.php';
	}

	/**
	 * Enqueue assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue(): void {

		bf_register_product_enqueue_scripts();
	}

	/**
	 * @param array $response
	 *
	 * @throws \Exception
	 */
	protected function handle_reponse( $response ) {

		if ( empty( $response ) ) {

			throw new \Exception( __( 'unknown error occurred!', 'better-studio' ) );
		}

		$item_id       = $this->params['item_id'];
		$purchase_code = $this->params['bs-purchase-code'];

		if ( is_wp_error( $response ) ) {

			$error_code = $response->get_error_code();

			if ( 'add-to-account' === $error_code || 'add-domain' === $error_code ) {

				$uri       = site_url();
				$bs_action = 'register-product';
				$link      = add_query_arg(
					compact( 'purchase_code', 'uri', 'item_id', 'bs_action' ),
					'https://getwpshield.com/account/apply-new-purchase'
				);

				if ( 'add-domain' === $error_code ) {

					$response = array(
						'error-message' => '<div class="bf-fields-style">' . wp_kses( sprintf( __( 'Your current domain name was not added to this purchase code,<br/> <b>Please add this domain name to your license code</b> by clicking on the following button <br><br> <a href="%s" class="button button-primary" target="_blank" id="bs-login-register-btn">Add new domain</a>', 'better-studio' ), $link ), bf_trans_allowed_html() ) . '</div>',
						'error-code'    => 'add-to-account',
						'result'        => 'error',
					);
				} else {

					$response = array(
						'error-message' => '<div class="bf-fields-style">' . wp_kses( sprintf( __( 'This looks like <b>a new purchase code that hasn&#x2019;t been added to BetterStudio account yet</b>. Login to existing account or register new one to continue. <br><br> <a href="%s" class="button button-primary" target="_blank" id="bs-login-register-btn">Login or Register</a>', 'better-studio' ), $link ), bf_trans_allowed_html() ) . '</div>',
						'error-code'    => 'add-to-account',
						'result'        => 'error',
					);
				}

			} else {

				throw new \Exception( $response->get_error_message() );
			}
		}

		if ( isset( $response->status ) ) {

			$status = $response->status;

			bf_delete_transient( 'bf-plugins-config' ); // Clear plugins list cache
			bf_register_product_set_info( $item_id, compact( 'purchase_code', 'status' ) );
		}

		wp_send_json( $response );
	}
}
