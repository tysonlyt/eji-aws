<?php

use BetterStudio\Framework\Pro\Booster\{
	Scripts,
	Booster,
	Styles
};

if ( ! function_exists( 'bf_scripts' ) ) {

	/**
	 * @global $bf_scripts Scripts object
	 *
	 * @since 2.9.0
	 * @return Scripts
	 */
	function bf_scripts(): Scripts {

		global $bf_scripts;

		if ( ! $bf_scripts ) {

			$bf_scripts = new Scripts();
			$bf_scripts->init();
		}

		return $bf_scripts;
	}
}

if ( ! function_exists( 'bf_styles' ) ) {

	/**
	 * @global $bf_styles Styles object
	 *
	 * @since 2.9.0
	 * @return Styles
	 */
	function bf_styles(): Styles {

		global $bf_styles;

		if ( ! $bf_styles ) {

			$bf_styles = new Styles();
			$bf_styles->init();
		}

		return $bf_styles;
	}
}

if ( ! function_exists( 'bf_booster_is_active' ) ) {
	/**
	 * Returns state of Booster sections
	 *
	 * @param string $section
	 *
	 * @return bool
	 */
	function bf_booster_is_active( string $section = '' ): bool {

		return Booster::is_active( $section );
	}
}

if ( ! function_exists( 'bf_enqueue_style' ) ) {
	/**
	 * @see   wp_enqueue_style for more documentation.
	 *
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param string      $file_path
	 * @param string|bool $ver
	 */
	function bf_enqueue_style( string $handle, string $src = '', array $deps = array(), string $file_path = '', $ver = false ): bool {

		// check list to change for backward compatibility
		$check = array(
			'better-social-font-icon' => 'bs-icons'
		);

		if ( isset( $check[ $handle ] ) ) {
			$handle = $check[ $handle ];
		}

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-css' )
		) {

			wp_enqueue_style( $handle, $src, $deps, $ver );

			return true;
		}

		if ( $src ) {
			bf_styles()->add( $handle, $src, $deps, $ver, 'all' );
		}

		if ( $file_path ) {
			bf_styles()->files_path[ $handle ] = $file_path;
		}

		bf_styles()->enqueue( $handle );

		return true;
	}
}

if ( ! function_exists( 'bf_dequeue_style' ) ) {
	/**
	 * @see   wp_dequeue_style for more documentation.
	 *
	 * @param string $handle
	 */
	function bf_dequeue_style( string $handle ): bool {


		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-css' )
		) {
			wp_dequeue_style( $handle );

			return true;
		}

		bf_styles()->dequeue( $handle );

		return true;
	}
}

if ( ! function_exists( 'bf_enqueue_wp_script_deps' ) ) {

	function bf_enqueue_wp_script_deps( string $handle ) {

		if ( ! isset( bf_scripts()->registered[ $handle ] ) ) {
			return;
		}

		$deps = &bf_scripts()->registered[ $handle ]->deps;

		foreach ( $deps as $index => $dep ) {

			if ( ! isset( bf_scripts()->registered[ $dep ] ) ) {

				if ( ! isset( wp_scripts()->registered[ $dep ] ) ) {
					continue;
				}

				unset( $deps[ $index ] );

				if ( wp_scripts()->registered[ $dep ]->args === 1 ) {
					wp_scripts()->registered[ $dep ]->args = null;
				}

				if ( ! wp_script_is( $dep ) ) {
					wp_enqueue_script( $dep );
				}

			} else {

				bf_enqueue_wp_script_deps( $dep );
			}
		}
	}
}

if ( ! function_exists( 'bf_localize_script' ) ) {

	/**
	 * @param string $handle
	 * @param string $object_name
	 * @param array  $l10n
	 *
	 * @since 2.9.0
	 * @return bool
	 */
	function bf_localize_script( string $handle, string $object_name, array $l10n ): bool {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-js' )
		) {

			return wp_localize_script( $handle, $object_name, $l10n );
		}

		return bf_scripts()->localize( $handle, $object_name, $l10n );
	}
}

if ( ! function_exists( 'bf_enqueue_script' ) ) {
	/**
	 * Enqueue BetterFramework scripts safely
	 *
	 * @see   wp_enqueue_script for more documentation.
	 *
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param string      $file_path
	 * @param string|bool $ver
	 */
	function bf_enqueue_script( string $handle, string $src = '', array $deps = [], string $file_path = '', $ver = false, $in_footer = false ): bool {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-js' )
		) {
			wp_enqueue_script( $handle, $src, $deps, $ver, true );

			return true;
		}

		if ( $src ) {
			bf_scripts()->add( $handle, $src, $deps, $ver, '1' );
		}

		if ( $file_path ) {

			bf_scripts()->files_path[ $handle ] = $file_path;
		}

		if ( $in_footer ) {

			bf_scripts()->add_data( $handle, 'group', 1 );
		}

		bf_enqueue_wp_script_deps( $handle );

		bf_scripts()->enqueue( $handle );

		return true;
	}
}


if ( ! function_exists( 'bf_dequeue_script' ) ) {
	/**
	 * Enqueue BetterFramework scripts safely
	 *
	 * @see   wp_enqueue_script for more documentation.
	 *
	 * @param string $handle
	 */
	function bf_dequeue_script( string $handle ): bool {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-js' )
		) {

			wp_dequeue_script( $handle );

		} else {

			bf_scripts()->dequeue( $handle );
		}

		return true;
	}
}


if ( ! function_exists( 'bf_register_script' ) ) {

	/**
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param string      $file_path
	 * @param string|bool $ver
	 *
	 * @return bool Whether the script has been registered. True on success, false on failure.
	 */
	function bf_register_script( string $handle, string $src = '', array $deps = array(), string $file_path = '', $ver = false, bool $in_footer = false ): bool {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-js' )
		) {

			return wp_register_script( $handle, $src, $deps, $ver );
		}

		if ( $file_path ) {

			bf_scripts()->files_path[ $handle ] = $file_path;
		}

		if ( $in_footer ) {

			bf_scripts()->add_data( $handle, 'group', 1 );
		}

		return bf_scripts()->add( $handle, $src, $deps, $ver, '1' );
	}
}

if ( ! function_exists( 'bf_add_inline_script' ) ) {

	/**
	 * @param string $handle
	 * @param string $data
	 * @param string $position
	 *
	 * @return bool Whether the script has been registered. True on success, false on failure.
	 */
	function bf_add_inline_script( string $handle, string $data, string $position = 'after' ): bool {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-js' )
		) {
			return wp_add_inline_script( $handle, $data, $position );
		}

		return bf_scripts()->add_inline_script( $handle, $data, $position );
	}
}


if ( ! function_exists( 'bf_deregister_script' ) ) {

	function bf_deregister_script( string $handle ): bool {

		if ( function_exists( 'bf_booster_is_active' ) &&
		     bf_booster_is_active( 'minify-js' )
		) {

			bf_scripts()->remove( $handle );

		} else {

			wp_deregister_script( $handle );
		}

		return true;
	}
}


if ( ! function_exists( 'bf_print_scripts' ) ) {

	/**
	 * Print scripts in document head that are in the $handles queue.
	 *
	 * @param string|bool|array $handles
	 *
	 * @return string[]
	 */
	function bf_print_scripts( $handles = false ): array {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-js' )
		) {

			return wp_print_scripts( $handles );
		}

		return bf_scripts()->do_items( $handles );
	}
}


if ( ! function_exists( 'bf_add_style_file' ) ) {

	/**
	 * Append inline css content into a file
	 *
	 * @param string   $unique_id unique name
	 * @param callable $content_cb
	 *
	 * @since 2.9.0
	 * @return bool
	 */
	function bf_add_style_file( string $unique_id, callable $content_cb ): bool {

		if ( function_exists( 'bf_styles' ) ) {

			bf_styles()->add_css_file( $unique_id, $content_cb );

			return true;

		}

		if ( function_exists( 'wp_add_inline_style' ) ) {

			if ( $style = $content_cb() ) {

				$handle = wp_styles()->queue[ array_key_last( wp_styles()->queue ) ];

				return wp_add_inline_style( $handle, $style );
			}
		}

		return false;
	}
}


if ( ! function_exists( 'bf_register_style' ) ) {

	/**
	 * @param string      $handle
	 * @param string      $src
	 * @param array       $deps
	 * @param string      $file_path
	 * @param string|bool $ver
	 * @param string      $media
	 *
	 * @return bool Whether the style has been registered. True on success, false on failure.
	 */
	function bf_register_style( string $handle, string $src = '', array $deps = array(), string $file_path = '', $ver = false, string $media = 'all' ): bool {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-css' )
		) {

			return wp_register_style( $handle, $src, $deps, $ver );
		}

		if ( $file_path ) {

			bf_styles()->files_path[ $handle ] = $file_path;
		}

		return bf_styles()->add( $handle, $src, $deps, $ver, $media );
	}
}


if ( ! function_exists( 'bf_deregister_style' ) ) {

	function bf_deregister_style( string $handle ): bool {

		if ( function_exists( 'bf_booster_is_active' ) &&
		     bf_booster_is_active( 'minify-css' )
		) {

			bf_styles()->remove( $handle );

		} else {

			wp_deregister_style( $handle );
		}

		return true;
	}
}


if ( ! function_exists( 'bf_print_styles' ) ) {

	/**
	 * Print scripts in document head that are in the $handles queue.
	 *
	 * @param string|bool|array $handles
	 *
	 * @return string[]
	 */
	function bf_print_styles( $handles ): array {

		if ( ! function_exists( 'bf_booster_is_active' ) ||
		     ! bf_booster_is_active( 'minify-css' )
		) {
			return wp_print_styles( $handles );
		}

		return bf_styles()->do_items( $handles );
	}
}
