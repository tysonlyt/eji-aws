<?php

namespace WPShield\Plugin\ContentProtector\Components\EmailProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	Contracts\Localization,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\{Component, Encoder, Utils};

/**
 * Class PrintProtector
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\EmailProtector
 */
class EmailProtector extends Component implements Module, Installable, Localization {

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

		return 'email-address';
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
	 * Content protection for email subject.
	 *
	 * @param string $content
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function content_protector( string $content ): string {

		if ( 'char-encoding' !== wpshield_cp_option( wp_sprintf( '%s/type', $this->id() ) ) ) {

			return apply_filters( 'wpshield/content-protector/components/email-protector/content', $content );
		}

		return preg_replace_callback(
			Encoder::instance()->get_email_regex(),
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

		return apply_filters( 'wpshield/content-protector/components/email-protector/filter-hooks',
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

		return [
			'object'    => 'EmailL10n',
			'handle'    => wp_sprintf( '%s-components-js', $this->plugin->product_id() ),
			'l10n-data' => [
				'options' => $component_fields,
			],
		];
	}
}
