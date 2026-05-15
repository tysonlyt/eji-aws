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


/**
 * Handles all message showing in admin panel
 */
class BF_Admin_Notices {

	/**
	 * Store notice data to save in the database
	 * todo check and add custom location for pages
	 *
	 * @var array
	 */
	protected $notices_hook = [
		'post-new.php' => 'edit_form_top',
		'post.php'     => 'edit_form_top',
	];


	/**
	 * @var mixed|void
	 */
	protected $notice_data;


	/**
	 * Flag to detect it should be save or not
	 *
	 * @var bool
	 */
	protected $should_save = false;


	function __construct() {

		$this->apply_notice_hook();

		add_action( 'shutdown', [ $this, 'save_notices' ], 999 );
		add_action( 'wp_ajax_bf-notice-dismiss', [ $this, 'ajax_dismiss_handler' ] );

		$this->notice_data = $this->get_notices();
	}


	protected function apply_notice_hook() {
		global $pagenow;

		if ( isset( $this->notices_hook[ $pagenow ] ) ) {

			add_action( $this->notices_hook[ $pagenow ], [ $this, 'show_notice' ] );

		} elseif ( function_exists( 'bf_is_product_page' ) && bf_is_product_page() ) {

			add_filter( 'better-framework/admin-page/product-pages/body', [ $this, 'product_pages_notice' ] );

		} elseif ( $pagenow !== 'admin.php' || ! bf_starts_with( ( $_GET['page'] ?? '' ), 'better-studio/' ) ) {

			add_action( 'admin_notices', [ $this, 'show_notice' ] );
		}
	}


	/**
	 * Adds notice to showing queue
	 *
	 * @param array $notice      array {
	 *
	 * @type string|callable $mg          Message Text
	 * @type string          $id          optional. for deferred type.notice unique id
	 * @type string          $product     optional. unique id to detect notice is belong to which product
	 * @type string          $state       optional. success|warning|danger - default:success
	 * @type string          $thumbnail   optional. thumbnail image url
	 * @type array           $class       optional. notice custom classes
	 * @type string          $type        optional. Notice type is one of the deferred|fixed. - default: deferred.
	 * @type array           $page        optional. display notice on specific page. its an array of $pagenow values
	 * @type bool            $dismissible optional. display close notice button - default:true
	 * }
	 *
	 * @return bool true on success or false on error.
	 */
	function add_notice( $notice ) {

		$notice = bf_merge_args(
			$notice,
			[
				'type'        => 'deferred',
				'dismissible' => true,
				'id'          => false,
				'product'     => false,
				'state'       => 'success',
			]
		);

		if ( empty( $notice['msg'] ) ) {
			return false;
		}

		/**
		 * Empty id just allowed for deferred type.
		 */
		if ( $notice['type'] !== 'deferred' && empty( $notice['id'] ) ) {
			return false;
		}

		if ( empty( $notice['id'] ) ) {
			$notice['id'] = $this->generate_ID();
		}

		$this->notice_data[ $notice['id'] ] = apply_filters( 'better-framework/admin-notices/new', $notice );

		if ( $this->immediately_save() ) {
			return $this->update_notices( $this->notice_data );
		}

		$this->should_save = true;

		return true;
	}

	public function product_pages_notice( $body ) {

		ob_start();
		$this->show_notice();
		$notices = ob_get_clean();

		return $notices . $body;
	}

	/**
	 * Remove a notice
	 *
	 * @param string|int|array $id notice unique id
	 *
	 * @return bool true on success or false on error
	 */
	function remove_notice( $id = null ) {

		if ( is_array( $id ) ) {
			$id = isset( $id['id'] ) ? $id['id'] : false;
		}
		if ( ! $id ) {
			return false;
		}

		$nt = &$this->notice_data;

		if ( isset( $nt[ $id ] ) ) {

			unset( $nt[ $id ] );

			if ( $this->immediately_save() ) {
				return $this->update_notices( $nt );
			} else {

				unset( $this->notice_data[ $id ] );
				$this->should_save = true;

				return true;
			}
		}

		return false;
	} // remove_notice


	protected function immediately_save(): bool {

		return did_action( 'admin_footer' ) ||
			   ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ||
			   ( defined( 'DOING_CRON' ) && DOING_CRON );
	}


	protected function generate_ID() {

		do {
			$id = mt_rand();
		} while ( isset( $this->notice_data[ $id ] ) );

		return $id;
	}


	/**
	 * Callback: Shows notice
	 * Action  : admin_notices
	 */
	function show_notice() {

		$notices = apply_filters( 'better-framework/admin-notices/show', $this->notice_data );

		if ( ! $notices ) {
			return;
		}

		foreach ( $notices as $notice ) {

			if ( ! bf_item_can_shown( $notice ) ) {
				continue;
			}

			if ( is_callable( $notice['msg'] ) ) {
				$message = call_user_func( $notice['msg'], $notice, $this );
			} elseif ( ! is_array( $notice['msg'] ) ) {
				$message = wpautop( $notice['msg'] );
			} else {
				$message = '';
			}

			if ( ! $message ) {
				continue;
			}

			$dismissible   = ! empty( $notice['dismissible'] );
			$has_thumbnail = ! empty( $notice['thumbnail'] ) && filter_var( $notice['thumbnail'], FILTER_VALIDATE_URL );

			$filter_class = str_replace( '.php', '', current_filter() );

			if ( isset( $notice['class'] ) && is_string( $notice['class'] ) ) {
				$notice['class'] = [ $notice['class'] ];
			} elseif ( ! isset( $notice['class'] ) || ! is_array( $notice['class'] ) ) {
				$notice['class'] = [];
			}

			$notice['class'][] = 'bf-notice';
			$notice['class'][] = 'bf-notice-' . sanitize_html_class( $filter_class );
			$notice['class'][] = sprintf( 'bf-notice-%s', $notice['type'] );

			if ( ! isset( $notice['class'] ) ) {
				$notice['class'] = [];
			}

			if ( $dismissible ) {
				$notice['class'][] = 'bf-notice-dismissible';
			}

			$notice['class'][] = $has_thumbnail ? 'bf-notice-has-thumbnail' : 'bf-notice-no-thumbnail';

			$notice['class'][] = 'bf-notice-' . $notice['state'];

			$attrs = '';

			if ( ! empty( $notice['show_all_label'] ) ) {
				$attrs .= sprintf( ' data-show-all="%s"', esc_attr( $notice['show_all_label'] ) );
			}

			if ( isset( $notice['show-all'] ) && ! $notice['show-all'] ) {
				$attrs .= ' data-show-all-enable="false"';
			}

			if ( ! empty( $notice['color'] ) || ! empty( $notice['color_darker'] ) ) {
				$attrs .= ' style="';
				if ( ! empty( $notice['color'] ) ) {
					$attrs .= '--bf-primary-color:' . $notice['color'] . ';';
				}

				if ( ! empty( $notice['color_darker'] ) ) {
					$attrs .= '--bf-primary-darker-color:' . $notice['color'] . ';';
				}

				$attrs .= '"';
			}

			?>
			<div class="bf-notice-wrapper bf-fields-style bf-notice-<?php echo esc_attr( $notice['id'] ); ?> wrap"<?php echo $attrs; ?>>
				<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $notice['class'] ) ) ); ?>">

					<div class="bf-notice-container">
						<?php
						if ( $has_thumbnail ) {
							printf( '<div class="bf-notice-thumbnail"><img src="%s" class="bf-notice-thumbnail-img"></div>', esc_html( $notice['thumbnail'] ) );
						}
						?>
						<div class="bf-notice-text-container">
							<div class="bf-notice-text">
								<?php
								echo $message;
								?>
							</div>
						</div>

						<button type="button" class="bf-notice-dismiss"
							<?php if ( $notice['type'] !== 'deferred' ) { ?>
								data-notice-token="<?php echo esc_attr( wp_create_nonce( 'notice-dismiss-' . $notice['id'] ) ); ?>"
								data-notice-id="<?php echo esc_attr( $notice['id'] ); ?>"
							<?php } ?>>
								<span
										class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'better-studio' ); ?></span>
							<?php
							if ( isset( $notice['dismiss_label'] ) ) {
								echo $notice['dismiss_label'];
							}
							?>
						</button>
					</div>
				</div>
				<?php

				if ( $notice['type'] === 'deferred' ) {
					$this->remove_notice( $notice );
				}

				?>
			</div>
			<?php
		}
	} // show_notice


	/**
	 * Set notices info in db
	 *
	 * @param array $notices
	 *
	 * @return bool true on success or false on failure.
	 */
	protected function update_notices( $notices ) {

		return update_option( 'bf_notices', $notices );
	}


	/**
	 * Get all notices
	 *
	 * @return array
	 */
	public function get_notices() {

		return get_option( 'bf_notices', [] );
	}


	/**
	 * Update notices storage  with given data
	 *
	 * @param array $notices
	 *
	 * @return bool true on success or false on failure
	 */
	public function set_notices( $notices ) {

		if ( is_array( $notices ) && $notices ) {
			return update_option( 'bf_notices', $notices );
		} elseif ( ! $notices ) {
			return delete_option( 'bf_notices' );
		}

		return false;
	}


	/**
	 * Callback: Save added notices in db
	 * Action  : admin_footer
	 */
	function save_notices() {

		if ( ! $this->should_save ) {
			return;
		}

		if ( is_array( $this->notice_data ) ) {
			update_option( 'bf_notices', $this->notice_data );
		} elseif ( $this->notice_data === false ) {
			delete_option( 'bf_notices' );
		}
	}


	/**
	 * Callback: close notice ajax request handler
	 * Action  : wp_ajax_bf-notice-dismiss
	 */
	public function ajax_dismiss_handler() {

		$required_params = [
			'noticeId'    => '',
			'noticeToken' => '',
		];
		if ( array_diff_key( $required_params, $_REQUEST ) ) {

			return;
		}

		$id = &$_REQUEST['noticeId'];
		if ( ! wp_verify_nonce( $_REQUEST['noticeToken'], 'notice-dismiss-' . $id ) ) {
			wp_die( __( 'Security error occurred', 'better-studio' ) );
		}

		$this->remove_notice( $id );
	}
}
