<?php

$version = '4.1.0-dev';

require __DIR__ . '/loader-library.php';

require __DIR__ . '/loader-composer.php';

if ( ! function_exists( 'better_framework_pro_load' ) ) {

	/**
	 * @param array $framework
	 */
	function better_framework_pro_load( array $framework ) {

		define( 'BF_PRO_URI', trailingslashit( $framework['uri'] ) );
		define( 'BF_PRO_PATH', trailingslashit( $framework['path'] ) );
		define( 'BF_PRO_VERSION', $framework['version'] );

		require $framework['path'] . '/better-framework-pro.php';
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
			) );
	}

	$params['version'] = $version;
	Better_Composer_Loader::init( __DIR__ . '/vendor/' );

	$instance = Better_Library_Loader::instance( 'better-framework-pro' );
	$instance->introduce( $version, 'better_framework_pro_load', $params );

	return $instance;
};
