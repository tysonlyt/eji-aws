<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\IDMExtension;

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
 * @package WPShield\Plugin\ContentProtectorPro\Modules\IDMExtension
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

		return 'idm-extension';
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

		if ( 'enable' === wpshield_cp_option( $this->id() . '/alert-popup' ) ) {

			global $_POST;

			$_POST = [
				'nonce'    => wp_create_nonce( 'popup-template' ),
				'text'     => wpshield_cp_option( $this->id() . '/alert-popup/text' ),
				'icon'     => wpshield_cp_option( $this->id() . '/alert-popup/icon' ),
				'title'    => wpshield_cp_option( $this->id() . '/alert-popup/title' ),
				'color'    => wpshield_cp_option( $this->id() . '/alert-popup/color' ),
				'template' => wpshield_cp_option( $this->id() . '/alert-popup/template' ),
			];

			\WPShield\Plugin\ContentProtectorPro\Core\Utils::make_disable_js_secure_file( $this->id() );
		}

		return true;
	}

	public function clear_data(): bool {

		return true;
	}

	public function assets(): array {

		$this->plugin = ContentProtectorSetup::instance();

		return [];
	}

	public function active(): bool {

		return true;
	}

	public function l10n(): array {

		$upload_dir = wp_upload_dir();

		$redirect_to = 'enable' === wpshield_cp_option( $this->id() . '/alert-popup' ) ?
			wp_sprintf( '%s/wpshield-content-protector-pro/' . $this->id() . '/index.php', $upload_dir['baseurl'] ) :
			home_url( '/404.php' );

		return [
			'object'    => 'IDMExtensionL10n',
			'handle'    => sprintf( '%s-components-js', \WPShield\Plugin\ContentProtector\ContentProtectorSetup::instance()->product_id() ),
			'l10n-data' => [
				'options'            => Utils::get_component_fields(
					$this->id(),
					get_option( 'wpshield-content-protector', [] )
				),
				'disabled-shortcuts' => Utilities::get_disabled_shortcuts(),
				'redirect-to'        => $redirect_to,
			],
		];
	}
}
