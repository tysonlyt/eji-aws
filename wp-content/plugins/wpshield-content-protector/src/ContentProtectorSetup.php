<?php

namespace WPShield\Plugin\ContentProtector;

use WPShield\Core\PluginCore\Core\Contracts\{Bootstrap, HaveDashboard, HaveExtension};
use WPShield\Core\PluginCore\PluginSetup as CoreSetup;
use WPShield\Plugin\ContentProtector\{
	Panel\PanelOption,
	Core\Managers\ComponentsManager
};

/**
 * Class PluginSetup
 *
 * @since   1.0.0
 *
 * @package WpShield\Plugin\ContentProtector
 */
class ContentProtectorSetup extends CoreSetup implements Bootstrap, HaveExtension, HaveDashboard {

	/**
	 * Store instance of ComponentsManager.
	 *
	 * @var ComponentsManager $components_manager
	 */
	public $components_manager;

	/**
	 * Retrieve plugin released version number.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function version(): string {

		return '1.4.0';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_id(): string {

		return 'wpshield-content-protector';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_name(): string {

		return __( 'Content Protector', 'wpshield-content-protector' );
	}

	/**
	 * Initializing all components.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init_components(): bool {

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ], 10 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_assets' ], 10 );

		//Create option panel.
		PanelOption::setup();

		// Initialize after bf init
		add_action( 'better-framework/after_setup', [ $this, 'bf_init' ], 20 );

		add_action( 'wp', [ $this, 'after_wp_loaded' ] );

		add_filter( 'better-framework/controls/pro-features/content-protector/config', [ $this, 'pro_feature_setup' ] );

		add_action( 'better-framework/admin-panel/wpshield-content-protector/topbar/', [ $this, 'panel_topbar_notice' ] );

		add_filter( 'plugin_action_links_' . plugin_basename( $this->file() ), [ $this, 'register_settings_link' ] );

		return true;
	}

	/**
	 * @since 1.0.0
	 */
	public function enqueue_assets(): void {

		bf_enqueue_style( 'bf-icon' );

		$components_handle = wp_sprintf( '%s-components-js', $this->product_id() );

		if ( ! file_exists( $file = $this->dir( 'dist/app.min.js' ) ) ) {

			return;
		}

		wp_enqueue_script(
			$components_handle,
			$this->uri( 'dist/app.min.js' ),
			[],
			filemtime( $file ),
			true
		);
	}

	/**
	 * @since 1.0.0
	 */
	public function admin_enqueue_assets(): void {

		wp_enqueue_style(
			$this->product_id() . '-admin-style',
			$this->uri( 'src/Panel/css/admin-style.css' ),
			[],
			$this->version()
		);

		$components_handle = wp_sprintf( '%s-admin-js', $this->product_id() );

		wp_enqueue_script(
			$components_handle,
			$this->uri( 'admin/dist/admin.min.js' ),
			[],
			$this->version(),
			true
		);
	}

	/**
	 * Setup plugin manager.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function config(): array {

		return [
			'apply-libs' => true,
			'bf-version' => '4.0.0',
			'libs-uri'   => wp_sprintf( '%slibs/', WPSHIELD_CP_URL ),
			'libs-dir'   => wp_sprintf( '%slibs/', WPSHIELD_CP_PATH ),
		];
	}

	/**
	 *  Init the plugin
	 */
	public function bf_init(): void {

		add_filter( 'wpshield/content-protector-pro/requires/free-version', [ $this, 'fix_requires_current_version' ] );

		bf_register_icon_family( 'cp', $this->dir( 'assets/icons' ), $this->uri( 'assets/icons' ) );
	}

	/**
	 * Fixed requires free plugin.
	 *
	 * @param bool $status
	 *
	 * @since 1.0.0
	 * @return bool false
	 */
	public function fix_requires_current_version( bool $status ): bool {

		bf_remove_notice( 'wpshield-content-protector-free-plugin-required' );

		return false;
	}

	/**
	 * After WordPress loaded.
	 *
	 * @since 1.0.0
	 */
	public function after_wp_loaded(): void {

		$this->components_manager = new ComponentsManager( $this );

		do_action( 'wpshield/content-protector/components/manager/mount', $this->components_manager );
	}

	/**
	 * Setup content protector pro features modal.
	 *
	 * @param array $config
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function pro_feature_setup( array $config ): array {

		$config['template'] = [
			'title'          => __( '<span>“ <b>{{ name }}</b> ”</span> Is A PRO Feature', 'wpshield-content-protector' ),
			'desc'           => __( 'We\'re sorry, this feature is not available on your plan. Please upgrade to the PRO plan to unlock all these awesome features.', 'wpshield-content-protector' ),
			'discount_desc'  => __( '<span>Bonus:</span> Lite users get <b>30% OFF</b> regular price, Automatically will be applied at checkout.', 'wpshield-content-protector' ),
			'purchased_text' => __( 'Already Purchased?', 'wpshield-content-protector' ),
			'purchased_url'  => 'https://getwpshield.com/docs/content-protector/getting-started-cp/register-license/',
			'button_text'    => __( 'Upgrade to PRO', 'wpshield-content-protector' ),
			'button_url'     => 'https://getwpshield.com/plugins/content-protector/pricing/',
			'interested_p1'  => wp_sprintf( __( 'Thanks for your interest in WP Shield Content Protector PRO! If you have any questions or issues just <a href="%s">let us know</a>.', 'wpshield-content-protector' ), 'https://getwpshield.com/contact-us' ),
			'interested_p2'  => wp_sprintf( __( 'After purchasing a license, just enter your license key on the <a href="%s">WP Shield License</a> page. This will let your site automatically upgrade to Content Protector Pro!', 'wpshield-content-protector' ), admin_url( 'admin.php?page=bs-product-pages-license' ) ),
			'interested_p3'  => wp_sprintf( __( 'Check out <a href="%s">our documentation</a> for step-by-step instructions.', 'wpshield-content-protector' ), 'https://getwpshield.com/docs/upgrade-to-pro/' ),
		];

		return $config;
	}

	/**
	 * Retrieve plugin absolute directory path.
	 *
	 * @param string $directory
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function dir( string $directory = '' ): string {

		if ( ! empty( $directory ) ) {

			return wp_sprintf(
				'%s%s',
				WPSHIELD_CP_PATH,
				$directory
			);
		}

		return WPSHIELD_CP_PATH;
	}

	/**
	 * Retrieve plugin absolute directory uri.
	 *
	 * @param string $directory
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function uri( string $directory = '' ): string {

		if ( ! empty( $directory ) ) {

			return wp_sprintf(
				'%s%s',
				WPSHIELD_CP_URL,
				$directory
			);
		}

		return WPSHIELD_CP_URL;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function load_extensions(): bool {

		/**
		 * Initialized extension functionality hook name.
		 *
		 * @since 1.0.0
		 */
		do_action( 'wpshield/content-protector/extensions/init' );

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function file(): string {

		return WPSHIELD_CP__FILE__;
	}

	/**
	 * Panel topbar notice
	 *
	 * @return void
	 */
	public function panel_topbar_notice(): void {

		$notice_is_on = apply_filters( 'wpshield/content-protector/panel/top-bar/notice/status', true );

		if ( ! $notice_is_on && bf_is_product_registered( self::PRODUCT_ITEM_ID ) ) {

			return;
		}

		echo wp_sprintf(
		//phpcs:ignore
			__(
				'%1$sYou\'re using WP Shield Content Protector Lite. To unlock more features consider <a href="%2$s">upgrading to Pro</a>',
				'wpshield-content-protector'
			),
			//phpcs:ignore
			bf_get_icon_tag( 'bsai-lock' ),
			'https://getwpshield.com/plugins/content-protector/pricing/'
		);
	}

	/**
	 * Added product settings link in plugins table details.
	 *
	 * @param array $links
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_settings_link( array $links ): array {

		$links[] = $this->settings_link();

		return $links;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function settings_link(): string {

		return wp_sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'admin.php?page=bs-product-pages-wpshield-settings' ),
			__( 'Settings', 'wpshield-content-protector' )
		);
	}
}
