<?php

$version = '1.4.1';

if ( ! class_exists( 'Better_Library_Loader' ) ) {

	require __DIR__ . '/../loader.php';
}

if ( ! function_exists( 'better_framework_oculus_load' ) ) {

	// Backward compatibility
	// Prevent bf<4 loaders to include BF by their own loader class
	add_filter( 'better-framework/oculus/loader', '__return_empty_array', PHP_INT_MAX );
	remove_action( 'after_setup_theme', [ 'BetterFramework_Oculus_Loader', 'setup_library' ], 11 );
	remove_action( 'after_setup_theme', [ 'BetterFramework_Oculus_Loader', 'setup_library' ] );

	/**
	 * @param array $library
	 */
	function better_framework_oculus_load( array $library ) {

		define( 'BS_OCULUS_URI', trailingslashit( $library['uri'] ) );
		define( 'BS_OCULUS_PATH', trailingslashit( $library['path'] ) );
		define( 'BS_OCULUS_VERSION', $library['version'] );

		require $library['path'] . '/class-bf-oculus.php';

		do_action( 'better-framework/oculus/after_setup' );
	}
}

/**
 * @param array $params {
 *
 * @type string $uri
 * @type string $path
 * }
 */
return static function ( array $params ) use ( $version ) {

	if ( empty( $params['path'] ) ) {

		$params['path'] = __DIR__;
	}

	if ( empty( $params['uri'] ) ) {

		$params['uri'] = site_url(
			str_replace(
				[ rtrim( ABSPATH, '/' ), '\\' ],
				[ '', '/' ],
				$params['path']
			)
		);
	}

	$params['version'] = $version;

	$instance = Better_Library_Loader::instance( 'better-framework-oculus' );
	$instance->introduce( $version, 'better_framework_oculus_load', $params );

	return $instance;
};
