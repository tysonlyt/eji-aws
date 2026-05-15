<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\Images;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;

use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;

/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\Images
 */
class Handler extends Component implements Module, Installable {

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\Images\Creator
	 */
	protected $creator;

	/**
	 * @implements Base structure for component module.
	 */
	use ComponentBase,Feature;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'images';
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

		add_filter( 'the_content', [ $this, 'the_content_protector' ] );

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
	 * Protected by remove image parent link!
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function the_content_protector( string $content ): string {

		$image_protection = 'enable' === wpshield_cp_option( 'images' );
		$remove_link      = 'enable' === wpshield_cp_option( 'images/remove-links' );

		if ( ! $image_protection || ! $remove_link ) {

			return $content;
		}

		return Utils::remove_parent_link( $content, 'img' );
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
