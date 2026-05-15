<?php

namespace WPShield\Plugin\ContentProtector\Components\VideoProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Utils};

class VideoProtector extends Component implements Module, Installable, Localization {

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

		return 'videos';
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

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( 'disable' === wpshield_cp_option( wp_sprintf( '%s/download-button', $this->id() ) ) ) {

			return false;
		}

		add_filter( 'the_content', [ $this, 'remove_video_download_control' ] );

		return true;
	}

	/**
	 * No download video control list!
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function remove_video_download_control( string $content ): string {

		return preg_replace( '/<video\s/', '<video controlsList="nodownload"', $content );
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
			'object'    => 'VideosL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options'       => Utils::get_component_fields(
					$this->id(),
					get_option( $this->plugin->product_id(), [] )
				),
				'available-pro' => false,
				'is-filter'     => $this->is_filter(),
			],
		];
	}
}
