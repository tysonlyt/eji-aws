<?php

namespace WPShield\Plugin\ContentProtector\Components\Addons\PopupMessage;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\ContentProtectorSetup;

/**
 * Class PopupMessage
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Addons\PopupMessage
 */
class PopupMessage implements Module, Installable, Localization {

	/**
	 * implements component base functionalities.
	 */
	use Base;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function id(): string {

		return 'popup-message-addons';
	}

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function active(): bool {

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @return bool
	 */
	public function operation(): bool {

		$this->prepare();

		return true;
	}

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function clear_data(): bool {

		return true;
	}

	/**
	 * @inheritDoc
	 * @return array
	 */
	public function assets(): array {

		$plugin = ContentProtectorSetup::instance();

		return apply_filters( 'wpshield/content-protector/addons/popup-message/assets',
			[
				[
					'deps'    => [],
					'format'  => 'style',
					'version' => $plugin->version(),
					'handle'  => wp_sprintf( '%s-%s-css', $plugin->product_id(), $this->id() ),
					'src'     => $this->plugin->uri( 'src/Components/Addons/PopupMessage/css/popup-message.css' ),
				],
			]
		);
	}

	/**
	 * @inheritDoc
	 * @return array
	 */
	public function l10n(): array {

		$protectors = [
			'feed',
			'print',
			'images',
			'audios',
			'videos',
			'iframe',
			'text-copy',
			'extensions',
			'javascript',
			'right-click',
			'view-source',
			'phone-number',
			'idm-extension',
			'email-address',
			'developer-tools',
		];

		$templates = [];

		foreach ( $protectors as $protector ) {

			$templates[ wp_sprintf( '%s/template-1', $protector ) ] = $this->get_template( 'template-1', $protector );
		}

		return [
			'object'    => 'PopupMessageL10n',
			'handle'    => wp_sprintf( '%s-components-js', ContentProtectorSetup::instance()->product_id() ),
			'l10n-data' => [
				'templates' => apply_filters( 'wpshield/content-protector/addons/popup-message/templates', $templates, $this, $protectors ),
				'ajax-url'  => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'popup-message-nonce-addons' ),
			],
		];
	}

	/**
	 * Get template of popup message.
	 *
	 * @param string $id
	 * @param string $protector
	 * @param string $template_file
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_template( string $id, string $protector, string $template_file = '' ): string {

		$panel_options = get_option( $this->plugin->product_id(), [] );

		$title_key = wp_sprintf( '%s/alert-popup/title', $protector );
		$title     = $panel_options[ $title_key ] ?? '';

		$content_key = wp_sprintf( '%s/alert-popup/text', $protector );
		$content     = $panel_options[ $content_key ] ?? '';

		$color_key = wp_sprintf( '%s/alert-popup/color', $protector );
		$color = $panel_options[$color_key] ?? '';

		$icon_key = wp_sprintf( '%s/alert-popup/icon', $protector );
		$icon = $panel_options[$icon_key] ?? '';

		if ( ! empty( $template_file ) && file_exists( $template_file ) ) {

			ob_start();

			include $template_file;

			return trim( ob_get_clean() );
		}

		$filename = wp_sprintf( '%s/templates/%s.php', __DIR__, $id );

		if ( ! file_exists( $filename ) ) {

			return '';
		}

		ob_start();

		include $filename;

		return trim( ob_get_clean() );
	}
}
