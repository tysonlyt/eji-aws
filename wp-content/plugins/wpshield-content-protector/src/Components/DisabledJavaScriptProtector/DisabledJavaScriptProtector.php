<?php

namespace WPShield\Plugin\ContentProtector\Components\DisabledJavaScriptProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\Component;

/**
 * Class DisabledJavaScriptProtector
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\DisabledJavaScriptProtector
 */
class DisabledJavaScriptProtector extends Component implements Module, Installable {

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

		return 'javascript';
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

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( $this->is_filter() ) {

			return false;
		}

		$this->prepare();

		add_action( 'wp_footer', [ $this, 'disabled_js_protection' ] );

		return true;
	}

	/**
	 * When disabled js protection turn on!
	 *
	 * @hooked wp_footer
	 *
	 * @since  1.0.0
	 */
	public function disabled_js_protection(): void {

		if ( wpshield_cp_is_amp() ) {

			return;
		}

		if ( 'message' !== wpshield_cp_option( 'javascript/type' ) ) {

			return;
		}

		$popup_template = $this->getPopupTemplate();

		$noscript_file = wp_sprintf( '%s/public/noscript.php', __DIR__ );

		if ( file_exists( $noscript_file ) ) {

			include $noscript_file;
		}
	}

	/**
	 * Get popup alert message template for javascript protector.
	 *
	 * @param string $template
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function getPopupTemplate( string $template = '' ): string {

		return wp_sprintf(
			'%s/Addons/PopupMessage/templates/%s.php',
			dirname( __DIR__ ),
			$template ? $template : wpshield_cp_option( 'javascript/alert-popup/template' )
		);
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
}
