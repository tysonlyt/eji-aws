<?php

namespace WPShield\Plugin\ContentProtectorPro;

use WPShield\Core\PluginCore\{Core\Contracts\Bootstrap, Core\Contracts\HaveExtension};
use WPShield\Plugin\ContentProtectorPro\{
	Panel\PanelOption,
	Features\ViewSource\Handler,
	Core\WebServer\ApacheConfigManager,
	Core\WebServer\WebServerConfigModifier
};
use function WPShield\Core\PluginCore\wpshield_plugin_core_is_registered_product as is_registered;

/**
 * Class PluginSetup
 *
 * @since   1.0.0
 *
 * @package WpShield\Plugin\ContentProtector
 */
class ContentProtectorSetup extends \WPShield\Core\PluginCore\PluginSetup implements Bootstrap, HaveExtension {

	/**
	 * Store the product registered status.
	 *
	 * @var bool
	 */
	public static $is_registered = false;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function file(): string {

		return WPSHIELD_CPP__FILE__;
	}

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

		return 'wpshield-content-protector-pro';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_name(): string {

		return __( 'Content Protector PRO', 'wpshield-content-protector-pro' );
	}

	/**
	 * Retrieve plugin text domain.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function text_domain(): string {

		return $this->product_id();
	}

	/**
	 * Initializing all components.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init_components(): bool {

		$this->set_company_name( 'wpshield' );

		add_action( 'init', [ $this, 'buffer_start' ] );

		add_action( 'shutdown', [ $this, 'buffer_end' ], 0 );

		add_action( 'wpshield/wpshield-content-protector/before/init', [ $this, 'boot' ] );

		add_action( 'better-framework/after_setup', [ $this, 'bf_initialize' ], 30 );

		add_filter( 'better-framework/controls/pro-features/content-protector/enable', [ $this, 'unlock_pro_features' ] );

		if ( class_exists( \WPShield\Plugin\ContentProtector\ContentProtectorSetup::class ) ) {

			register_activation_hook( $this->file(), [ $this, 'activation_hook' ] );
			register_deactivation_hook( $this->file(), [ $this, 'deactivation_hook' ] );
		}

		add_filter( 'wpshield-plugin-core/plugin/is-pro', '__return_true' );
		add_filter( 'wpshield-plugin-core/dashboard/pages/have-license', '__return_true' );
		add_filter( 'wpshield/content-protector/panel/top-bar/notice/status', '__return_false' );

		return true;
	}

	/**
	 * Turn on output buffering.
	 *
	 * we use 'init' action to use ob_start()!
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function buffer_start(): void {

		ob_start();
	}

	/**
	 * Get current buffer contents and delete current output buffer
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function buffer_end(): void {

		$final = '';

		// We'll need to get the number of ob levels we're in, so that we can iterate over each, collecting
		// that buffer's output into the final output.
		$levels = ob_get_level();

		for ( $i = 0; $i < $levels; $i++ ) {

			$final .= ob_get_clean();
		}

		// Apply any filters to the final output
		//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo apply_filters( 'wpshield/content-protector-pro/buffer/end/content', $final );
	}

	/**
	 *  Init the free plugin submodule
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function bf_initialize(): void {

		/**
		 * Handle notice status Hook!
		 *
		 * This way to add notice to requires wpshield-content-protector free version of current plugin!
		 *
		 * External developers can execute custom handlers by hooking to the
		 * `wpshield/content-protector-pro/requires/free-version` filter.
		 *
		 * @since 1.0.0
		 */
		if ( apply_filters( 'wpshield/content-protector-pro/requires/free-version', true ) ) {

			$message = sprintf(
				'<h3 class="title">%s</h3> <div class="detail"><ul>%s</ul> <a href="%s" class="button button-primary">%s</a></div>',
				__( 'Plugin Required For Content Protector Pro', 'wpshield-content-protector' ),
				__( 'You must install the lite version of WP Shield Content Protector Pro from the following link in order for the Pro version to function properly.', 'wpshield-content-protector' ),
				'https://getwpshield.com/account/license-manager/',
				__( 'Install Content Protector Lite', 'better-studio' )
			);

			bf_add_notice( array(
				'msg'          => $message,
				'thumbnail'    => WPSHIELD_CPP_URL . 'assets/images/wpshield-content-protector-notice.svg',
				'type'         => 'fixed',
				'dismissible'  => false,
				'class'        => 'wpshield-content-protector-notice',
				'id'           => 'wpshield-content-protector-free-plugin-required',
				'color'        => '#d60000',
				'color_darker' => '#c60000',
			) );
		}

		PanelOption::setup();

		WebServerConfigModifier::setup();
	}

	/**
	 * Bootstrap premium features
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function boot(): void {

		/**
		 * Is registered product license?
		 */
		self::$is_registered = is_registered( self::PRODUCT_ITEM_ID );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		#Filter and add new features support.
		add_filter( 'wpshield/content-protector/core/manager/components', [ $this, 'add_components_addons' ] );

		if ( self::$is_registered ) {

			$view_source = new Handler();

			add_action( 'better-framework/panel/save', [ $view_source, 'create_copyright' ] );
		}
	}

	/**
	 * Enqueue plugin assets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_assets(): void {

		if ( ! file_exists( $file = $this->dir( 'src/Features/assets/js/index.min.js' ) ) ) {

			return;
		}

		wp_enqueue_script(
			sprintf( '%s-features-js', $this->product_id() ),
			$this->uri( 'src/Features/assets/js/index.min.js' ),
			[ 'wpshield-content-protector-components-js', 'wp-i18n' ],
			filemtime( $file ),
			true
		);
	}

	/**
	 * Adding pro addons to components manager of free plugin.
	 *
	 * @param array $components
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function add_components_addons( array $components ): array {

		return array_merge( $components ?? [], ! self::$is_registered ? [] : $this->get_creators() );
	}

	/**
	 * Retrieve array of Feature Creators.
	 *
	 * @since 1.0.0
	 * @return Features\RightClick\Creator[]
	 */
	protected function get_creators(): array {

		return [
			'feed-addons'            => Features\Feed\Creator::class,
			'email-addons'           => Features\Email\Creator::class,
			'phone-addons'           => Features\Phone\Creator::class,
			'iframe-addons'          => Features\Iframe\Creator::class,
			'images-addons'          => Features\Images\Creator::class,
			'video-addons'           => Features\Video\Creator::class,
			'audio-addons'           => Features\Audio\Creator::class,
			'text-copy-addons'       => Features\TextCopy\Creator::class,
			'extensions'             => Features\Extensions\Creator::class,
			'right-click-addons'     => Features\RightClick\Creator::class,
			'view-source-addons'     => Features\ViewSource\Creator::class,
			'print-addons'           => Features\PrintAddon\Creator::class,
			'idm-extension'          => Features\IDMExtension\Creator::class,
			'developer-tools-addons' => Features\DeveloperTools\Creator::class,
			'javascript-addons'      => Features\DisabledJavascript\Creator::class,
		];
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

			return sprintf(
				'%s%s',
				WPSHIELD_CPP_PATH,
				$directory
			);
		}

		return WPSHIELD_CPP_PATH;
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

			return sprintf(
				'%s%s',
				WPSHIELD_CPP_URL,
				$directory
			);
		}

		return WPSHIELD_CPP_URL;
	}

	/**
	 * Setup plugin manager.
	 *
	 * "apply-libs" => if needed BF or other libraries this value must be TRUE.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function config(): array {

		return [
			'apply-libs' => true,
			'bf-version' => '4.0.0',
			'libs-uri'   => wp_sprintf( '%slibs/', WPSHIELD_CPP_URL ),
			'libs-dir'   => wp_sprintf( '%slibs/', WPSHIELD_CPP_PATH ),
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function load_extensions(): bool {

		// TODO: Implement load_plugin() method.

		return true;
	}

	/**
	 * Clear All htaccess changes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function undo_htaccess_changes(): void {

		$web_server = WebServerConfigModifier::instance();

		if ( ! $web_server->find_web_server( new ApacheConfigManager() ) ) {
			return;
		}

		$web_server->protection_config( false );
	}

	/**
	 * Clear All htaccess changes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function update_htaccess_changes(): void {

		$web_server = WebServerConfigModifier::instance();

		if ( ! $web_server->find_web_server( new ApacheConfigManager() ) ) {
			return;
		}

		$web_server->protection_config();
	}

	/**
	 * Handle activation hook when register.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function activation_hook(): bool {

		$this->update_htaccess_changes();

		return true;
	}

	/**
	 * Handle deactivation hook when register.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function deactivation_hook(): bool {

		$this->undo_htaccess_changes();

		return true;
	}

	/**
	 * Unlock Premium Features.
	 *
	 * @since 1.0.0
	 * @return bool false on unlock pro features success, true when occurred otherwise!
	 */
	public function unlock_pro_features(): bool {

		return ! self::$is_registered;
	}

}
