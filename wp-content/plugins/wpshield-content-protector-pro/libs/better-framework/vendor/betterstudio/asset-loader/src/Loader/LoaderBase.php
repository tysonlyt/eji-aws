<?php

namespace BetterFrameworkPackage\Asset\Loader;

use BetterFrameworkPackage\Asset;

//
use BetterFrameworkPackage\Core\Module;

/**
 * File loader sub-module base class.
 *
 * @since   1.0.0
 * @package BetterStudio\Asset\Loader
 */
abstract class LoaderBase {

	/**
	 * Store the files list.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $files;

	/**
	 * Store absolute path to the root directory.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $root;

	/**
	 * LoaderBase constructor.
	 *
	 * @param string[] $files_map list of files.
	 * @param string   $root      absolute path to the root directory.
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $files_map, string $root ) {

		$this->files = $files_map;
		$this->root  = rtrim( $root, '/\\' ) . '/';
	}

	/**
	 * Return list of request headers.
	 *
	 * @since 1.0.0
	 * @return string[]
	 */
	abstract public function headers(): array;


	/**
	 * @param array $names
	 *
	 * @since 1.0.0
	 * @throws Module\Exception
	 * @return string
	 */
	public function load_files( array $names ): string {

		$content = '';

		foreach ( $names as $name ) {

			if ( ! $path = $this->path( $name ) ) {

				throw new \BetterFrameworkPackage\Core\Module\Exception( sprintf( 'wrong file name:%s', $name ), 'invalid-files' );
			}

			$content .= file_get_contents( $path );
			$content .= "\n";
		}

		return $content;
	}

	/**
	 * Handle the Http request.
	 *
	 * @since 1.0.0
	 * @throws Module\Exception
	 */
	public function handle_request(): void {

		try {

			if ( empty( $_GET['load'] ) ) {

				throw new \BetterFrameworkPackage\Core\Module\Exception('Invalid Request');
			}

			foreach ( $this->headers() as $key => $value ) {

				\BetterFrameworkPackage\Asset\header( sprintf( '%s: %s', $key, $value ) );
			}

			echo $this->load_files( explode( ',', $_GET['load'] ) );

		} catch ( \BetterFrameworkPackage\Core\Module\Exception $e ) {

			if ( function_exists( 'wp_die' ) ) {
				wp_die( 'Invalid Request' );
			}

			throw $e;
		}
	}


	/**
	 * Get the file path by file ID.
	 *
	 * @param string $id
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function path( string $id ): string {

		if ( ! isset( $this->files[ $id ] ) ) {

			return '';
		}

		$path = $this->root . $this->files[ $id ];

		if ( ! file_exists( $path ) ) {

			return '';
		}

		return $path;
	}
}
