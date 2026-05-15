<?php

namespace WPShield\Plugin\ContentProtector\Components\IframeProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Utils};

/**
 * Class PrintProtector
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\IframeProtector
 */
class IframeProtector extends Component implements Module, Installable, Localization {

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

		return 'iframe';
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

		$url = Utils::get_current_page_url();

		if ( preg_match( '/\/embed\/.*/i', $url, $m ) ) {

			wp_safe_redirect( str_replace( $m, '', $url ) );

			exit;
		}

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

		$component_fields = Utils::get_component_fields(
			$this->id(),
			get_option( $this->plugin->product_id(), [] )
		);

		if ( ! empty( $component_fields['iframe/redirect/page'] ) ) {

			$redirect_url = get_the_permalink( (int) $component_fields['iframe/redirect/page'] );
		}

		if ( ! empty( $component_fields['iframe/watermark/file'] ) ) {

			$watermark = wp_get_attachment_image_url( (int) $component_fields['iframe/watermark/file'] );
		}

		return [
			'object'    => 'IframeL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options'      => $component_fields,
				'redirect_url' => $redirect_url ?? null,
				'watermark'    => $watermark ?? null,
			],
		];
	}
}
