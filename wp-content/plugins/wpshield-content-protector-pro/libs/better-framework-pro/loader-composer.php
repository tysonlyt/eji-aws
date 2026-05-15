<?php

use Composer\Autoload\ClassLoader;

if ( ! class_exists( 'Better_Composer_Loader' ) ) {

	class Better_Composer_Loader {

		/**
		 * @var string
		 */
		public const VERSION = '1.1.0-beta-1';

		/**
		 * @var self|null
		 */
		protected static $instance = null;

		/**
		 * @var ClassLoader|null
		 */
		protected $loader = null;

		/**
		 * @var string[]
		 */
		protected $vendor_dirs = [];

		private function __construct( $vendor_dir ) {

			if ( ! class_exists( ClassLoader::class ) ) {

				require $vendor_dir . '/composer/ClassLoader.php';
			}

			if ( ! $this->loader ) {

				$this->loader = new ClassLoader();
				$this->loader->register();
			}
		}

		public static function instance( $vendor_dir ): self {

			if ( ! self::$instance instanceof self ) {

				self::$instance = new self( $vendor_dir );

				//add_action( 'plugins_loaded', [ self::$instance, 'load' ], 999 );
				add_action( 'after_setup_theme', [ self::$instance, 'load' ], 9 );
			}

			return self::$instance;
		}

		public function load(): void {

			$all_files = [];

			foreach ( $this->vendor_dirs as $vendor_dir ) {

				$psr4_file = $vendor_dir . '/composer/autoload_psr4.php';

				if ( file_exists( $psr4_file ) ) {

					$psr4      = include $psr4_file;
					$all_files = array_merge_recursive( $all_files, $psr4 );
				}
			}

			$latest_by_namespace = [];
			$latest_by_package   = [];

			foreach ( $all_files as $namespace => $paths ) {

				$latest = $this->latest_version( $paths );

				$latest_by_namespace[ $namespace ] = $latest['path'];

				if ( $latest['package_name'] ) {
					$latest_by_package[ $latest['package_name'] ] = $latest['vendor_dir'];
				}
			}

			foreach ( $latest_by_namespace as $prefix => $path ) {

				$this->loader()->addPsr4( $prefix, (array) $path );
			}

			foreach ( $this->vendor_dirs as $vendor_dir ) {

				$static_file = $vendor_dir . '/composer/autoload_files.php';

				if ( ! file_exists( $static_file ) ) {

					continue;
				}

				$static = include $static_file;

				foreach ( $static as $file_identifier => $file ) {

					[ , $package_name ] = $this->parse_file_in_vendor( $file, $vendor_dir );

					if ( $package_name ) {

						$is_latest_version = $vendor_dir === ( $latest_by_package[ $package_name ] ?? null );

						if ( ! $is_latest_version ) {

							continue;
						}
					}

					if ( ! empty( $GLOBALS['__composer_autoload_files'][ $file_identifier ] ) ) {

						continue;
					}

					$GLOBALS['__composer_autoload_files'][ $file_identifier ] = true;

					require_once $file;
				}
			}

			foreach ( $this->vendor_dirs as $vendor_dir ) {

				$classmap_file = $vendor_dir . '/composer/autoload_classmap.php';

				if ( file_exists( $classmap_file ) ) {

					$classmap = include $classmap_file;

					$this->loader()->addClassMap( $classmap );
				}
			}

			do_action( 'better-composer-loader/loaded' );
		}

		/**
		 * @param string[] $paths
		 *
		 * @return array
		 */
		public function latest_version( array $paths ): array {

			$return_first_item = function () use ( $paths ): array {

				$path = self::sanitize_path( $paths[0] );
				[ $vendor_dir, $package_name ] = $this->parse_file_path( $path );

				if ( empty( $vendor_dir ) ) {
					$vendor_dir = $path;
				}

				return compact( 'path', 'vendor_dir', 'package_name' );
			};

			if ( count( $paths ) === 1 ) {

				return $return_first_item();
			}

			foreach ( $paths as $path ) {

				[ $vendor_dir, $package_name ] = $this->parse_file_path( $path );

				if ( empty( $vendor_dir ) || empty( $package_name ) ) {

					continue;
				}

				$version_file = $vendor_dir . '/composer/installed.php';

				if ( ! file_exists( $version_file ) ) {

					continue;
				}

				$versions = include $version_file;
				$version  = $versions['versions'][ $package_name ]['version'] ?? '';

				unset( $versions );

				if ( $version ) {

					$files_version[ $version ] = compact( 'path', 'vendor_dir', 'package_name' );
				}
			}

			if ( empty( $files_version ) ) {

				return $return_first_item();
			}

			uksort( $files_version, static function ( $a, $b ) {

				return version_compare( $b, $a );
			} );

			return array_shift( $files_version );
		}


		public function parse_file_path( string $file_path ): ?array {

			foreach ( $this->vendor_dirs as $vendor_dir ) {

				if ( $package_name = $this->parse_file_in_vendor( $file_path, $vendor_dir ) ) {

					return [ $vendor_dir, $package_name ];
				}
			}

			return null;
		}

		public function parse_file_in_vendor( string $file_path, $specific_vendor_dir = null ): ?string {

			if ( ! preg_match( "#$specific_vendor_dir/*(.+)#", $file_path, $match ) ) {

				return null;
			}

			$rel_path     = explode( '/', self::sanitize_path( $match[1] ), 3 );
			$package_name = isset( $rel_path[0], $rel_path[1] ) ? sprintf( '%s/%s', $rel_path[0], $rel_path[1] ) : '';

			return $package_name ? : null;
		}

		public function loader(): ClassLoader {

			if ( ! $this->loader ) {

				throw new RuntimeException( 'Loader is not defined.' );
			}

			return $this->loader;
		}

		public static function sanitize_path( string $path ): string {

			$path = preg_replace( '#[\\\/]+#', '/', $path );

			return rtrim( $path, '/' );
		}

		public static function init( $vendor_dir ): void {

			$vendor_dir = self::sanitize_path( $vendor_dir );
			$instance   = self::instance( $vendor_dir );

			if ( ! in_array( $vendor_dir, $instance->vendor_dirs, true ) ) {

				$instance->vendor_dirs[] = $vendor_dir;
			}
		}

	}
}