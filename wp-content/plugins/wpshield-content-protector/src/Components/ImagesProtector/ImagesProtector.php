<?php

namespace WPShield\Plugin\ContentProtector\Components\ImagesProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Utils, Utils\WPQueryUtils};

class ImagesProtector extends Component implements Module, Installable, Localization {

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

		return 'images';
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

		$disable_media_pages = wpshield_cp_option( 'images/disable-attachment-pages' );


		if ( 'disable' !== $disable_media_pages && Utils\CurrentPageHelper::is_attachment() ) {

			if ( 'home' === $disable_media_pages ) {

				Utils\RedirectHelper::do_unsafe_redirect( home_url(), 301 );

				return true;
			}

			$attachments_helper = Utils\AttachmentsHelper::get_attachment_related_posts(
				WPQueryUtils::get_main_query()->post->ID
			);

			if ( $attachments_helper ) {

				$related_post_url = get_the_permalink( $attachments_helper->first() );
			}

			if ( ! $related_post_url || Utils\CurrentPageHelper::attachment_url() === $related_post_url ) {

				Utils\RedirectHelper::do_unsafe_redirect( home_url(), 301 );

				return true;
			}

			Utils\RedirectHelper::do_unsafe_redirect( $related_post_url, 301 );
		}

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
			'object'    => 'ImagesL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options'   => Utils::get_component_fields(
					$this->id(),
					get_option( $this->plugin->product_id(), [] )
				),
				'is-filter' => $this->is_filter(),
			],
		];
	}

}
