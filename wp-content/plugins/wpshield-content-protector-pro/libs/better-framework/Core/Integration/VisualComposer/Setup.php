<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\VisualComposer;

use \BetterFrameworkPackage\Core\{
	Module
};

class Setup implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	/**
	 * Setup module.
	 *
	 * @since 4.0.0
	 * @return bool true on success.
	 */
	public static function setup(): bool {

		add_filter( 'better-studio/blocks/integration/list', [ self::class, 'introduce_vc_integration' ] );
		add_filter( 'better-studio/controls/integration/list', [ self::class, 'introduce_fields_integration' ] );
		add_filter( 'better-framework/shortcodes/atts', [ self::class, 'attributes_decode' ], 1 );
		add_action( 'vc_backend_editor_enqueue_js_css', [ self::class, 'enqueue_assets' ] );
		add_action( 'vc_frontend_editor_enqueue_js_css', [ self::class, 'enqueue_assets' ] );
		add_filter( 'vc_shortcode_set_template_vc_column', [ self::class, 'column_filter' ] );
		add_filter( 'vc_shortcode_set_template_vc_column_inner', [ self::class, 'column_inner_filter' ] );

		return true;
	}

	/**
	 * Introduce vc integration class.
	 *
	 * @hooked better-studio/blocks/integration/list
	 * @since  4.0.0
	 */
	public static function introduce_vc_integration( array $integration ): array {

		if ( \BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\VCBlocksIntegration::is_enable() ) {

			$integration['bf-visual-composer'] = \BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\VCBlocksIntegration::class;
		}

		return $integration;
	}

	/**
	 * Introduce vc fields integration class.
	 *
	 * @hooked better-studio/controls/integration/list
	 * @since  4.0.0
	 */
	public static function introduce_fields_integration( array $integration ): array {

		$integration['bf-visual-composer'] = \BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\VCControlsIntegration::class;

		return $integration;
	}

	/**
	 * @hooked vc_backend_editor_enqueue_js_css
	 * @hooked vc_frontend_editor_enqueue_js_css
	 *
	 * @since  4.0.0
	 */
	public static function enqueue_assets(): void {

		bf_enqueue_script(
			'bf-visual-composer',
			BF_URI . 'assets/bundles/visual-composer.js',
			bf_enqueue_dependencies( BF_PATH . 'assets/bundles/visual-composer.js' ),
			BF_PATH . 'assets/bundles/visual-composer.js'
		);
	}

	/**
	 *  Handy filter to calculate columns state
	 *
	 * @hooked vc_shortcode_set_template_vc_column
	 *
	 * @param string $file
	 *
	 * @since  4.0.0
	 * @return string
	 */
	public static function column_filter( string $file ): string {

		global $_vc_column_template_file;

		$_vc_column_template_file = $file;

		return __DIR__ . '/column/vc_column.php';
	}

	/**
	 * Handy filter to calculate columns state
	 *
	 * @hooked vc_shortcode_set_template_vc_column_inner
	 *
	 * @param string $file
	 *
	 * @since  4.0.0
	 * @return string
	 */
	public static function column_inner_filter( string $file ): string {
		global $_vc_column_inner_template_file;

		$_vc_column_inner_template_file = $file;

		return __DIR__ . '/column/vc_column_inner.php';
	}

	/**
	 * Decode VC encoded shortcode attributes.
	 *
	 * @param mixed $atts
	 *
	 * @since 4.0.0
	 * @return mixed
	 */
	public static function attributes_decode( $atts ) {

		if ( ! \is_array( $atts ) ) {

			return $atts;
		}

		return array_map( [ self::class, 'decode_multiple_values' ], $atts );
	}

	/**
	 * Decode VC json encoded value.
	 *
	 * @param mixed $string
	 *
	 * @since 4.0.0
	 * @return mixed
	 */
	public static function decode_multiple_values( $string ) {

		if ( ! \is_string( $string ) ) {

			return $string;
		}

		/**
		 * VC json format:
		 *
		 * `{` => [
		 * `}` => ]
		 * ``  => "
		 */
		if ( substr( $string, 0, 1 ) !== '{' && substr( $string, 0, 3 ) !== '`{`' ) {

			return $string;
		}

		$string = preg_replace( '/\`{2}([^\`\}]+)/', '"$1', $string );
		$string = strtr(
			$string,
			[
				'``'  => '"',
				'`{`' => '[',
				'`}`' => ']',
			]
		);

		$new_value = \json_decode( $string, true );

		// check if it's valid json
		if ( JSON_ERROR_NONE !== json_last_error() ) {

			return $string;
		}

		return $new_value;
	}
}
