<?php

namespace WPShield\Plugin\ContentProtector\Core\Managers;

use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\Contracts\Creator as CoreCreator;
use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Plugin\ContentProtector\ContentProtectorSetup as Plugin;
use WPShield\Plugin\ContentProtector\Components;
use WPShield\Plugin\ContentProtector\Core\CreatorBase;

/**
 * Class ComponentsManager
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Core\Managers
 */
class ComponentsManager {

	/**
	 * Store array of Module instances.
	 *
	 * @var CoreCreator[]
	 */
	protected $components;

	/**
	 * Store instance of main module.
	 *
	 * @var Plugin $plugin
	 */
	public $plugin;

	/**
	 * ComponentsManager constructor.
	 *
	 * @param Plugin $plugin
	 *
	 * @since 1.0.0
	 */
	public function __construct( Plugin $plugin ) {

		$this->plugin = $plugin;

		/**
		 * Before register components Hook!
		 *
		 * This way to add new handler before components registration.
		 *
		 * External developers can execute custom handlers by hooking to the
		 * `wpshield/content-protector/core/managers/before/register-components` action.
		 *
		 * @since 1.0.0
		 */
		do_action( 'wpshield/content-protector/core/managers/before/register-components' );

		#Register all available components.
		$this->register_components();
	}

	/**
	 * Retrieve components names as array of string.
	 *
	 * @since 1.0.0
	 * @return CoreCreator[]
	 */
	public function get_components_names(): array {

		return apply_filters( 'wpshield/content-protector/core/manager/components',
			[
				'feed'            => Components\FeedProtector\Creator::class,
				'audios'          => Components\AudioProtector\Creator::class,
				'videos'          => Components\VideoProtector\Creator::class,
				'phone-number'    => Components\PhoneProtector\Creator::class,
				'print'           => Components\PrintProtector\Creator::class,
				'email-address'   => Components\EmailProtector\Creator::class,
				'iframe'          => Components\IframeProtector\Creator::class,
				'images'          => Components\ImagesProtector\Creator::class,
				'text-copy'       => Components\TextCopyProtector\Creator::class,
				'right-click'     => Components\RightClickProtector\Creator::class,
				'popup-message'   => Components\Addons\PopupMessage\Creator::class,
				'view-source'     => Components\ViewSourceProtector\Creator::class,
				'developer-tools' => Components\DeveloperToolsProtector\Creator::class,
				'javascript'      => Components\DisabledJavaScriptProtector\Creator::class,
			]
		);
	}

	/**
	 * Register components.
	 *
	 * This method creates a list of all the supported components by requiring the
	 * component files and initializing each one of them.
	 *
	 * External developers can register new components by hooking to the
	 * `wpshield/content-protector/components/components-registered` action.
	 *
	 * @since 1.0.0
	 */
	private function register_components(): bool {

		$this->components = [];

		foreach ( $this->get_components_names() as $creator ) {

			#When component creator class not exists!
			if ( ! class_exists( $creator ) ) {

				continue;
			}

			$creator_object = new $creator();

			if ( ! $creator_object instanceof CoreCreator ) {

				continue;
			}

			if ( is_callable( [ $creator_object, 'set_plugin' ] ) ) {

				/**
				 * @var CreatorBase $creator_object
				 */
				$creator_object->set_plugin( $this->plugin );
			}

			/**
			 * @var Installable|Module $protector
			 *
			 * @since 1.0.0
			 */
			$protector = $creator_object->factory_method();

			#Register component in plugin components list.
			$this->register( $protector );

			#Run current protector functionalities.
			$this->run( $protector );
		}

		/**
		 * After components registered.
		 *
		 * Fires after wpshield/content-protector components are registered.
		 *
		 * @param ComponentsManager $this The components manager.
		 *
		 * @since 1.0.0
		 */
		do_action( 'wpshield/content-protector/components/components-registered', $this );

		return true;
	}

	/**
	 * Running module protector.
	 *
	 * @param Module $protector
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function run( Module $protector ): bool {

		return $protector->operation();
	}

	/**
	 * Register component.
	 *
	 * This method adds a new component to the components list. It adds any given
	 * component to any given component instance.
	 *
	 * @param Installable $instance ComponentProtector instance, usually the current instance.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register( Installable $instance ): void {

		$this->components[] = $instance;
	}

	/**
	 * Retrieve components list.
	 *
	 * @since 1.0.0
	 * @return Installable[]
	 */
	public function get_components(): array {

		return $this->components;
	}
}
