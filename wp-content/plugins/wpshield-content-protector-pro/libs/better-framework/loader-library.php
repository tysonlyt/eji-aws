<?php

if ( ! class_exists( 'Better_Library_Loader' ) ) {

	class Better_Library_Loader {

		public const VERSION = '1.1.0';

		/**
		 * Store the loader instances.
		 *
		 * @var self[]
		 * @since 1.0.0
		 */
		protected static $instances = [];

		/**
		 * Store the library multiple version information.
		 *
		 * @var array
		 * @since 1.0.0
		 */
		protected $registered = [];

		/**
		 * Library unique identifier.
		 *
		 * @var string
		 * @since 1.0.0
		 */
		protected $id;

		protected function __construct( string $unique_id, $options = [] ) {

			$this->id = $unique_id;

			$options = array_merge( [
				'hook_name' => 'after_setup_theme',
				'priority'  => 10,
			], $options );

			add_action( $options['hook_name'], [ $this, 'setup' ], $options['priority'] );
		}

		/**
		 * Introduce/register library version.
		 *
		 * @param string   $version
		 * @param callable $callback
		 * @param array    ...$arguments
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		public function introduce( string $version, callable $callback, ...$arguments ): bool {

			if ( empty( $version ) ) {

				return false;
			}

			$this->registered[ $version ] = compact( 'callback', 'arguments' );

			return true;
		}

		/**
		 * Get the singleton instance.
		 *
		 * @param string $unique_id
		 *
		 * @since 1.0.0
		 * @return static
		 */
		public static function instance( string $unique_id, array $options = [] ): self {

			if ( ! isset( self::$instances[ $unique_id ] ) ) {

				self::$instances[ $unique_id ] = new self( $unique_id, $options );
			}

			return self::$instances[ $unique_id ];
		}

		/**
		 * Load the correct version which meet the requirements.
		 *
		 * @hooked after_setup_theme
		 * @since  1.0.0
		 * @return bool
		 */
		public function setup(): bool {

			$selected_version = '0.0.0';

			foreach ( $this->registered as $version => $current ) {
				

				if ( version_compare( $version, $selected_version, '>' ) ) {

					$selected_version = $version;
				}
			}

			return $this->load( $selected_version );
		}

		/**
		 * Load the specified version.
		 *
		 * @param string $version
		 *
		 * @since 1.0.0
		 * @return bool
		 */
		public function load( string $version ): bool {

			if ( empty( $this->registered[ $version ] ) ) {

				return false;
			}

			$callback  = &$this->registered[ $version ]['callback'];
			$arguments = &$this->registered[ $version ]['arguments'];

			$result = $callback( ...$arguments );

			do_action( 'better-studio/loader/loaded', $version, $this, $result );

			return true;
		}

		/**
		 * Get the library unique identifier.
		 *
		 * @since 1.0.0
		 * @return string
		 */
		public function id(): string {

			return $this->id;
		}

		/**
		 * Get register version info.
		 *
		 * @param string|null $version
		 *
		 * @since 1.0.0
		 * @return array|null
		 */
		public function get( string $version = null ): ?array {

			if ( ! isset( $version ) ) {

				return $this->registered;
			}

			return $this->registered[ $version ] ?? null;
		}


		public function is_dev(): bool {

			return ( defined( 'BF_DEV_MODE' ) && BF_DEV_MODE ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		}

		public function dev_version( string $root, string $fallback = '' ): string {

			if ( ! $git_root = $this->git_root( $root ) ) {

				return $fallback;
			}

			if ( ! $head = $this->git_head( $git_root ) ) {

				return $fallback;
			}

			if ( ! $commit_date = $this->git_commit_date( $git_root, $head ) ) {

				return $fallback;
			}

			return $commit_date;
		}


		public function git_root( string $root ): ?string {

			if ( ! is_readable( $root . '/.git' ) ) {

				return null;
			}

			if ( is_dir( $root . '/.git' ) ) {

				return $root . '/.git';
			}

			$content = file_get_contents( $root . '/.git', false, null, 0, 999 );

			if ( ! preg_match( '/gitdir\s*:\s*([^\n]+)$/i', $content, $match ) ) {

				return null;
			}

			return $this->absolute_path( $root, $match[1] );
		}

		function git_commit_date( string $git_root, string $commit_hash ): ?string {

			$match_index   = 1;
			$date_index    = 4;
			$explode_limit = 6; // max($match_index,$date_index) + 2

			if ( ! is_readable( $git_root . '/logs/HEAD' ) ) {

				return null;
			}
			$stream = fopen( $git_root . '/logs/HEAD', 'rb' );

			while ( false !== ( $line = fgets( $stream ) ) ) {

				$partials = explode( ' ', $line, $explode_limit );

				if ( isset( $partials[ $match_index ] ) && $partials[ $match_index ] === $commit_hash ) {


					$date = $partials[ $date_index ] ?? null;
					break;
				}

			}

			fclose( $stream );

			return $date ?? null;
		}

		function git_head( string $git_root ): ?string {

			if ( is_readable( $git_root . '/ORIG_HEAD' ) ) {

				return trim( file_get_contents( $git_root . '/ORIG_HEAD' ) );
			}


			if ( ! file_exists( $git_root . '/HEAD' ) ) {

				return null;
			}

			$content = file_get_contents( $git_root . '/HEAD', false, null, 0, 999 );

			if ( ! preg_match( '/ref\s*:\s*([^\n]+)$/i', $content, $match ) ) {

				return null;
			}

			$head_file = $this->absolute_path( $git_root, $match[1] );

			return isset( $head_file ) && is_readable( $head_file ) ? trim( file_get_contents( $head_file ) ) : null;
		}

		public function absolute_path( string $root, string $rel_path ): ?string {

			$cwd = getcwd();

			chdir( $root );

			$abs_path = realpath( $root . '/' . $rel_path );

			chdir( $cwd );

			return $abs_path ? : null;
		}

	}
}
