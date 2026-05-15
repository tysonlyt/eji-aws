<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\TextCopy;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Localization;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Components\TextCopyProtector\Utilities;
use WPShield\Plugin\ContentProtector\Core\Utils;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\TextCopy
 */
class Handler implements Module, Installable, Localization {

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

		return 'text-copy';
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

		return [];
	}

	public function active(): bool {

		return true;
	}

	public function l10n(): array {

		global $post, $wp;

		return [
			'object'    => 'TextCopyAddonL10n',
			'handle'    => sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options' => [
					'post-link'  => is_single() && isset( $post->ID ) ? get_the_permalink( $post->ID ) : home_url( $wp->request ),
					'post-title' => is_single() && isset( $post->ID ) ? get_the_title( $post->ID ) : get_bloginfo( 'name' ),
					'site-link'  => home_url(),
					'site-title' => get_bloginfo( 'name' ),
				],
			],
		];
	}
}
