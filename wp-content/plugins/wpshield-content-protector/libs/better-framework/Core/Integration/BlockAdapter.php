<?php

namespace BetterFrameworkPackage\Framework\Core\Integration;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

// use old fashion APIs
use BF_Shortcodes_Manager, BF_Shortcode;

/**
 * @`since 4.0.0
 */
class BlockAdapter implements
	\BetterFrameworkPackage\Component\Standard\Block\BlockInterface,
	\BetterFrameworkPackage\Component\Standard\Block\HaveOptions,
	\BetterFrameworkPackage\Component\Standard\Block\HaveControls {

	/**
	 * Store the BF shortcode ID.
	 *
	 * @var string
	 * @since 4.0.0
	 */
	protected $shortcode;

	/**
	 * @param string|BlockStandards\BlockInterface $shortcode BF Shortcode ID.or BlockInterface Instance
	 *
	 * @since 4.0.0
	 */
	public function __construct( $shortcode ) {

		$this->shortcode = $shortcode;
	}


	/**
	 * Get the shortcodes fields list.
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function fields(): array {

		if ( $shortcode = $this->shortcode() ) {

			return $shortcode->get_fields() ?? [];
		}

		if ( $this->shortcode instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveControls ) {

			return $this->shortcode->fields();
		}

		return [];
	}

	/**
	 * Get the shortcode fields default value.
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function defaults(): array {

		if ( $shortcode = $this->shortcode() ) {

			return $shortcode->defaults ?? [];
		}

		if ( $this->shortcode instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveControls ) {

			return $this->shortcode->defaults();
		}

		return [];
	}

	/**
	 * The shortcode ID.
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function block_id(): string {

		if ( $shortcode = $this->shortcode() ) {

			return $shortcode->id ?? '';
		}

		if ( $this->shortcode instanceof \BetterFrameworkPackage\Component\Standard\Block\BlockInterface ) {

			return $this->shortcode->block_id();
		}

		return '';
	}

	/**
	 * The shortcode name.
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function block_name(): string {

		if ( $this->shortcode instanceof \BetterFrameworkPackage\Component\Standard\Block\BlockInterface ) {

			return $this->shortcode->block_name();
		}

		if ( $options = $this->options() ) {

			return $options['name'] ?? ucwords(
				str_replace( [ '-', '_' ], ' ', $this->block_id() )
			);
		}

		return '';
	}

	/**
	 * The shortcode icon.
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function block_icon(): string {

		return $this->options()['icon_url'] ?? '';
	}

	/**
	 * Render the shortcode output.
	 *
	 * @param array|string $settings
	 * @param string       $content
	 *
	 * @since 4.0.0
	 * @return string
	 */
	public function block_render( $settings, string $content = '' ): string {

		if ( $shortcode = $this->shortcode() ) {

			return $shortcode->display(
				$shortcode->shortcode_attributes_prepare( $settings ),
				$content
			) ?? '';
		}

		if ( $this->shortcode instanceof \BetterFrameworkPackage\Component\Standard\Block\BlockInterface ) {

			return $this->shortcode->block_render( $settings, $content );
		}

		return '';
	}

	/**
	 * The shortcode custom options.
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function options(): array {

		if ( $shortcode = $this->shortcode() ) {

			return $shortcode->page_builder_settings() ?? [];
		}

		if ( $this->shortcode instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveOptions ) {

			return $this->shortcode->options();
		}

		return [];
	}

	/**
	 * The BF shortcode options.
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function shortcode_options(): array {

		if ( $shortcode = $this->shortcode() ) {

			return $shortcode->options;
		}

		return [];
	}

	/**
	 * Get the BF shortcode instance.
	 *
	 * @since 4.0.0
	 * @return BF_Shortcode|null
	 */
	public function shortcode(): ?BF_Shortcode {

		if ( is_string( $this->shortcode ) ) {

			return BF_Shortcodes_Manager::factory( $this->shortcode, [], true );
		}

		return null;
	}
}
