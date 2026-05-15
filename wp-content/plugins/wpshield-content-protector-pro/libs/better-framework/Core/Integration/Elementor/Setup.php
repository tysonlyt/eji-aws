<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

use \BetterFrameworkPackage\Core\{
	Module
};

use Elementor\Plugin as ElementorPlugin;

class Setup implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	/**
	 * Setup module.
	 *
	 * @since 4.0.0
	 * @return bool true on success.
	 */
	public static function setup(): bool {

		add_action( 'elementor/editor/after_enqueue_scripts', [ self::class, 'admin_enqueue_script' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ self::class, 'admin_enqueue_style' ] );
		add_filter( 'better-studio/blocks/integration/list', [ self::class, 'introduce_elementor_integration' ] );
		add_filter( 'better-studio/controls/integration/list', [ self::class, 'introduce_fields_integration' ] );
		add_action( 'elementor/editor/init', 'ob_start', 1, 0 );
		add_action( 'elementor/editor/footer', [ self::class, 'modify_templates' ], 99999 );

		return true;
	}

	/**
	 * Introduce elementor integration class.
	 *
	 * @hooked better-studio/blocks/integration/list
	 * @since  4.0.0
	 */
	public static function introduce_elementor_integration( array $integration ): array {

		if ( \BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorBlocksIntegration::is_enable() ) {

			$integration['bf-elementor'] = \BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorBlocksIntegration::class;
		}

		return $integration;
	}

	/**
	 * Introduce elementor fields integration class.
	 *
	 * @hooked better-studio/controls/integration/list
	 * @since  4.0.0
	 */
	public static function introduce_fields_integration( array $integration ): array {

		$integration['bf-elementor'] = \BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorControlsIntegration::class;

		return $integration;
	}

	public static function admin_enqueue_script(): void {

		bf_enqueue_script(
			'bf-elementor',
			BF_URI . 'assets/bundles/elementor.js',
			bf_enqueue_dependencies( BF_PATH . 'assets/bundles/elementor.js' ),
			BF_PATH . 'assets/bundles/elementor.js'
		);
	}

	public static function admin_enqueue_style(): void {

		bf_enqueue_style( 'bf-elementor', BF_URI . 'assets/css/elementor.css' );

		if ( $colors = self::admin_colors() ) {

			wp_add_inline_style(
				'elementor-editor',
				'body{
			--bf-elementor-color-scheme: ' . $colors[1] . '
		}'
			);
		}
	}

	public static function admin_colors(): array {
		global $_wp_admin_css_colors;

		$current_color = get_user_option( 'admin_color' );

		if ( empty( $current_color ) ) {
			$current_color = 'fresh';
		}

		return $_wp_admin_css_colors[ $current_color ]->colors ?? [];
	}

	/**
	 * Whether the elementor preview mode is active
	 *
	 * @param int $post_id
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public static function is_preview( int $post_id = 0 ): bool {

		return ElementorPlugin::$instance->preview->is_preview_mode( $post_id );
	}

	/**
	 * @hooked publisher-tools/builder/new-template/rest-response
	 *
	 * @param array $response
	 *
	 * @since  4.0.0
	 * @return array
	 */
	public static function elementor_redirect( array $response ): array {

		if ( isset( $response['redirect_url'] ) ) {

			$response['redirect_url'] = add_query_arg( 'action', 'elementor', html_entity_decode( $response['redirect_url'] ) );
		}

		return $response;
	}
	/**
	 * Modify elementor core library.
	 *
	 * @since 4.0.0
	 */
	public static function modify_templates(): void {

		$template_id = 'tmpl-elementor-element-library-element';
		$content     = ob_get_clean();
		$content     = preg_replace(
			"'<\s*script [^\<\>]+ id\s*=\s*	([\"\'])? {$template_id}\\1 .*?> (.*?) </script\s*>'isx",
			self::library_element_template(),
			$content
		);

		echo $content;
	}

	/**
	 * @since 4.0.0
	 * @return string
	 */
	protected static function library_element_template(): string {

		ob_start();

		include __DIR__ . '/templates/library-element.php';

		return ob_get_clean();
	}
}
