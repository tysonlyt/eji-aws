<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

use WP_Dependencies;

final class Utils {


	public static function find_wp_js( string $handle ): array {

		return self::find_wp_dependencies( wp_scripts(), $handle );
	}


	public static function find_wp_css( string $handle ): array {

		return self::find_wp_dependencies( wp_styles(), $handle );
	}

	public static function find_wp_dependencies( WP_Dependencies $dependencies, string $handle ): array {

		if ( ! $wp_asset = self::find_wp_dependency( $dependencies, $handle ) ) {

			return [];
		}

		$assets[] = [ $wp_asset ];

		foreach ( ( $wp_asset['deps'] ?? [] ) as $dep_handle ) {

			$assets[] = self::find_wp_dependencies( $dependencies, $dep_handle );
		}

		return array_merge( ...$assets );
	}

	public static function find_wp_dependency( WP_Dependencies $dependencies, string $handle ): ?array {

		if ( ! isset( $dependencies->registered[ $handle ] ) ) {

			return null;
		}

		$script = $dependencies->registered[ $handle ];

		if ( filter_var( $script->src, FILTER_VALIDATE_URL ) ) {

			$domain_name = preg_quote( parse_url( home_url(), PHP_URL_HOST ), '#' );
			preg_match( '#(?:https?:)?//w*\.?' . $domain_name . '(.+)$#i', $script->src, $march );

			$url  = $script->src;
			$path = self::make_path_abs( $march[1] ?? '' );

		} else {

			$url  = site_url( $script->src );
			$path = self::make_path_abs( $script->src );
		}

		return [
			'is_wp' => true,
			'id'    => $handle,
			'url'   => $url,
			'path'  => $path,
			'deps'  => $script->deps,
			'ver'   => $script->ver,
		];
	}

	public static function make_path_abs( string $rel_path ): string {

		if ( empty( $rel_path ) ) {

			return '';
		}

		return rtrim( ABSPATH, '/' ) . '/' . ltrim( $rel_path, '/' );
	}
}
