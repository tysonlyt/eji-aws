<?php


namespace WPShield\Core\PluginCore\Dashboard\Menus\Settings;

use WPShield\Core\PluginCore\PluginSetup;

/**
 * Class Dashboard
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Dashboard\Menus\Settings
 */
class Settings extends \BF_Product_Plugin_Manager {

	public $id = 'wpshield-settings';

	/**
	 * Dashboard constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = [] ) {

		parent::__construct();

		$this->args = $args;

		//Before rendering
		$this->update_plugins( true, PluginSetup::PRODUCT_ITEM_ID );

		// Callback for enqueue BF admin pages style
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	public function render_content( $item_data ) {

		$list_table = $this->list_table();

		$plugins = $list_table->get_plugins_list();

		include __DIR__ . '/public/body.php';
	}

	/**
	 * Retrieve the instance of ListTable.
	 *
	 * @since 1.0.0
	 * @return BundlePlugins
	 */
	public function list_table(): BundlePlugins {

		return new BundlePlugins( [ 'screen' => __CLASS__ ] );
	}

	/**
	 * Callback: Used for enqueue scripts in WP backend
	 *
	 * Action: admin_enqueue_scripts
	 *
	 * @since   1.0.0
	 * @return void
	 */
	public function admin_enqueue_scripts(): void {

		wp_enqueue_script( 'jquery' );

		global $hook_suffix;

		$submenu_page_prefix = 'product-pages-wpshield-settings';

		if ( false === strpos( $hook_suffix, $this->args['type'] ) && false === strpos( $hook_suffix, $submenu_page_prefix ) ) {

			return;
		}

		if ( false !== strpos( $hook_suffix, $this->args['type'] ) ) {

			wp_enqueue_style(
				'dashboard-asset',
				$this->args['dir-uri'] . '/css/dashboard.min.css',
				[],
				$this->args['version']
			);
		}

		bf_register_product_enqueue_scripts();
	}

	/**
	 * Active plugin by ajax request
	 *
	 * @hooked 'wp_ajax_plugin-core-active'
	 *
	 * @since  1.0.0
	 */
	public function active_plugin(): bool {

		//FIXME: Add nonce verification!

		$this->run_activate_plugin( $_POST['slug'] . '/' . $_POST['slug'] . '.php' );

		return true;
	}

	/**
	 * @param $plugin
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function run_activate_plugin( $plugin ): bool {

		$current = get_option( 'active_plugins' );

		$plugin = plugin_basename( trim( $plugin ) );

		if ( in_array( $plugin, $current, true ) ) {

			return false;
		}

		$current[] = $plugin;

		sort( $current );

		do_action( 'activate_plugin', trim( $plugin ) );

		update_option( 'active_plugins', $current );

		do_action( 'activate_' . trim( $plugin ) );

		do_action( 'activated_plugin', trim( $plugin ) );

		return true;
	}
}