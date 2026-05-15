<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\RightClick;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Localization;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\RightClick
 */
class Handler implements Module, Localization, Installable {

	/**
	 * @implements Base structure for component module.
	 */
	use ComponentBase, Feature;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'right-click';
	}

	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		$this->prepare();

		return true;
	}

	public function clear_data(): bool {

		return true;
	}

	public function assets(): array {

		$this->plugin = ContentProtectorSetup::instance();

		return [
			[
				'deps'    => [],
				'format'  => 'style',
				'version' => $this->plugin->version(),
				'handle'  => sprintf( '%s-css', $this->id() ),
				'src'     => $this->plugin->uri( 'src/Features/RightClick/css/right-click-addons.css' ),
			],
		];
	}

	public function l10n(): array {

		return [
			'object'    => 'RightClickAddonsL10n',
			'handle'    => sprintf( '%s-components-js', \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->product_id() ),
			'l10n-data' => [
				'views' => Utils::get_cx_views(),
			],
		];
	}

	public function active(): bool {

		return true;
	}
}
