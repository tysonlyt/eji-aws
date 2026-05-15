<?php

namespace BetterFrameworkPackage\Utils\Icons;

use \BetterFrameworkPackage\Core\{
	Rest
};
use Exception;

class IconManager {

	/**
	 * Store the icon information.
	 *
	 * @var array {
	 *
	 * @type string $icon
	 * @type string $type
	 * @type string $version
	 *
	 * }
	 * @since 3.16.0
	 */
	protected $icon;

	/**
	 * @var IconStack
	 */
	protected static $icons_stack;

	/**
	 * @var array
	 */
	protected static $printed_icons = [];

	public static $families = [];

	/**
	 * @param array|string $icon
	 *
	 * @throws Exception
	 */
	public function __construct( $icon ) {

		$this->icon = $this->sanitize( $icon );
		$this->validate();

		self::init();
	}


	protected static function init() {

		if ( isset( self::$icons_stack ) ) {

			return;
		}

		self::$icons_stack = new \BetterFrameworkPackage\Utils\Icons\IconStack( dirname( __DIR__ ) . '/assets/icons' );

		foreach ( self::$families as $family ) {

			self::$icons_stack->register_family( $family['id'], $family['path'] );
		}

		\BetterFrameworkPackage\Core\Rest\RestSetup::register( \BetterFrameworkPackage\Utils\Icons\RestHandler::class );
	}

	/**
	 * @return IconStack
	 */
	public static function icons_stack() {

		return self::$icons_stack;
	}

	/**
	 * @param string $family_id
	 * @param string $icon_path
	 * @param string $icon_prefix
	 *
	 * @return bool
	 */
	public static function register_family( string $family_id, string $icon_path, string $icon_url = '', string $icon_prefix = '' ): bool {

		self::init();

		$index = empty( $icon_prefix ) ? $family_id : $icon_prefix;

		self::$families[ $index ] = [
			'id'     => $family_id,
			'url'    => trailingslashit( $icon_url ),
			'path'   => trailingslashit( $icon_path ),
			'prefix' => $icon_prefix,
		];

		return self::$icons_stack->register_family( $family_id, $icon_path );
	}

	/**
	 * Render the given icon.
	 *
	 * @param array|string $icon
	 * @param array        $options
	 *
	 * @since 3.16.0
	 * @return string
	 */
	public static function render( $icon, array $options = [] ): string {

		try {

			return ( new self( $icon ) )->markup( $options );

		} catch ( Exception $e ) {

		}

		return '';
	}

	/**
	 * Get the icon file path.
	 *
	 * @param string $icon_id
	 *
	 * @since 3.16.0
	 * @return array
	 */
	public static function file( $icon_id ): array {

		try {

			return ( new self( $icon_id ) )->file_path();

		} catch ( Exception $e ) {

		}

		return [];
	}

	/**
	 * Get the icon file path.
	 *
	 * @param string $icon_id
	 *
	 * @since 3.16.0
	 * @return array
	 */
	public static function exists( $icon_id ): bool {

		try {

			return ( new self( $icon_id ) )->icon_exists();

		} catch ( Exception $e ) {

		}

		return [];
	}

	/**
	 * Generate the HTML markup for given icon id.
	 *
	 * @since 3.16.0
	 * @return string
	 */
	public function markup( array $options = [] ): string {

		$options = array_merge( [
			'custom_attributes' => '',
			'custom_classes'    => '',
			'custom_icon_code'  => '',
			'instant'           => false,
			'before'            => '',
			'after'             => '',
			'base64'            => false,
		], $options );

		if ( $options['base64'] ) {

			$options['instant'] = $options['base64'];
		}

		if ( $this->icon['type'] === 'custom-url' ) {

			$file = pathinfo( $this->icon['icon'] );
			// Convert local, valid and readable SVG files to code
			if ( ! empty( $file['filename'] ) &&
			     ! empty( $file['extension'] ) &&
			     $file['extension'] == 'svg' &&
			     file_exists( $_SERVER['DOCUMENT_ROOT'] . parse_url( $this->icon['icon'], PHP_URL_PATH ) )
			) {

				$options['custom_icon_code'] = file_get_contents(
					$this->icon['icon']
				);

				$this->icon['icon'] = $file['filename'];
			} else {
				$width = empty( $this->icon['width'] ) ? 24 : $this->icon['width'];

				return '<span class="bf-icon bf-custom-icon bf-custom-icon-url"><span class="bf-img-tag"><img src="' . esc_url( $this->icon['icon'] ) . '" width="' . intval( $width ) . '"></span></span>';
			}
		}

		if ( $options['base64'] ) {

			if ( ! isset( $this->icon['abs_path'] ) ) {

				return '';
			}

			$icon_content = file_get_contents(
				$this->icon['abs_path']
			);

			return 'data:image/svg+xml;base64,' . base64_encode( $icon_content );
		}

		// Custom SVG code from outside!
		if ( ! empty( $options['custom_icon_code'] ) ) {
			$this->icon['custom_id'] = $this->icon['icon'];
			$this->icon['icon_code'] = $options['custom_icon_code'];
		}

		$icon_content = sprintf( '<svg class="bf-svg-tag"><use xlink:href="#%s"></use></svg>', $this->icon['icon'] );

		$span = sprintf( '<span class="bf-icon bf-icon-svg %s %s %s" %s>',
			esc_attr( $options['custom_classes'] ),
			esc_attr( $this->icon['prefix'] ),
			$this->icon['type'] !== 'custom-url' ? esc_attr( $this->icon['icon'] ) : '',
			$options['custom_attributes']
		);

		$output = $span . $options['before'] . $icon_content . $options['after'];

//		if ( ! $this->icon_loaded() ) {

		$output .= $this->icon_sprite( $this->icon['icon_code'] ?? '' );
		$this->icon_printed();
//		}

		$output .= '</span>';

		return $output;
	}


	/**
	 * @return false
	 */
	protected function icon_loaded() {

		$id = $this->icon['icon'];

		return ! empty( self::$printed_icons[ $id ] );
	}

	protected function icon_printed() {

		$id = $this->icon['icon'];

		self::$printed_icons[ $id ] = true;
	}


	protected function icon_content(): string {

		if ( ! $path = $this->file_path() ) {

			return '';
		}

		if ( ! file_exists( $path['abs_path'] ?? '' ) ) {

			return '';
		}

		return file_get_contents(
			$path['abs_path']
		);
	}

	protected function icon_sprite( $svg = '' ): string {

		if ( ! $svg ) {

			$svg = $this->icon_content();
		}

		if ( ! preg_match( '/\<\s*svg([^\>]+)>(.+)<\s*\/\s*svg\s*>/is', $svg, $match ) ) {

			return '';
		}

		$output = '<svg width="0" height="0" class="hidden">';
		$output .= \BetterFrameworkPackage\Utils\Icons\IconSvgSprite::convert_to_symbol( $this->icon );
		$output .= '</svg>';

		return $output;
	}


	public function file_path( array $icon = null ) {

		if ( ! isset( $icon ) ) {

			$icon = $this->icon;
		}

		if ( ! isset( self::$families[ $icon['prefix'] ]['path'] ) ) {

			return [];
		}

		$rel_path  = strtr(
			'vversion/id.svg',
			$icon
		);

		$root_path = self::$families[ $icon['prefix'] ]['path'];
		$abs_path  = trailingslashit( $root_path ) . $rel_path;

		return compact( 'root_path', 'rel_path', 'abs_path' );
	}

	public function icon_exists(): bool {

		if ( ! $path = $this->file_path() ) {

			return false;
		}

		return file_exists( $path['abs_path'] );
	}

	public function icon_info( array $icon ): array {

		if ( empty( $icon['icon'] ) ) {

			return [];
		}

		if ( filter_var( $icon['icon'], FILTER_VALIDATE_URL ) ) {

			return [
				'type'  => 'custom-url',
				'width' => '',
			];
		}

		preg_match( '/^([^\-]+)\-(.+)/', $icon['icon'], $match );
		//
		$id      = $match[2] ?? '';
		$prefix  = $match[1] ?? '';
		$type    = self::$families[ $prefix ]['id'] ?? $prefix;
		$version = $type === 'font-awesome' ? '4.7' : '1';


		return array_merge(
			$icon,
			compact( 'type', 'version', 'id', 'prefix' )
		);
	}


	/**
	 * @param array|string $icon
	 *
	 * @since 3.16.0
	 * @return array
	 */
	protected function sanitize( $icon ): array {

		if ( ! is_array( $icon ) ) {

			$icon = [ 'icon' => trim( $icon ) ];
		}

		$icon = array_merge( [
			'icon'    => '',
			'width'   => '',
			'height'  => '',
			'type'    => '',
			'id'      => '',
			'version' => '',
			'prefix'  => '',
		], $icon );

		if ( empty( $icon['type'] ) || empty( $icon['version'] ) ) {

			$icon = array_merge(
				$icon,
				$this->icon_info( $icon )
			);
		}

		return array_merge(
			$icon,
			$this->file_path( $icon )
		);
	}

	/**
	 * @throws Exception
	 * @since 3.16.0
	 */
	protected function validate() {

		if ( empty( $this->icon['icon'] ) ) {

			throw new Exception( sprintf( 'Invalid icon id: %s', $this->icon['icon'] ?? '?' ) );
		}

		if ( empty( $this->icon['type'] ) ) {

			throw new Exception( sprintf( 'Invalid icon given: %s', $this->icon['type'] ?? '?' ) );
		}
	}

	public static function families() {

		return self::$families;
	}

	public static function family( $family ) {

		return self::families()[ $family ] ?? [];
	}
}
