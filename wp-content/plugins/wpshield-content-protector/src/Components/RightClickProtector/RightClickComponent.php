<?php

namespace WPShield\Plugin\ContentProtector\Components\RightClickProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Utils};
use WPShield\Plugin\ContentProtectorPro\Features\RightClick\Handler;

/**
 * Class RightClickComponent
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\RightClickProtector
 */
class RightClickComponent extends Component implements Module, Installable, Localization {

	/**
	 * Implements component base functionalities.
	 *
	 * @since 1.0.0
	 */
	use Base;

	/**
	 * @inheritDoc
	 *
	 * @return string
	 */
	public function id(): string {

		return 'right-click';
	}

	/**
	 * @inheritDoc
	 *
	 * @return bool
	 */
	public function active(): bool {

		/**
		 * External developers can active|deactive component by hooking to the
		 * 'wpshield/content-protector/right-click-protector/active' filter.
		 */
		return apply_filters( 'wpshield/content-protector/right-click-protector/active', true );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function operation(): bool {

		#Prepareing requirements.
		$this->prepare();

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function assets(): array {

		return [];
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function l10n(): array {

		return [
			'object'    => 'RightClickL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options'       => Utils::get_component_fields(
					$this->id(),
					get_option( $this->plugin->product_id(), [] )
				),
				'available-pro' => [
					'mode' => class_exists( Handler::class ),
				],
				'exclude-hosts' => Utils::get_hosts(),
				'is-filter'     => $this->is_filter(),
			],
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function clear_data(): bool {

		return true;
	}
}
