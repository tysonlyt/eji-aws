<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions;

use WPShield\Core\PluginCore\Core\Contracts\Creator as BaseCreator;
use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Localization;
use WPShield\Core\PluginCore\PluginSetup;
use WPShield\Core\PluginCore\Core\Contracts\Bootstrap;
use WPShield\Plugin\ContentProtector\Core\Managers\ComponentsManager;
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions as PluginBase;
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core\Utils;

#Constants declarations.
define( 'WPSHIELD_CPP_CORE_FAC__FILE__', __FILE__ );
define( 'WPSHIELD_CPP_CORE_FAC_PLUGIN_BASE', plugin_basename( WPSHIELD_CPP_CORE_FAC__FILE__ ) );
define( 'WPSHIELD_CPP_CORE_FAC_PATH', plugin_dir_path( WPSHIELD_CPP_CORE_FAC__FILE__ ) );
define( 'WPSHIELD_CPP_CORE_FAC_URL', plugins_url( '/', WPSHIELD_CPP_CORE_FAC__FILE__ ) );

/**
 * Class FilterAndConditionsSetup
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions
 */
class FilterAndConditionsSetup extends PluginSetup implements Bootstrap {

	/**
	 * Store namespaces of Creators module.
	 *
	 * @var BaseCreator[] $components_stack
	 */
	public $components_stack = [
		'user'       => PluginBase\Components\User\Creator::class,
		'post-type'  => PluginBase\Components\PostType\Creator::class,
		'url'        => PluginBase\Components\CustomURL\Creator::class,
		'user-role'  => PluginBase\Components\UserRoles\Creator::class,
		'taxonomies' => PluginBase\Components\Taxonomies\Creator::class,
		'global'     => PluginBase\Components\Everywhere\Creator::class,
		'css-class'  => PluginBase\Components\CustomCssClasses\Creator::class,
	];

	/**
	 * Store a list of available filters rule name.
	 *
	 * @var string[] $filters
	 */
	protected $filters = [ 'user', 'user-role', 'post', 'url', 'css-class', 'global', 'taxonomies' ];

	/**
	 * Store results of components after running.
	 *
	 * @var array $components_result
	 */
	protected $components_result = [];

	/**
	 * @var PluginBase\Core\BaseModule $current_component
	 */
	private $current_component;

	/**
	 * @var string $running_protector
	 */
	protected $running_protector;

	/**
	 * Store instance of ComponentsManager of free plugin.
	 *
	 * @var ComponentsManager $manager
	 */
	protected $manager;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function file(): string {

		return WPSHIELD_CPP_CORE_FAC__FILE__;
	}

	/**
	 * Retrieve plugin released version number.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function version(): string {

		return '1.0.0';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_id(): string {

		return 'filter-and-conditions';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function product_name(): string {

		return __( 'Filter and Conditions', 'wpshield-content-protector-pro' );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init_components(): bool {

		#Add Extension to wpshield/content-protector plugin.
		add_filter( 'wpshield/content-protector/extensions', [ $this, 'add_extension' ] );

		#Filter and add new features support.
		add_action( 'wpshield/content-protector/core/manager/components', [ $this, 'add_components_addons' ] );

		#Fire functionalities after register all components.
		add_action( 'wpshield/content-protector/components/components-registered', [ $this, 'handle' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );

		return true;
	}

	/**
	 * Add current plugin to extensions list.
	 *
	 * @hooked "wpshield/content-protector/extensions"
	 *
	 * @param array $extensions
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function add_extension( array $extensions ): array {

		return array_merge( $extensions, [ $this->product_id() ] );
	}

	/**
	 * Adding filter components to content-protector plugin.
	 *
	 * @param array $components
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function add_components_addons( array $components ): array {

		return array_merge( $components ?? [], $this->get_components_stack() );
	}

	/**
	 * Localization scripts.
	 *
	 * @since 1.0.0
	 */
	public function l10n_scripts(): void {

		foreach ( $this->components_result as $result ) {

			$result = $result['css-class'] ?? [];

			if ( ! $result || ! isset( $result['component'], $result['filter'], $result['protector'] ) ) {

				continue;
			}

			/**
			 * @var Localization|PluginBase\Core\BaseModule $component
			 */
			$component = $result['component'];

			if ( ! $component instanceof PluginBase\Core\BaseModule || ! $component instanceof Localization ) {

				continue;
			}

			$component->set_filter( $result['filter'] );

			wp_localize_script(
				sprintf( '%s-components-app', $this->product_id() ),
				sprintf(
					'%s%sL10n',
					Utils::get_l10n_object_name( 'css-class' ),
					Utils::get_l10n_object_name( $result['protector'] )
				),
				$component->l10n()
			);
		}
	}

	/**
	 * Handling implements filter components.
	 *
	 * Retrieve details of implemented filter components.
	 *
	 * @param ComponentsManager $manager
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function handle( ComponentsManager $manager ): void {

		$this->manager = $manager;
		$_details      = [];

		foreach ( array_keys( $manager->get_components_names() ) as $protector ) {

			$option_key = sprintf( '%s/filters', $protector );
			$get_option = wpshield_cp_option( $option_key );

			if ( ! $get_option ) {

				continue;
			}

			#Extract filters selected with remove empty values.
			$filters = Utils::array_deep_filter( $get_option );

			$this->running_protector = $protector;

			#Execution protector filters with ComponentsManager API.
			$_details = array_filter(
				array_merge(
					$_details ?? [],
					//Execution any filters.
					array_map( [ $this, 'execute' ], $filters )
				)
			);

			$this->components_result[ $protector ] = array_merge( ...$_details );

			$_details = [];
		}

		#Handling implements filter components.
		update_option( 'filter-and-conditions/handle/details', $this->components_result ?? [], false );
	}

	/**
	 * Retrieve a component of manager with identifier.
	 *
	 * @param string $id
	 *
	 * @since 1.0.0
	 * @return Installable|null
	 */
	protected function get_manager_component( string $id ): ?Installable {

		foreach ( $this->manager->get_components() as $component ) {

			if ( ! in_array( $component->id(), $this->filters, true ) ) {

				continue;
			}

			if ( $component instanceof Installable && $component->id() === $id ) {

				return $component;
			}
		}

		return null;
	}

	/**
	 * Execution protector filters fields.
	 *
	 * @param array $filter
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function execute( array $filter ): array {

		if ( ! isset( $filter['in'] ) ) {

			return [];
		}

		$component = $this->get_manager_component( $filter['in'] );

		return [
			$filter['in'] => [
				'component' => $component,
				'protector' => $this->running_protector,
				'filter'    => $filter,
				'type'      => $filter['type'] ?? 'include',
			],
		];
	}

	/**
	 * Enqueue assets.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets(): void {

		wp_enqueue_script(
			sprintf( '%s-components-app', $this->product_id() ),
			WPSHIELD_CPP_CORE_FAC_URL . 'dist/app.min.js',
			[],
			$this->version(),
			true
		);

		$this->l10n_scripts();
	}

	/**
	 * Retrieve components stack list.
	 *
	 * @return BaseCreator[]
	 */
	public function get_components_stack(): array {

		return $this->components_stack;
	}

	/**
	 * Retrieve filter component by identifier param.
	 *
	 * @param string $id filter component identifier.
	 *
	 * @return string|null The namespace of component creator class.
	 */
	public function get_component( string $id ): ?string {

		return $this->components_stack[ $id ] ?? null;
	}

	/**
	 * Add creator object of component to stack!
	 *
	 * @param BaseCreator $creator
	 * @param string      $id
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	public function add_components( BaseCreator $creator, string $id ): bool {

		$this->components_stack[ $id ] = $creator;

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function dir( string $directory = '' ): string {

		if ( ! empty( $directory ) ) {

			return sprintf(
				'%s%s',
				WPSHIELD_CPP_CORE_FAC_PATH,
				$directory
			);
		}

		return WPSHIELD_CPP_CORE_FAC_PATH;
	}

	/**
	 * @inheritDoc
	 *
	 * @param string $directory
	 *
	 * @return string
	 */
	public function uri( string $directory = '' ): string {

		if ( ! empty( $directory ) ) {

			return sprintf(
				'%s%s',
				WPSHIELD_CPP_CORE_FAC_URL,
				$directory
			);
		}

		return WPSHIELD_CPP_CORE_FAC_URL;
	}
}
