<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\Extensions;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Localization;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Components\TextCopyProtector\Utilities;
use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtector\Core\Utils;
use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;

/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\Extensions
 */
class Handler extends Component implements Module, Installable, Localization {

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

		return 'extensions';
	}

	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( $this->is_filter() ) {

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
				'src'     => $this->plugin->uri( 'src/Features/Extensions/css/extensions-addons.css' ),
			],
		];
	}

	public function active(): bool {

		return true;
	}

	public function l10n(): array {

		return [
			'object'    => 'ExtensionsL10n',
			'handle'    => sprintf( '%s-components-js', \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->product_id() ),
			'l10n-data' => [
				'options'            => Utils::get_component_fields(
					$this->id(),
					get_option( 'wpshield-content-protector', [] )
				),
				'disabled-shortcuts' => Utilities::get_disabled_shortcuts(),
			],
		];
	}
}
