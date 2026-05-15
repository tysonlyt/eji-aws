<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\DisabledJavascript;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Plugin\ContentProtector\Components\DisabledJavaScriptProtector\DisabledJavaScriptProtector;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\DisabledJavascript
 */
class Handler extends Component implements Module, Installable {

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\DisabledJavascript\Creator
	 */
	protected $creator;

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

		return 'javascript';
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

		add_action( 'wp_footer', [ $this, 'protection' ] );

		return true;
	}

	/**
	 * Disabled js protection.
	 *
	 * @hooked "wp_footer"
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function protection(): void {

		if ( function_exists( 'wpshield_cp_is_amp' ) && wpshield_cp_is_amp() ) {

			return;
		}

		$type                   = wpshield_cp_option( 'javascript/type' );
		$noscript_template_file = sprintf( '%s/public/redirect.php', __DIR__ );

		if ( 'message' === $type ) {

			return;
		}

		if ( 'redirect' === $type ) {

			$redirect_page = wpshield_cp_option( 'javascript/redirect/page' );

			$url = get_permalink( (int) $redirect_page );

			if ( ! $url ) {

				$wp_post = get_post( (int) $redirect_page );

				if ( $wp_post ) {

					$url = esc_attr( $wp_post->guid );
				}
			}

			if ( cpp_get_current_url() === $url || cpp_get_current_url() === rtrim( $url, '/' ) ) {

				return;
			}

			if ( ! file_exists( $noscript_template_file ) ) {

				return;
			}
		} elseif ( 'blank' === $type ) {

			$upload_dir = wp_upload_dir();

			$url = sprintf( '%s/wpshield-content-protector-pro/blank.php', $upload_dir['baseurl'] );

			if ( cpp_get_current_url() === $url || cpp_get_current_url() === rtrim( $url, '/' ) ) {

				return;
			}
		}

		include $noscript_template_file;
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
	 * @return array[]
	 */
	public function assets(): array {

		return [];
	}

	public function active(): bool {

		return true;
	}
}
