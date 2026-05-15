<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\PrintAddon;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Localization;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Components\PrintProtector\Utils;
use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtector\Core\Utils as CoreUtils;
use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;

/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\PrintAddon
 */
class Handler extends Component implements Module, Installable, Localization {

	/**
	 * @implements Base structure for component module.
	 */
	use ComponentBase, Feature;

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\PrintAddon\Creator
	 */
	protected $creator;

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
	 * @return bool
	 */
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
				'src'     => $this->plugin->uri( 'src/Features/PrintAddon/css/print-addons.css' ),
			],
		];
	}

	public function active(): bool {

		return true;
	}

	public function l10n(): array {

		$watermark_id = wpshield_cp_option( 'print/watermark/file' );

		if ( $watermark_id ) {

			$watermark_image = wp_get_attachment_image_url( $watermark_id );
		}

		$image_opacity = wpshield_cp_option( 'print/watermark/opacity' );

		return [
			'object'    => 'PrintAddonsL10n',
			'handle'    => sprintf( '%s-components-js', \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->product_id() ),
			'l10n-data' => [
				'options'            => CoreUtils::get_component_fields(
					$this->id(),
					get_option( 'wpshield-content-protector', [] )
				),
				'opacity'            => $image_opacity,
				'watermark'          => $watermark_image ?? '',
				'type'               => wpshield_cp_option( 'print/type' ) ?? 'hotkeys',
				'popup-template'               => wpshield_cp_option( 'print/alert-popup/template' ) ?? 'template-1',
				'disabled-shortcuts' => class_exists( Utils::class ) ? Utils::get_disabled_shortcuts() : [ 'ctrl_p', 'cmd_p' ],
			],
		];
	}

}
