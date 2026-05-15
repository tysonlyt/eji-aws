<?php

namespace WPShield\Plugin\ContentProtector\Components\TextCopyProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\{Core\Component, Core\Utils, ContentProtectorSetup};
use WPShield\Plugin\ContentProtectorPro\Features\TextCopy\Handler;

class TextCopyProtector extends Component implements Module, Installable, Localization {

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

		return 'text-copy';
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

		#Prepareing requirements.
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

		return [
			[
				'deps'    => [],
				'format'  => 'style',
				'version' => ContentProtectorSetup::instance()->version(),
				'handle'  => wp_sprintf( '%s-%s-css', $this->plugin->product_id(), $this->id() ),
				'src'     => $this->plugin->uri( 'src/Components/TextCopyProtector/css/text-copy.css' ),
			],
		];
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function l10n(): array {

		return [
			'object'    => 'TextCopyL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options'            => Utils::get_component_fields(
					$this->id(),
					get_option( $this->plugin->product_id(), [] )
				),
				'disabled-shortcuts' => Utilities::get_disabled_shortcuts(),
				'available-pro'      => [
					'mode' => class_exists( Handler::class ),
				],
				'is-filter'     => $this->is_filter(),
			],
		];
	}
}
