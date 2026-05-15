<?php

namespace BetterFrameworkPackage\Component\Control\Typography;

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use wp APIs
use WP_HTTP_Response;

class TypographyAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	/**
	 * @var array
	 * @since 1.0.0
	 */
	protected $params;

	/**
	 * Handle the control ajax request.
	 *
	 * @param array $params
	 *
	 * @since 1.0.0
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response {

		$this->params = $params;

		if ( empty( $this->params['action'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid request' );
		}

		if ( $this->params['action'] === 'add-font' ) {

			$response = $this->add_font();

		} else {

			$response = $this->localize();
		}

		return $this->response( $response );
	}


	protected function localize(): array {

		return apply_filters(
			'better-framework/fonts-manager/localized-items',
			[
				'type'                => 'panel',

				// Fonts lib
				'fonts'               => $this->get_all_fonts(),
				'admin_fonts_css_url' => get_site_url() . '/?better_fonts_manager_custom_css=1&ver=' . time(),

				'texts'               => [
					'variant_100'       => __( 'Ultra-Light 100', 'better-studio' ),
					'variant_300'       => __( 'Book 300', 'better-studio' ),
					'variant_400'       => __( 'Normal 400', 'better-studio' ),
					'variant_500'       => __( 'Medium 500', 'better-studio' ),
					'variant_700'       => __( 'Bold 700', 'better-studio' ),
					'variant_900'       => __( 'Ultra-Bold 900', 'better-studio' ),
					'variant_100italic' => __( 'Ultra-Light 100 Italic', 'better-studio' ),
					'variant_300italic' => __( 'Book 300 Italic', 'better-studio' ),
					'variant_400italic' => __( 'Normal 400 Italic', 'better-studio' ),
					'variant_500italic' => __( 'Medium 500 Italic', 'better-studio' ),
					'variant_700italic' => __( 'Bold 700 Italic', 'better-studio' ),
					'variant_900italic' => __( 'Ultra-Bold 900 Italic', 'better-studio' ),

					'subset_unknown'    => __( 'Unknown', 'better-studio' ),
					'parent_font'       => __( 'Parent Font (%s)', 'better-studio' ),
					'parent_font_2'     => __( 'Parent Font', 'better-studio' ),
				],
				'labels'              => [
					'types'             => [
						'theme_fonts'     => __( 'Theme Fonts', 'better-studio' ),
						'google_fonts'    => __( 'Google Fonts', 'better-studio' ),
						'google_ea_fonts' => __( 'Google Early Access Font', 'better-studio' ),
						'custom_fonts'    => __( 'Custom Fonts', 'better-studio' ),
						'typekit_font'    => __( 'TypeKit Fonts', 'better-studio' ),
						'font_stacks'     => __( 'Font Stack', 'better-studio' ),
					],

					'style'             => __( '%s style(s)', 'better-studio' ),
					'search'            => __( 'Search Font...', 'better-studio' ),

					'preview_text'      => \function_exists( 'bf_get_option' ) ? bf_get_option( 'typo_text_font_manager', 'better-framework-custom-fonts' ) : 'The face of the moon was in shadow',
					'choose_font'       => __( 'Choose a font', 'better-studio' ),
					'upload_font'       => __( 'Upload Custom Font', 'better-studio' ),
					'add_font'          => __( 'Add Custom Font', 'better-studio' ),

					'filter_cat_title'  => __( 'Category', 'better-studio' ),
					'filter_cats'       => [
						'serif'       => __( 'Serif', 'better-studio' ),
						'sans-serif'  => __( 'Sans Serif', 'better-studio' ),
						'display'     => __( 'Display', 'better-studio' ),
						'handwriting' => __( 'Handwriting', 'better-studio' ),
						'monospace'   => __( 'Monospace', 'better-studio' ),
					],

					'all_l10n'          => __( 'All Fonts', 'better-studio' ),
					'filter_type_title' => __( 'Type', 'better-studio' ),
					'filter_types'      => [
						'google_font'    => __( 'Google Fonts', 'better-studio' ),
						'custom_font'    => __( 'Custom Fonts', 'better-studio' ),
						'font_stacks'    => __( 'Font Stacks', 'better-studio' ),
						'google_ea_font' => __( 'Google Early Access Fonts', 'better-studio' ),
						'theme_font'     => __( 'Theme Fonts', 'better-studio' ),
						'typekit_font'   => __( 'TypeKit Fonts', 'better-studio' ),
					],

					'font_name'         => __( 'Font Name', 'better-studio' ),
					'font_woff'         => __( 'Font .woff', 'better-studio' ),
					'upload_woff'       => __( 'Upload .woff', 'better-studio' ),
					'font_woff2'        => __( 'Font .woff2', 'better-studio' ),
					'upload_woff2'      => __( 'Upload .woff2', 'better-studio' ),
					'font_ttf'          => __( 'Font .ttf', 'better-studio' ),
					'upload_ttf'        => __( 'Upload .ttf', 'better-studio' ),
					'font_svg'          => __( 'Font .svg', 'better-studio' ),
					'upload_svg'        => __( 'Upload .svg', 'better-studio' ),
					'font_eot'          => __( 'Font .eot', 'better-studio' ),
					'upload_eot'        => __( 'Upload .eot', 'better-studio' ),
					'font_otf'          => __( 'Font .otf', 'better-studio' ),
					'upload_otf'        => __( 'Upload .otf', 'better-studio' ),
				],
			]
		);
	}

	/**
	 * @return array{success: mixed, new_font_id: mixed}
	 */
	protected function add_font(): array {

		$options = get_option( 'better-framework-custom-fonts' );

		if ( ! isset( $options['custom_fonts'] ) ) {

			$options['custom_fonts'] = [];
		}

		$new_font_id = sanitize_text_field( $this->params['data']['font-name'] ?? '' );

		$options['custom_fonts'][] = [
			'id'    => $new_font_id,
			'woff2' => sanitize_text_field( $this->params['data']['woff2'] ?? '' ),
			'woff'  => sanitize_text_field( $this->params['data']['woff'] ?? '' ),
			'ttf'   => sanitize_text_field( $this->params['data']['ttf'] ?? '' ),
			'svg'   => sanitize_text_field( $this->params['data']['svg'] ?? '' ),
			'eot'   => sanitize_text_field( $this->params['data']['eot'] ?? '' ),
			'otf'   => sanitize_text_field( $this->params['data']['otf'] ?? '' ),

		];

		$success = update_option( 'better-framework-custom-fonts', $options );

		return compact( 'success', 'new_font_id' );
	}

	/**
	 * List all available fonts by font category
	 *
	 * @return array
	 */
	public function get_all_fonts(): array {

		return [
			'theme_fonts'     => \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Theme_Fonts_Helper::get_all_fonts(),
			'font_stacks'     => \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Font_Stacks_Helper::get_all_fonts(),
			'google_fonts'    => \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_Fonts_Helper::get_all_fonts(),
			'custom_fonts'    => \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Custom_Fonts_Helper::get_all_fonts(),
			'typekit_fonts'   => \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Typekit_Fonts_Helper::get_all_fonts(),
			'google_ea_fonts' => \BetterFrameworkPackage\Component\Control\Typography\old\BF_FM_Google_EA_Fonts_Helper::get_all_fonts(),
		];
	}
}
