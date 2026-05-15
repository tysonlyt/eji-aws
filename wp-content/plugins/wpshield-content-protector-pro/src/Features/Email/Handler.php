<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\Email;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtector\Core\Encoder;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\Email
 */
class Handler extends Component implements Module, Installable {

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\Email\Creator
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

		return 'email-address';
	}

	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		$this->prepare();

		add_action( 'wpshield/content-protector/components/email-protector/content', [ $this, 'content_protection' ] );

		return true;
	}

	public function content_protection( string $content ): string {

		$content = Encoder::instance()->filter_mailto_link( $content );

		return preg_replace_callback( Encoder::instance()->get_email_regex(),
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
