<?php

namespace WPShield\Plugin\ContentProtector\Components\PrintProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Utils as CoreUtils};
use WPShield\Plugin\ContentProtectorPro\Features\PrintAddon\Handler;

/**
 * Class PrintProtector
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\PrintProtector
 */
class PrintProtector extends Component implements Module, Installable, Localization {

	/**
	 * Implements component base functionalities.
	 *
	 * @since 1.0.0
	 */
	use Base;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'print';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function active(): bool {


		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function operation(): bool {

		if ( $this->is_filter() ) {

			return false;
		}

		$this->prepare();

		return true;
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
			'object'    => 'PrintL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options'            => CoreUtils::get_component_fields(
					$this->id(),
					get_option( $this->plugin->product_id(), [] )
				),
				'disabled-shortcuts' => Utils::get_disabled_shortcuts(),
				'available-pro'      => class_exists( Handler::class ),
			],
		];
	}
}
