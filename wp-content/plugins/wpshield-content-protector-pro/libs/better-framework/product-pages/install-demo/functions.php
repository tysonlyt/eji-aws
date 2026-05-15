<?php
/***
 *  BetterFramework is BetterStudio framework for themes and plugins.
 *
 *  ______      _   _             ______                                           _
 *  | ___ \    | | | |            |  ___|                                         | |
 *  | |_/ / ___| |_| |_ ___ _ __  | |_ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 *  | ___ \/ _ \ __| __/ _ \ '__| |  _| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 *  | |_/ /  __/ |_| ||  __/ |    | | | | | (_| | | | | | |  __/\ V  V / (_) | |  |   <
 *  \____/ \___|\__|\__\___|_|    \_| |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\
 *
 *  Copyright © 2017 Better Studio
 *
 *
 *  Our portfolio is here: https://betterstudio.com/
 *
 *  \--> BetterStudio, 2018 <--/
 */


/**
 * get image URI by attachment ID
 *
 * @see add_image_size
 *
 * @param string $size          image size identifier
 *
 * @param int    $attachment_id image attachment ID.
 *
 * @return string image attachment url on success empty string otherwise.
 */
function bf_product_demo_media_url( $attachment_id, $size = 'thumbnail' ) {

	$src = wp_get_attachment_image_src( $attachment_id, $size );

	return $src[0] ?? '';
}

/**
 * enqueue static files
 */
function bf_install_demo_enqueue_scripts() {

	if ( bf_is_product_page( 'install-demo' ) ) {

		$ver        = BF_Product_Pages::Run()->get_version();
		$css_prefix = ( is_rtl() ? '.rtl' : '' ) . ( ! bf_is( 'dev' ) ? '.min' : '' );
		$js_prefix  = ( ! bf_is( 'dev' ) ? '.min' : '' );

		wp_enqueue_style( 'bs-product-demo-styles', BF_Product_Pages::get_url( 'install-demo/assets/css/bs-product-demo' . $css_prefix . '.css' ), [], $ver );

		wp_enqueue_script( 'bs-product-demo-scripts', BF_Product_Pages::get_url( 'install-demo/assets/js/bs-product-demo' . $js_prefix . '.js' ), [], $ver, true );

		wp_localize_script(
			'bs-product-demo-scripts',
			'bs_demo_install_loc',
			[
				'checked_label'   => __( 'Include content', 'better-studio' ),
				'unchecked_label' => __( 'Only settings', 'better-studio' ),

				'install' => [
					'title'      => __( 'Are you sure to install demo?', 'better-studio' ),
					'header'     => __( 'Import Demo', 'better-studio' ),
					'body'       => wp_kses(
						__(
							'<p>This will import our predefined settings for the demo (background, template layouts, fonts, colors etc...) and our sample content.</p>
				<p>The demo can be fully uninstalled via the uninstall button. Please backup your settings to be sure that you don\'t lose them by accident.</p>
				',
							'better-studio'
						),
						bf_trans_allowed_html()
					),
					'button_yes' => __( 'Yes, Import', 'better-studio' ),
					'button_no'  => __( 'Cancel', 'better-studio' ),
				],

				'uninstall' => [
					'title'      => __( 'Are your sure to uninstall this demo?', 'better-studio' ),
					'header'     => __( 'Confirm Uninstalling Demo', 'better-studio' ),
					'body'       => __( 'By uninstalling demo all configurations from widgets, options, menus and other settings that was comes from our demo content will be removed and your settings will be rollback to before demo installation.', 'better-studio' ),
					'button_yes' => __( 'Yes, Uninstall', 'better-studio' ),
					'button_no'  => __( 'No, do not', 'better-studio' ),
				],

				'on_error' => [
					'button_ok'       => __( 'Ok', 'better-studio' ),
					'default_message' => __( 'Cannot install demo.', 'better-studio' ),
					'body'            => __( 'Please try again several minutes later or contact better studio team support.', 'better-studio' ),
					'header'          => __( 'Demo installation failed', 'better-studio' ),
					'title'           => __( 'An error occurred while installing demo', 'better-studio' ),
					'display_error'   => '<div class="bs-pages-error-section"><a href="#" class="btn bs-pages-error-copy" data-copied="' . esc_attr__( 'Copied !', 'better-studio' ) . '">' . bf_get_icon_tag( 'fa-files-o' ) . ' ' . __( 'Copy', 'better-studio' ) . '</a>  <textarea> ' . __( 'Error', 'better-studio' ) . ':  %ERROR_CODE% %ERROR_MSG% </textarea></div>',
				],

				'uninstall_error' => [
					'button_ok'       => __( 'Ok', 'better-studio' ),
					'default_message' => __( 'Cannot uninstall demo.', 'better-studio' ),
					'body'            => __( 'Please try again several minutes later or contact better studio team support.', 'better-studio' ),
					'header'          => __( 'Demo uninstalling process failed', 'better-studio' ),
					'title'           => __( 'An error occurred while uninstalling demo', 'better-studio' ),
				],

				'uninstall_start_error' => [
					'button_ok'       => __( 'Ok', 'better-studio' ),
					'default_message' => __( 'Cannot install demo.', 'better-studio' ),
					'body'            => __( 'Please click ok and try again', 'better-studio' ),
					'header'          => __( 'Demo uninstalling process failed', 'better-studio' ),
					'title'           => __( 'An error occurred while uninstalling demo', 'better-studio' ),
				],

				'install_start_error' => [
					'button_ok'       => __( 'Ok', 'better-studio' ),
					'default_message' => __( 'Cannot install demo.', 'better-studio' ),
					'body'            => __( 'Please click ok and try again', 'better-studio' ),
					'header'          => __( 'Demo installing process failed', 'better-studio' ),
					'title'           => __( 'An error occurred while installing demo', 'better-studio' ),
				],
			]
		);
	}

}

add_action( 'admin_enqueue_scripts', 'bf_install_demo_enqueue_scripts' );


/**
 * Get a demo data
 *
 * @param string $demo_id  demo id
 * @param string $language demo language code.optional,
 * @param string $page_builder
 * @param bool   $include_contents
 *
 * @since 4.0.0
 * @return array
 */
function bf_get_demo_data( string $demo_id, string $page_builder, string $language, bool $include_contents ): array {

	$cache_key   = sprintf( '%s-%s-%d', $demo_id, $language, $include_contents );
	$cache_group = 'demo-' . $page_builder;

	$cached = bf_cache_get( $cache_key, $cache_group );

	if ( ! bf_is( 'demo-dev' ) && $cached ) {

		return $cached;
	}

	$fetched = bf_fetch_demo_data( $demo_id, $page_builder, $language, $include_contents );

	! bf_is( 'demo-dev' ) && bf_cache_set( $cache_key, $fetched, $cache_group, HOUR_IN_SECONDS );

	return $fetched;
}

/**
 * Fetch a demo data
 *
 * @param string $demo_id  demo id
 * @param string $page_builder
 * @param string $language demo language code.optional, default EN
 * @param bool   $include_contents
 *
 * @since 4.0.0
 * @return array
 */
function bf_fetch_demo_data( string $demo_id, string $page_builder, string $language = 'en', bool $include_contents = false ): array {

	global $bf_demo_data_replacement;

	$request_params = compact( 'demo_id', 'language', 'page_builder', 'include_contents' );
	$request_result = bs_core_request(
		'demo-data',
		[
			'json_assoc' => true,
			'data'       => $request_params,
			'version'    => 2,
		]
	);

	$replacements = $request_result['replacements'] ?? [];
	$replacements = [ array_keys( $replacements ), array_values( $replacements ) ];

	$bf_demo_data_replacement = apply_filters( 'better-framework/product-pages/install-demo/demo-data-replacements', $replacements, $request_result, $request_params );

	return bf_map_deep(
		! is_wp_error( $request_result ) && isset( $request_result['data'] ) ? $request_result['data'] : [],
		'bf_prepare_demo_data'
	);
}

/**
 * @param array $widget
 *
 * @since 3.14.0
 * @return array
 */
function bf_convert_to_block_widget( array $widget ): array {

	if ( ! isset( $widget['widget_id'] ) ) {

		return [];
	}

	return [
		'content' => sprintf(
			'<!-- wp:better-studio/%s %s /-->',
			$widget['widget_id'],
			wp_json_encode( $widget['widget_settings'] ?? [] )
		),
	];
}

function bf_get_demos_list(): array {

	foreach ( bf_get_demos_config() as $_id => $demo_info ) {

		if ( isset( $demo_info['info'] ) ) {

			$demos[ $demo_info['id'] ?? $_id ] = $demo_info['info'];
		}
	}

	return $demos ?? [];
}

/**
 * Get list of available demos.
 *
 * @since 4.0.0
 * @return array
 */
function bf_get_demos_config(): array {

	[ $data, $is_expired ] = bf_get_transient( 'bf-demo-list', [] );

	if ( $is_expired || bf_is( 'demo-dev' ) ) {

		$results = bs_core_request(
			'demos-list',
			[
				'json_assoc' => true,
			]
		);

		$data = [];
		if ( ! is_wp_error( $results ) ) {

			$data = $results['list'] ?? [];
		}

		$data = array_filter( $data, static function ( $demo ): bool {

			return isset( $demo['info']['thumbnail'], $demo['info']['name'] );
		} );

		bf_set_transient( 'bf-demo-list', $data, $data ? HOUR_IN_SECONDS * 5 : MINUTE_IN_SECONDS * 10 );
	}

	return ! empty( $data ) ? $data : [];
}

/**
 * Internal function.
 *
 * @param string $value
 *
 * @return string
 */
function bf_prepare_demo_data( $value ) {

	global $bf_demo_data_replacement;

	return str_replace( $bf_demo_data_replacement[0], $bf_demo_data_replacement[1], $value );
}
