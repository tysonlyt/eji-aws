<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\Phone;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Core\Encoder;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\Phone
 */
class Handler implements Module, Installable {

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\Phone\Creator
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

		return 'phone-number';
	}

	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		$this->prepare();

		add_action( 'wpshield/content-protector/components/phone-protector/content', [ $this, 'content_protection' ] );

		return true;
	}

	/**
	 * Phone number protection.
	 *
	 * @hooked 'wpshield/content-protector/components/phone-protector/content'
	 *
	 * @param string $content
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function content_protection( string $content ): string {

		$content = Encoder::instance()->filter_telephone_link( $content );

		return preg_replace_callback( Encoder::instance()->get_phone_number_regex(),
			[ Encoder::instance(), 'dynamic_js_encoding' ],
			$content
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
	 * @return array[]
	 */
	public function assets(): array {

		return [];
	}

	public function active(): bool {

		return true;
	}
}
