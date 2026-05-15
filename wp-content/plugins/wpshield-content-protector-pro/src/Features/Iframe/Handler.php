<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\Iframe;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;

/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\Iframe
 */
class Handler extends Component implements Module, Installable {

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\Iframe\Creator
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

		return 'iframe';
	}

	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( 'blank' === wpshield_cp_option( $this->id() . '/type' ) ) {
			
			header( 'X-Frame-Options: SAMEORIGIN' );
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
	 * @return array[]
	 */
	public function assets(): array {

		return [];
	}

	public function active(): bool {

		return true;
	}
}
