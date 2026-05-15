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
 * Class BS_Product_Plugins
 */
class BF_Product_Plugin_Manager extends BF_Product_Multi_Step_Item {

	/**
	 * @var mixed|null
	 */
	public $handled_action;

	/**
	 * @var string
	 */
	public $id = 'install-plugin';

	public $check_update_duration;

	/**
	 * Store custom messages
	 *
	 * @var array
	 */
	public $messages = array();

	/**
	 * @var BF_Product_Plugin_Installer
	 */
	protected $installer;


	/**
	 * BF_Product_Plugin_Manager constructor.
	 */
	public function __construct() {

		$this->messages  = array();
		$this->installer = new BF_Product_Plugin_Installer();

		parent::__construct();

		$this->check_update_duration = MINUTE_IN_SECONDS * 15; //check plugin update every 15 minutes;
		//
		$this->handle_early_actions();
	}


	protected function before_render() {

		wp_enqueue_script( 'updates' );

		$this->handled_action = $this->handle_actions();

		$this->update_plugins();

		$this->display_messages();
	}

	protected function after_render() { //phpcs:disable?>
        <script>
            pagenow = "plugins";
        </script>
		<?php
		//phpcs:enable
	}


	/**
	 *
	 */
	protected function handle_early_actions() {

		// Important: WP plugins list table deactivation bug fix.
		if ( ! bf_is_product_page( 'install-plugin' ) ) {

			return;
		}

		$action = $this->installer->handle_early_action();

		if ( $action && isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {

			$url = remove_query_arg(
				array( 'action', '_wpnonce', 'plugin' ),
				esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) )
			);
			$url = add_query_arg( 'msg', $action, $url );

			wp_ob_end_flush_all();
			wp_safe_redirect( $url );

			die( '#' );
		}

		//phpcs:ignore
		if ( isset( $_GET['msg'] ) ) {

			//phpcs:ignore
			$message = $this->installer->defined_message( $_GET['msg'] );

			if ( $message ) {

				$this->messages[] = $message;
			}
		}
	}


	protected function handle_actions() {

		$handled  = $this->installer->handle_action();
		$messages = $this->installer->get_messages();

		if ( $messages ) {
			$this->messages = array_merge( $messages, $this->messages );
		}

		return $handled;
	}


	/**
	 * Check plugins for update
	 *
	 * @param bool       $force   Whether to force check plugins for update. Defaults to false.
	 * @param string|int $item_id the product itemID.
	 *
	 * @return stdClass|bool false on failure.
	 * @uses $wp_version Used to notify the WordPress version.
	 *
	 */
	public function update_plugins( $force = false, $item_id = 15801051 ) {

		global $wp_version, $pagenow;

		include ABSPATH . WPINC . '/version.php';

		// Don't check update while updating another item!

		if (
			//phpcs:ignore
			( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], [
					//phpcs:ignore
					'do-theme-upgrade',
					//phpcs:ignore
					'do-plugin-upgrade',
					//phpcs:ignore
				], true ) )
			||
			//phpcs:ignore
			( bf_is_product_page( 'install-plugin' ) && ! empty( $_REQUEST['action'] ) )
			||
			(
				//phpcs:ignore
				isset( $_REQUEST['action'] ) &&
				in_array( $pagenow, array( 'admin-ajax.php', 'update.php' ), true ) &&
				//phpcs:ignore
				in_array( $_REQUEST['action'], array(
					'upgrade-theme',
					'update-selected-themes',
					'update-theme',
					'update-selected',
				) )
			)
		) {
			return false;
		}

		$plugins_basename = BF_Product_Plugin_Installer::get_plugins_basename();
		$all_plugins_data = get_plugins();
		$remote_plugins   = array();
		$active_plugins   = array();

		$update_status                 = new stdClass();
		$update_status->last_checked   = time();
		$update_status->no_update      = array();
		$update_status->remote_plugins = array(); // WordPress repo plugins need update
		$update_status->translations   = array();
		$update_status->local_plugins  = array(); // list of local plugins need update

		if ( ! $force ) {

			$prev_status = get_option( 'bs-product-plugins-status' );

			if ( ! is_object( $prev_status ) ) {
				$prev_status               = new stdClass();
				$prev_status->last_checked = time();
				$skip_update               = false;
			} else {
				$skip_update = $this->check_update_duration > ( time() - $prev_status->last_checked );
			}

			if ( $skip_update ) {

				return $prev_status;
			}
		}

		$bundled_plugins      = array();
		$bundled_plugins_data = array();
		$plugins_data         = $this->get_plugins_data( $item_id );

		if ( $plugins_data ) {
			//phpcs:ignore
			foreach ( $plugins_data as $ID => $plugin_data ) {
				if ( empty( $plugin_data['slug'] ) ) {
					continue;
				}

				/**
				 * skip process if plugin was not installed!
				 */
				$slug = &$plugin_data['slug'];
				if ( ! isset( $plugins_basename[ $slug ] ) ) {
					continue;
				}
				$plugin_basename = &$plugins_basename[ $slug ];
				/**
				 * get plugin basename path EX: pluginDirectory/pluginFile.php
				 *
				 * @see plugin_basename
				 * @var string $plugin_basename
				 */
				if ( ! isset( $all_plugins_data[ $plugin_basename ] ) ) {
					continue;
				}
				// End plugin installation check block

				$active_plugins[] = $plugin_basename;
				$data             = &$all_plugins_data[ $plugin_basename ];
				$is_local_plugin  = ! empty( $plugin_data['local_path'] );

				if ( $is_local_plugin ) {
					//compare local plugin version with installed plugin
					if (
						isset( $data['Version'] ) && isset( $plugin_data['version'] )
						&& version_compare( $plugin_data['version'], $data['Version'], '>' )
					) {

						$update_status->local_plugins[ $plugin_basename ] = array(
							//phpcs:ignore
							'id'          => $ID,
							'slug'        => $slug,
							'new_version' => $plugin_data['version'],
						);
					}
				} elseif ( isset( $plugin_data['type'] ) && 'bundled' === $plugin_data['type'] ) {
					// bundled plugin

					$bundled_plugins[ $slug ]      = $data['Version'];
					$bundled_plugins_data[ $slug ] = compact( 'plugin_basename', 'ID' );
				} else {
					//wordpress repository plugin
					$remote_plugins[ $plugin_basename ] = $data;
				}
			}
		}

		/**
		 * check wp repo plugins update
		 */

		// Three seconds, plus one extra second for every 10 plugins
		$timeout      = 3 + (int) ( bf_count( $plugins_basename ) / 10 );
		$to_send      = array(
			'plugins' => $remote_plugins,
			'active'  => $active_plugins,
		);
		$translations = wp_get_installed_translations( 'plugins' );
		$locales      = array( get_locale() );
		/**
		 * Filter the locales requested for plugin translations.
		 *
		 * @see wp_update_plugins
		 *
		 * @param array $locales Plugin locale. Default is current locale of the site.
		 */
		$locales = apply_filters( 'plugins_update_check_locales', $locales );


		$options = array(
			'timeout'    => $timeout,
			'body'       => array(
				'plugins'      => wp_json_encode( $to_send ),
				'translations' => wp_json_encode( $translations ),
				'locale'       => wp_json_encode( $locales ),
				'all'          => wp_json_encode( true ),
			),
			'user-agent' => 'WordPress/' . $wp_version . '; ' . esc_url( home_url( '/' ) ),
		);
		$api_url = 'http://api.wordpress.org/plugins/update-check/1.1/';

		$raw_response = wp_remote_post( $api_url, $options );
		if ( ! is_wp_error( $raw_response ) && 200 === wp_remote_retrieve_response_code( $raw_response ) ) {

			$response = json_decode( wp_remote_retrieve_body( $raw_response ), true );

			if ( is_array( $response ) ) {
				$update_status->remote_plugins = $response['plugins']; //list of plugins need update
				$update_status->translations   = $response['translations'];
				$update_status->no_update      = $response['no_update'];
			}
		}

		/**
		 * check bundled plugins update
		 */

		if ( $bundled_plugins ) {
			$auth['id']   = $item_id;
			$check_update = $this->api_request(
				'check-plugin-update',
				array(
					'plugins_list' => $bundled_plugins,
					'auth'         => $auth,
				)
			);
			if ( ! is_wp_error( $check_update ) && ! empty( $check_update->success ) && ! empty( $check_update->plugins ) ) {
				foreach ( $check_update->plugins as $slug => $version ) {
					if ( 'latest' !== $version ) {
						$plugin_basename                                   = $bundled_plugins_data[ $slug ]['plugin_basename'];
						$update_status->remote_plugins[ $plugin_basename ] = array(
							'id'          => $bundled_plugins_data[ $slug ]['ID'],
							'slug'        => $slug,
							'new_version' => $version,
						);
					}
				}
			}
		}

		if ( isset( $response ) ) {
			do_action( 'better-framework/product-pages/plugin-update-check', $update_status, $response );
		}

		update_option( 'bs-product-plugins-status', $update_status, 'no' );

		return $update_status;
	}


	/**
	 * get list of plugins
	 *
	 * @see \BF_Product_Plugin_Factory::install_start $plugin_data param
	 * @return array
	 */
	public function get_plugins_data( $item_id = 15801051 ) {

		return bf_get_plugins_config( $item_id );
	}


	/**
	 * @param $options
	 */
	public function render_content( $options ) {

		global $status, $page;

		if ( $this->handled_action ) {

			if ( 'deactivate' !== $this->handled_action && 'activate' !== $this->handled_action && 'delete' !== $this->handled_action ) {
				return;
			}
		}

		$list_table = $this->list_table();

		bf_product_view( 'install-plugin/templates/main-page', compact( 'list_table', 'status', 'page' ) );
	}


	/**
	 * Get an instance of the plugins list table class
	 *
	 * @return BF_Product_Plugin_List_Table
	 */
	protected function list_table() {

		return new BF_Product_Plugin_List_Table( array( 'screen' => __CLASS__ ) );
	}


	/**
	 *
	 */
	public function display_messages() {

		if ( empty( $this->messages ) ) {
			return;
		}

		//phpcs:disable
		?>
        <div id="message" class="updated notice is-dismissible">
			<?php
			foreach ( $this->messages as $message ) {

				printf( '<p>%s%s</p>', $message, "\n" );
			}
			?>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'better-studio' ); ?></span>
            </button>
        </div>
<?php
	}
}
