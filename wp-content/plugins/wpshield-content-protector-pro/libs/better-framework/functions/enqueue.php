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


if ( ! function_exists( 'bf_enqueue_modal' ) ) {
	/**
	 * Enqueue BetterFramework modals safely
	 *
	 * @param $modal_key
	 */
	function bf_enqueue_modal( $modal_key = '' ) {

		Better_Framework::assets_manager()->add_modal( $modal_key );

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
	function bf_enqueue_style( string $handle, string $src = '', array $deps = [], string $file_path = '', $ver = false ): bool {

		// check list to change for backward compatibility
		$check = [
			'better-social-font-icon' => 'bs-icons',
		];

		if ( isset( $check[ $handle ] ) ) {
			$handle = $check[ $handle ];
		}

		wp_enqueue_style( $handle, $src, $deps, $ver );

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

		wp_dequeue_style( $handle );

		return true;
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

		return wp_localize_script( $handle, $object_name, $l10n );
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

		wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

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

		wp_dequeue_script( $handle );

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
	 * @param bool        $in_footer
	 *
	 * @return bool Whether the script has been registered. True on success, false on failure.
	 */
	function bf_register_script( string $handle, string $src = '', array $deps = [], string $file_path = '', $ver = false, bool $in_footer = false ): bool {

		return wp_register_script( $handle, $src, $deps, $ver, $in_footer );
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

		return wp_add_inline_script( $handle, $data, $position );
	}
}


if ( ! function_exists( 'bf_deregister_script' ) ) {

	function bf_deregister_script( string $handle ): bool {

		wp_deregister_script( $handle );

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

		return wp_print_scripts( $handles );
	}
}

if ( ! function_exists( 'bf_add_style_file' ) ) {

	/**
	 * Append inline css content into a file
	 *
	 * @param string   $id unique name
	 * @param callable $content_cb
	 *
	 * @since 2.9.0
	 * @return bool
	 */
	function bf_add_style_file( string $id, callable $content_cb ): bool {

		if ( ! function_exists( 'wp_add_inline_style' ) ) {

			return false;
		}

		$style = $content_cb();

		if ( ! $style ) {

			return false;
		}

		// phpcs:ignore -- use polyfill-php73 if array_key_last not exists
		$handle = wp_styles()->queue[ array_key_last( wp_styles()->queue ) ];

		return wp_add_inline_style( $handle, $style );
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
	 * @since 1.0.0
	 * @return bool Whether the style has been registered. True on success, false on failure.
	 */
	function bf_register_style( string $handle, string $src = '', array $deps = [], string $file_path = '', $ver = false, string $media = 'all' ): bool {

		return wp_register_style( $handle, $src, $deps, $ver, $media );
	}
}


if ( ! function_exists( 'bf_deregister_style' ) ) {

	function bf_deregister_style( string $handle ): bool {

		wp_deregister_style( $handle );

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

		return wp_print_styles( $handles );
	}
}


if ( ! function_exists( 'bf_add_jquery_js' ) ) {
	/**
	 * Used for adding inline js to front end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_jquery_js( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_jquery_js( $code, $to_top, $force );
	}
}


if ( ! function_exists( 'bf_add_js' ) ) {
	/**
	 * Used for adding inline js to front end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_js( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_js( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_add_css' ) ) {
	/**
	 * Used for adding inline css to front end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_css( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_css( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_add_admin_js' ) ) {
	/**
	 * Used for adding inline js to back end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_admin_js( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_admin_js( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_add_admin_css' ) ) {
	/**
	 * Used for adding inline css to back end
	 *
	 * @param string $code
	 * @param bool   $to_top
	 * @param bool   $force
	 */
	function bf_add_admin_css( $code = '', $to_top = false, $force = false ) {

		Better_Framework::assets_manager()->add_admin_css( $code, $to_top, $force );

	}
}


if ( ! function_exists( 'bf_append_suffix' ) ) {
	/**
	 * Used for adding .min quickly
	 *
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	function bf_append_suffix( $before = '', $after = '' ) {

		static $suffix;

		if ( is_null( $suffix ) ) {
			$suffix = bf_is( 'dev' ) ? '' : '.min';
		}

		return $before . $suffix . $after;
	}
}

if ( ! function_exists( 'bf_enqueue_tinymce_style' ) ) {
	/**
	 * Register style for tinymce view add-on
	 *
	 * @param string $type inline|custom|extra|registered
	 * @param string $data
	 *                     bf_add_style_file() handle id if $type == extra
	 *                     unique handle id              if $type == registered
	 *                     custom inline css code        if $type == inline
	 *                     stylesheet url                if $type == inline
	 *
	 * @since 3.0.0
	 */
	function bf_enqueue_tinymce_style( $type, $data ) {

		$enqueue = [];

		if ( 'inline' === $type ) {
			$enqueue = [
				'type' => 'inline',
				'data' => $data,
			];
		} elseif ( 'custom' === $type ) {
			$enqueue = [
				'type' => 'custom',
				'url'  => $data,
			];
		} elseif ( 'extra' === $type ) {
			$enqueue = [
				'type'    => 'extra',
				'handles' => (array) $data,
			];
		} elseif ( 'registered' === $type ) {
			$enqueue = [
				'type'    => 'registered',
				'handles' => (array) $data,
			];
		}
		if ( empty( BF_Shortcodes_Manager::$tinymce_extra_enqueues['styles'] ) ) {
			BF_Shortcodes_Manager::$tinymce_extra_enqueues['styles'] = [];
		}

		BF_Shortcodes_Manager::$tinymce_extra_enqueues['styles'][] = $enqueue;
	}
}


if ( ! function_exists( '_bf_normalize_enqueue_tinymce' ) ) {
	/**
	 * @see   bf_enqueue_tinymce_style for documentation
	 * @see   BF_Shortcodes_Manager::tinymce_view_shortcode
	 *
	 * @param string $type
	 * @param string $data
	 *
	 * @since 3.0.0
	 * @return array
	 */
	function _bf_normalize_enqueue_tinymce( $type, $data ) {

		$enqueue = [];

		if ( 'inline' === $type ) {
			$enqueue = [
				'type' => 'inline',
				'data' => $data,
			];
		} elseif ( 'custom' === $type ) {
			$enqueue = [
				'type' => 'custom',
				'url'  => $data,
			];
		} elseif ( 'extra' === $type ) {
			$enqueue = [
				'type'    => 'extra',
				'handles' => (array) $data,
			];
		} elseif ( 'registered' === $type ) {
			$enqueue = [
				'type'    => 'registered',
				'handles' => (array) $data,
			];
		}

		return $enqueue;
	}
}
if ( ! function_exists( 'bf_enqueue_tinymce_style' ) ) {
	/**
	 * Register style for tinymce view add-on
	 *
	 * @param string $type inline|custom|extra|registered
	 * @param string $data
	 *                     bf_add_style_file() handle id if $type == extra
	 *                     unique handle id              if $type == registered
	 *                     custom inline css code        if $type == inline
	 *                     stylesheet url                if $type == custom
	 *
	 * @since 3.0.0
	 * @return true on success or false on failure
	 */
	function bf_enqueue_tinymce_style( $type, $data ): bool {

		if ( empty( BF_Shortcodes_Manager::$tinymce_extra_enqueues['styles'] ) ) {
			BF_Shortcodes_Manager::$tinymce_extra_enqueues['styles'] = [];
		}

		BF_Shortcodes_Manager::$tinymce_extra_enqueues['styles'][] = _bf_normalize_enqueue_tinymce( $type, $data );

		return true;
	}
}
if ( ! function_exists( 'bf_enqueue_tinymce_script' ) ) {
	/**
	 * Register style for tinymce view add-on
	 *
	 * @param string $type inline|custom|extra|registered
	 * @param string $data
	 *                     bf_add_style_file() handle id if $type == extra
	 *                     unique handle id              if $type == registered
	 *                     custom inline css code        if $type == inline
	 *                     stylesheet url                if $type == custom
	 *
	 * @since 3.0.0
	 * @return true on success or false on failure
	 */
	function bf_enqueue_tinymce_script( $type, $data ): bool {

		if ( empty( BF_Shortcodes_Manager::$tinymce_extra_enqueues['scripts'] ) ) {
			BF_Shortcodes_Manager::$tinymce_extra_enqueues['scripts'] = [];
		}

		BF_Shortcodes_Manager::$tinymce_extra_enqueues['scripts'][] = _bf_normalize_enqueue_tinymce( $type, $data );

		return true;
	}
}

if ( ! function_exists( 'bf_asset_info' ) ) {
	function bf_asset_info( string $file_path ): array {

		if ( ! preg_match( '#(.+).js$#', $file_path, $match ) ) {

			return [];
		}

		$asset_file = $match[1] . '.asset.php';

		if ( ! file_exists( $asset_file ) ) {

			return [];
		}

		return include $asset_file;
	}
}

if ( ! function_exists( 'bf_enqueue_dependencies' ) ) {

	function bf_enqueue_dependencies( string $file_path, string ...$deps ): array {

		return array_merge(
			bf_asset_info( $file_path )['dependencies'] ?? [],
			$deps
		);
	}
}