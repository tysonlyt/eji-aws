<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core;

use WPShield\Core\PluginCore\Core\Contracts\Creator as CoreCreator;
use WPShield\Core\PluginCore\Core\Localization;
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\FilterAndConditionsSetup;

/**
 * Class ComponentsManager
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core
 */
class ComponentsManager {

	/**
	 * Store instance of FilterAndConditionSetup main class.
	 *
	 * @var FilterAndConditionsSetup $plugin
	 */
	protected $plugin;

	/**
	 * Store current execution protector.
	 *
	 * @var string $protector
	 */
	protected $protector;

	/**
	 * ComponentsManager constructor.
	 *
	 * @param FilterAndConditionsSetup $plugin
	 *
	 * @since 1.0.0
	 */
	public function __construct( FilterAndConditionsSetup $plugin ) {

		$this->plugin = $plugin;
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

		#When not exists filter component.
		if ( empty( $filter['in'] ) || ! $this->plugin->get_component( $filter['in'] ) instanceof CoreCreator ) {

			return [];
		}

		$creator_class = $this->plugin->get_component( $filter['in'] );

		if ( ! $creator_class ) {

			return [];
		}

		$creator = new $creator_class();

		#Running filter component functionalities.
		$filter_module = $creator->factory_method();

		if ( ! $filter_module instanceof Localization ) {

			$details[ $filter['in'] ] = [];

		} else {

			$details[ $filter['in'] ] = [
				'component' => $filter_module,
				'protector' => $this->protector,
				'module'    => $filter_module,
				'filter'    => $filter,
				'type'      => $filter['type'] ?? 'include',
			];
		}

		return $details;
	}

	/**
	 * @return string
	 */
	public function get_protector(): string {
		return $this->protector;
	}

	/**
	 * @param string $protector
	 */
	public function set_protector( string $protector ): void {
		$this->protector = $protector;
	}
}
