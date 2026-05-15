<?php

namespace WPShield\Core\PluginCore;

use WPShield\Core\PluginCore\Core\Contracts\PluginPro;

/**
 * Class ManagerBase
 *
 * @since   1.0.0
 *
 * @package WPShield\Core\PluginCore
 */
class ManagerBase {

	/**
	 * Store the instance of PluginSetup class.
	 *
	 * @var PluginSetup
	 */
	protected $plugin;

	/**
	 * PluginManager constructor.
	 *
	 * @param PluginSetup $plugin
	 *
	 * @since 1.0.0
	 */
	public function __construct( PluginSetup $plugin ) {

		$this->plugin = $plugin;
	}

	/**
	 * Retrieve the details of product pages.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_product_pages(): array {

		return $this->get_config( 'product-pages' );
	}

	/**
	 * Retrieve absolute directory path.
	 *
	 * @param string $subdirectory The subdirectory path without start slashes.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function dir( string $subdirectory = '' ): string {

		$base_url = trailingslashit( __DIR__ );

		if ( ! empty( $subdirectory ) ) {

			return sprintf( '%s%s', $base_url, $subdirectory );
		}

		return $base_url;
	}

	/**
	 * Retrieve absolute directory url.
	 *
	 * @param string $subdirectory The subdirectory path without start slashes.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function url( string $subdirectory = '' ): string {

		$base_url = plugins_url( '/', __FILE__ );

		if ( ! empty( $subdirectory ) ) {

			return sprintf( '%s%s', $base_url, $subdirectory );
		}

		return trailingslashit( $base_url );
	}

	/**
	 * Retrieve external libraries absolute directory path or get library absolute path with library directory name.
	 *
	 * @param string $lib_directory
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_libs_dir( string $lib_directory = '' ): string {

		if ( ! empty( $lib_directory ) ) {

			$prefix = $this->get_config( 'libs-uri' );

			if ( ! $prefix ) {

				return $lib_directory;
			}

			return sprintf( '%s%s', $this->get_config( 'libs-dir' ) ?? '', $lib_directory );
		}

		return $this->get_config( 'libs-dir' ) ?? $lib_directory;
	}

	/**
	 * Retrieve external libraries absolute directory uri or get library absolute uri with library directory name.
	 *
	 * @param string $lib_directory
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_libs_uri( string $lib_directory ): string {

		if ( ! empty( $lib_directory ) ) {

			$prefix = $this->get_config( 'libs-uri' );

			if ( ! $prefix ) {

				return $lib_directory;
			}

			return sprintf( '%s%s', $prefix, $lib_directory );
		}

		return $this->get_config( 'libs-uri' ) ?? $lib_directory;
	}

	/**
	 * Retrieve plugin all configurations.
	 *
	 * @param string $key
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_config( string $key = '' ) {

		$config = $this->plugin->config();

		if ( ! empty( $key ) && isset( $config[ $key ] ) ) {

			return $config[ $key ];
		}

		if ( ! empty( $key ) ) {

			return null;
		}

		return $config;
	}
}
