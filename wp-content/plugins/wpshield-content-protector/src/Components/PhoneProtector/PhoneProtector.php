<?php

namespace WPShield\Plugin\ContentProtector\Components\PhoneProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Encoder};

/**
 * Class PhoneProtector
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\PhoneProtector
 */
class PhoneProtector extends Component implements Module, Installable {

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

		return 'phone-number';
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

		foreach ( $this->get_filter_hooks() as $hook ) {

			add_filter( $hook, [ $this, 'content_protector' ], 100 );
		}

		return true;
	}

	/**
	 * Phone number protection.
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function content_protector( string $content ): string {

		if ( 'char-encoding' !== wpshield_cp_option( wp_sprintf( '%s/type', $this->id() ) ) ) {

			return apply_filters( 'wpshield/content-protector/components/phone-protector/content', $content );
		}

		return preg_replace_callback(
			Encoder::instance()->get_phone_number_regex(),
			[ Encoder::instance(), 'char_encoding' ],
			$content
		);
	}

	/**
	 * Get all filter hooks.
	 *
	 * @since 1.0.0
	 * @return string[]
	 */
	public function get_filter_hooks(): array {

		return apply_filters( 'wpshield/content-protector/components/phone-protector/filter-hooks',
			[
				'the_title',
				'the_content',
				'the_excerpt',
				'get_the_excerpt',

				//Comment related
				'comment_text',
				'comment_excerpt',
				'comment_url',
				'get_comment_author_url',
				'get_comment_author_url_link',

				//Widgets
				'widget_title',
				'widget_text',
				'widget_content',
				'widget_output',
			]
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
