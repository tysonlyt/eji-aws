<?php

namespace BetterFrameworkPackage\Utils\Icons;

class IconStack {

	/**
	 * Store the configuration information.
	 *
	 * @var array
	 * @since 3.16.0
	 */
	protected $config = [];

	/**
	 * The icons stack.
	 *
	 * @var array
	 * @since 3.16.0
	 */
	protected $icons = [];

	/**
	 * Store custom icon families.
	 *
	 * @var array
	 * @since 3.16.0
	 */
	protected $families = [];

	/**
	 *
	 * @param string $icons_directory Absolute path to the root icons directory.
	 *
	 * @since 3.16.0
	 */
	public function __construct( string $icons_directory = '' ) {

		$this->config['root'] = $icons_directory;
	}

	/**
	 * Push new icon to the stack.
	 *
	 * @param string $icon_id
	 * @param string $icon_group
	 * @param string $icon_version
	 *
	 * @since 3.16.0
	 * @return bool
	 */
	public function register( string $icon_id, string $icon_group, string $icon_version, string $custom_id = '' ): bool {

		$rel_path = $this->file_path( $icon_id, $icon_group, $icon_version );
		$abs_path = $this->root( $icon_group ) . '/' . $rel_path;

		if ( ! file_exists( $abs_path ) ) {

			return false;
		}

		$this->icons[ $rel_path ] = compact( 'rel_path', 'icon_group', 'icon_id', 'custom_id' );

		return true;
	}

	/**
	 * Register custom icon family.
	 *
	 * @param string $group_id  Unique ID
	 * @param string $icon_path Absolute path to the icons root directory.
	 *
	 * @since 3.16.0
	 * @return bool true on success.
	 */
	public function register_family( string $group_id, string $icon_path ): bool {

		$this->families[ $group_id ] = compact( 'icon_path' );

		return true;
	}

	/**
	 * Remove an icon existing in stack.
	 *
	 * @param string $icon_id
	 * @param string $icon_group
	 * @param string $icon_version
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function remove( string $icon_id, string $icon_group, string $icon_version ): bool {

		$rel_path = $this->file_path( $icon_id, $icon_group, $icon_version );

		if ( ! isset( $this->icons[ $rel_path ] ) ) {

			return false;
		}

		unset( $this->icons[ $rel_path ] );

		return true;
	}

	/**
	 * Whether to check if an icon is currently registered.
	 *
	 * @param string $icon_id
	 * @param string $icon_group
	 * @param string $icon_version
	 *
	 * @since 3.16.0
	 * @return bool
	 */
	public function exists( string $icon_id, string $icon_group, string $icon_version ): bool {

		$rel_path = $this->file_path( $icon_id, $icon_group, $icon_version );

		return isset( $this->icons[ $rel_path ] );
	}

	/**
	 * Get relative path to the icon file.
	 *
	 * @param string $icon_id
	 * @param string $icon_group
	 * @param string $icon_version
	 *
	 * @since 3.16.0
	 * @return string absolute path to the svg.
	 */
	public function file_path( string $icon_id, string $icon_group, string $icon_version ): string {

		return strtr(
			'vicon_version/icon_id.svg',
			compact( 'icon_id', 'icon_group', 'icon_version' )
		);
	}

	/**
	 * Get icons root directory.
	 *
	 * @since 3.16.0
	 * @return string
	 */
	public function root( string $icon_group = '' ): string {

		$root = isset( $this->families[ $icon_group ] ) ? $this->families[ $icon_group ]['icon_path'] : $this->config['root'];

		return trailingslashit( $root );
	}

	public function families():array {

		return $this->families;
	}


	/**
	 * Export the SVG file path.
	 *
	 * @since 3.16.0
	 * @return array
	 */
	public function export(): array {

		return array_map( function ( $icon ) {

			$root = $this->root( $icon['icon_group'] );

			$icon['abs_path'] = $root . $icon['rel_path'];

			return $icon;

		}, $this->icons );
	}
}
