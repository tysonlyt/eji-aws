<?php

namespace WPShield\Core\PluginCore;

//Internal
use WPShield\Core\{
	PluginCore\Dashboard\Dashboard,
	PluginCore\Core\Contracts\Details,
	PluginCore\Core\Contracts\Activate,
	PluginCore\Core\Contracts\Bootstrap,
	PluginCore\Core\Contracts\Deactivated,
	PluginCore\Core\Contracts\HaveDashboard,
	PluginCore\Core\Contracts\HaveExtension
};

//External
use BetterStudio\Core\Module\ModuleHandler;

/**
 * Class PluginSetup
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore
 */
abstract class PluginSetup extends ModuleHandler implements Details, Activate, Deactivated {

	/**
	 * Store the product item id.
	 *
	 * @since 1.0.0
	 */
	public const PRODUCT_ITEM_ID = 20220905;

	/**
	 * Store instance of ManagerBase.
	 *
	 * @var ManagerBase
	 */
	public $manager_base;

	/**
	 * Store number of version released.
	 *
	 * @var string $version
	 */
	protected $version = '1.0.0';

	/**
	 * Store company name.
	 *
	 * @var string $company_name
	 */
	protected $company_name = 'wpshield';

	/**
	 * Retrieve plugin absolute directory path.
	 *
	 * @param string $directory
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function dir( string $directory = '' ): string;

	/**
	 * Retrieve plugin absolute directory uri.
	 *
	 * @param string $directory
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function uri( string $directory = '' ): string;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @since  1.0.0
	 */
	public function __clone() {

		//Un serializing instances of the class is forbidden.
		_doing_it_wrong(
			__FUNCTION__,
			esc_html__(
				'Something went wrong.',
				//phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralDomain
				'wpshield'
			),
			esc_html( $this->version )
		);
	}

	/**
	 * @inheritDoc
	 *
	 * @return bool
	 */
	public function init(): bool {

		/**
		 * Before initializing
		 *
		 * @since 1.0.0
		 */
		// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		do_action( sprintf( '%s/%s/before/init', $this->company_name, $this->product_id() ) );

		$this->manager_base = new ManagerBase( $this );

		if ( $this instanceof HaveExtension ) {

			#Loading extensions when current object is extension.
			$this->load_extensions();
		}

		if ( $this instanceof Bootstrap ) {

			#initializing all components when current object is main plugin.
			$this->init_components();
		}

		$have_dashboard = $this instanceof HaveDashboard;

		if ( $have_dashboard ) {

			$this->preparation();
		}

		$config = $this->config();

		if ( $have_dashboard || ! empty( $config['apply-libs'] ) ) {

			( new Apply( $this->manager_base ) )->run();
		}

		/**
		 * After initializing
		 *
		 * @since 1.0.0
		 */
		// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		do_action( sprintf( '%s/%s/after/init', $this->company_name, $this->product_id() ) );

		return true;
	}

	/**
	 * @return string
	 */
	public function get_company_name(): string {

		return $this->company_name;
	}

	/**
	 * @param string $company_name
	 */
	public function set_company_name( string $company_name ): void {

		$this->company_name = $company_name;
	}

	/**
	 * Retrieve the plugin core config.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function config(): array {

		return [];
	}

	/**
	 * Dashboard Preparation.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function preparation(): bool {

		new Dashboard( $this->manager_base,
			[
				'version'      => $this->version(),
				'product-name' => $this->product_id(),
				'panel-id'     => sprintf( '%s-dashboard', $this->get_company_name() ),
				'menu_title'   => __( 'WP Shield', 'wpshield' ),
			]
		);

		return true;
	}

	/**
	 * Retrieve activation hook is registered?
	 *
	 * @since 1.0.0
	 * @return bool true on success,false when failure.
	 */
	public function activation_hook(): bool {

		return true;
	}

	/**
	 * Retrieve deactivation hook is registered?
	 *
	 * @since 1.0.0
	 * @return bool true on success,false when failure.
	 */
	public function deactivation_hook(): bool {

		return true;
	}
}
