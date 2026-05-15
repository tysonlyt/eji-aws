<?php

$version = '4.2.0';

require __DIR__ . '/loader-library.php';

require __DIR__ . '/loader-composer.php';

if ( ! function_exists( 'better_framework_load' ) ) {

	/**
	 * @param array $framework
	 */
	function better_framework_load( array $framework ) {

		define( 'BF_URI', trailingslashit( $framework['uri'] ) );
		define( 'BF_PATH', trailingslashit( $framework['path'] ) );
		define( 'BF_VERSION', $framework['version'] );

		require $framework['path'] . '/class-better-framework.php';

		/**
		 * Fires after BetterFramework fully loaded.
		 */
		do_action( 'better-framework/after_setup' );
	}
}

if ( ! function_exists( 'better_framework_v4_compatibility' ) ) {

	/**
	 * BF < v4 Backward compatibility
	 *
	 * @since 4.0.0
	 */
	function better_framework_v4_compatibility() {

		// Prevent bf<4 loaders to include BF by their own loader class
		add_filter( 'better-framework/loader', '__return_empty_array', PHP_INT_MAX );
		remove_action( 'after_setup_theme', [ 'BetterFramework_Oculus_Loader', 'setup_library' ], 11 );
		remove_action( 'after_setup_theme', [ 'BetterFramework_Oculus_Loader', 'setup_library' ] );

		require __DIR__ . '/functions/compatibility.php';
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

	Better_Composer_Loader::init( __DIR__ . '/vendor/' );

	$instance = Better_Library_Loader::instance( 'better-framework', [ 'priority' => 90 ] );

	if ( version_compare( Better_Library_Loader::VERSION, '1.1.0', '>=' ) ) {

		$version = $instance->is_dev() ? $instance->dev_version( $params['path'], $version ) : $version;
	}

	$params['version'] = $version;
	$instance->introduce( $version, 'better_framework_load', $params );

	if ( ! has_action( 'after_setup_theme', 'better_framework_v4_compatibility' ) ) {

		add_action( 'after_setup_theme', 'better_framework_v4_compatibility', 2 );
	}
	
	remove_action( 'after_setup_theme', [ 'Better_Framework_Factory', 'setup_framework' ] );

	return $instance;
};
