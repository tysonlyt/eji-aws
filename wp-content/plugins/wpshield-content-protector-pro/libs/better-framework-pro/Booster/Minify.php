<?php

namespace BetterStudio\Framework\Pro\Booster;

/**
 * Merge and compress static js/css files.
 *
 * @since 2.9.0
 */
abstract class Minify extends \WP_Dependencies {

	/**
	 * Relative path to cache directory
	 *
	 * @var string
	 *
	 * @since 2.9.0
	 */
	public static $cache_dir = 'bs-booster-cache';

	/**
	 * Static files path
	 *
	 * @var array
	 *
	 * @since 2.9.0
	 */
	public $files_path = array();


	/**
	 * @return mixed
	 */
	abstract public function print_output();


	/**
	 * Callback to compress files content
	 *
	 * @param string $content
	 *
	 * @since 2.9.0
	 * @return string
	 */
	public function minify( string $content ): string {

		return $content;
	}


	/**
	 * Callback to compress sanitize files content
	 *
	 * @param string $content
	 * @param string $handle
	 *
	 * @since 2.9.0
	 * @return string
	 */
	public function sanitize( string $content, string $handle ): string {

		return $content;
	}


	/**
	 * Put contents into a file
	 *
	 * @param string $file_path full path to file
	 * @param string $content   file content
	 *
	 * @since 2.9.0
	 * @return bool true on success or false on failure
	 */
	public static function write_file( string $file_path, string $content ): bool {

		$dir = dirname( $file_path );

		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		return bf_file_system_instance()->put_contents(
			$file_path,
			$content
		);
	}


	public function is_dir_writable( string $dir_path ): bool {

		if ( ! is_dir( $dir_path ) ) {
			wp_mkdir_p( $dir_path );
		}

		return is_writable( $dir_path );
	}


	/**
	 * Get file content
	 *
	 * @param string $handle handle name
	 *
	 * @since 2.9.0
	 * @return bool|string string on success or false on failure
	 */
	public function get_file_content( string $handle ) {

		if ( ! empty( $this->files_path[ $handle ] ) && is_readable( $this->files_path[ $handle ] ) ) {

			return bf_get_local_file_content( $this->files_path[ $handle ] );
		}

		return false;
	}


	/**
	 * Get unique file hash
	 *
	 * @param array $handles
	 *
	 * @since 2.9.0
	 * @return string
	 */
	public function handles_hash( array $handles ): string {

		return md5( serialize( array_intersect_key( $this->registered, array_flip( $handles ) ) ) );
	}

	public function string_hash( string $string ): string {

		return md5( $string );
	}

	/**
	 * Combine and compress files content
	 *
	 * @param array  $handles
	 * @param string $new_filename
	 *
	 * @since 2.9.0
	 * @return bool true on success or false on failure
	 */
	public function do_minify( array $handles, string $new_filename ): bool {

		if ( ! bf_booster_is_active( 'minify-css' ) ) {
			return false;
		}

		if ( is_admin() ) {
			return false;
		}

		if ( ! $this->is_dir_writable( dirname( $new_filename ) ) ) {
			return false;
		}

		$output = '';

		foreach ( $handles as $handle ) {

			$content = $this->get_file_content( $handle );

			if ( $content === false ) {

				return false;
			}

			$output .= $this->sanitize( $content, $handle ) . "\n";
		}

		if ( ! $output = $this->minify( $output ) ) {

			return false;
		}

		return self::write_file( $new_filename, $output );
	}


	public static function add_hooks() {

		add_action( 'bs-booster/minify/clear-cache', [ __CLASS__, 'clear_cache' ] );
	}


	public static function register_schedule(): void {

		if ( ! wp_next_scheduled( 'bs-booster/minify/clear-cache' ) ) {

			wp_schedule_event( time(), 'daily', 'bs-booster/minify/clear-cache' );
		}
	}


	/**
	 * Clears all minified caches (files)
	 *
	 * @param string $type all|outdated
	 *
	 * @return bool
	 */
	public static function clear_cache( string $type = 'outdated' ): bool {

		$dir         = trailingslashit( WP_CONTENT_DIR . '/' . self::$cache_dir );
		$file_system = bf_file_system_instance();

		if ( $files = $file_system->dirlist( $dir, false, false ) ) {

			$files = array_keys( $files );

		} else {

			return false;
		}

		$current_time = time();
		$duration     = DAY_IN_SECONDS * 2;

		if ( $type === 'outdated' ) {

			foreach ( $files as $file ) {

				$delete_file = $duration < ( $current_time - fileatime( $dir . $file ) );

				if ( $delete_file ) {

					$file_system->delete( $dir . $file );
				}
			}

		} else {

			foreach ( $files as $file ) {

				$file_system->delete( $dir . $file );
			}
		}

		return true;
	} // clear_cache
}


if ( is_admin() ) {
	Minify::register_schedule();
}

Minify::add_hooks();
