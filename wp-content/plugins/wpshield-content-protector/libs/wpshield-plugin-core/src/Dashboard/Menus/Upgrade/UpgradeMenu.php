<?php

namespace WPShield\Core\PluginCore\Dashboard\Menus\Upgrade;

/**
 * Class PluginsManager
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore\Dashboard\Menus\Upgrade
 */
class UpgradeMenu extends \BF_Product_Item {

	public $id = 'wpshield-upgrade';

	/**
	 * Store the version of panel.
	 *
	 * @var string
	 */
	protected $version = '1.0.0';

	/**
	 * Store of menu slug.
	 *
	 * TODO: static external link!
	 *
	 * @var string $prefix_slug
	 */
	protected $slug = 'https://getwpshield.com/plugins/content-protector/pricing/';

	/**
	 * Initialize plugin core panel.
	 *
	 * @param array $args Configuration
	 *
	 * @since   1.0.0
	 *
	 */
	public function __construct( array $args = [] ) {

		$this->args = $args;

		add_filter( 'better-framework/product-pages/register-menu/params', [ $this, 'filter_config' ] );

		// Callback for enqueue BF admin pages style
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		parent::__construct( $this->args );
	}

	/**
	 * Filtering menu config.
	 *
	 * @param array $menu_config
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function filter_config( array $menu_config ): array {

		if ( false !== strpos( $menu_config['slug'], $this->args['type'] ) ) {

			$menu_config['slug'] = $this->slug;
		}

		return $menu_config;
	}

	/**
	 * @inheritDoc
	 *
	 * @param $item_data
	 *
	 * @since 1.0.0
	 */
	public function render_content( $item_data ) {
		// TODO: Implement render_content() method.
	}

	/**
	 * Enqueue assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue(): void {

		wp_enqueue_style(
			'upgrade-to-pro',
			$this->args['dir-uri'] . '/css/upgrade-menu.css',
			[],
			$this->args['version']
		);
	}
}
