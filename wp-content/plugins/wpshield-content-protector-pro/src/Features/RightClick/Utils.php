<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\RightClick;

/**
 * Class Utils
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Features\RightClick
 */
class Utils {

	/**
	 * Retrieve contextmenu views list.
	 *
	 * @since 1.0.0
	 * @return string[]
	 */
	public static function get_cx_views(): array {

		return [
			'windows-chrome'     => [
				'onImage'          => self::get_view_by_id( 'windows.chrome.image' ),
				'onInputs'         => self::get_view_by_id( 'windows.chrome.inputs' ),
				'default'          => self::get_view_by_id( 'windows.chrome.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'windows.chrome.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'windows.chrome.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'windows.chrome.modified-inputs' ),
			],
			'windows-firefox'    => [
				'onImage'          => self::get_view_by_id( 'windows.firefox.image' ),
				'onInputs'         => self::get_view_by_id( 'windows.firefox.inputs' ),
				'default'          => self::get_view_by_id( 'windows.firefox.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'windows.firefox.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'windows.firefox.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'windows.firefox.modified-inputs' ),
			],
			'windows-opera'      => [
				'onImage'          => self::get_view_by_id( 'windows.opera.image' ),
				'onInputs'         => self::get_view_by_id( 'windows.opera.inputs' ),
				'default'          => self::get_view_by_id( 'windows.opera.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'windows.opera.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'windows.opera.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'windows.opera.modified-inputs' ),
			],
			'windows-edge'      => [
				'onImage'          => self::get_view_by_id( 'windows.edge.image' ),
				'onInputs'         => self::get_view_by_id( 'windows.edge.inputs' ),
				'default'          => self::get_view_by_id( 'windows.edge.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'windows.edge.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'windows.edge.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'windows.edge.modified-inputs' ),
			],
			'mac-chrome'  => [
				'onImage'          => self::get_view_by_id( 'mac.chrome.image' ),
				'onInputs'         => self::get_view_by_id( 'mac.chrome.inputs' ),
				'default'          => self::get_view_by_id( 'mac.chrome.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'mac.chrome.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'mac.chrome.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'mac.chrome.modified-inputs' ),
			],
			'mac-firefox' => [
				'onImage'          => self::get_view_by_id( 'mac.firefox.image' ),
				'onInputs'         => self::get_view_by_id( 'mac.firefox.inputs' ),
				'default'          => self::get_view_by_id( 'mac.firefox.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'mac.firefox.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'mac.firefox.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'mac.firefox.modified-inputs' ),
			],
			'mac-safari'  => [
				'onImage'          => self::get_view_by_id( 'mac.safari.image' ),
				'onInputs'         => self::get_view_by_id( 'mac.safari.inputs' ),
				'default'          => self::get_view_by_id( 'mac.safari.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'mac.safari.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'mac.safari.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'mac.safari.modified-inputs' ),
			],
			'mac-opera'   => [
				'onImage'          => self::get_view_by_id( 'mac.opera.image' ),
				'onInputs'         => self::get_view_by_id( 'mac.opera.inputs' ),
				'default'          => self::get_view_by_id( 'mac.opera.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'mac.opera.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'mac.opera.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'mac.opera.modified-inputs' ),
			],
			'linux-chrome'     => [
				'onImage'          => self::get_view_by_id( 'linux.chrome.image' ),
				'onInputs'         => self::get_view_by_id( 'linux.chrome.inputs' ),
				'default'          => self::get_view_by_id( 'linux.chrome.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'linux.chrome.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'linux.chrome.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'linux.chrome.modified-inputs' ),
			],
			'linux-firefox'    => [
				'onImage'          => self::get_view_by_id( 'linux.firefox.image' ),
				'onInputs'         => self::get_view_by_id( 'linux.firefox.inputs' ),
				'default'          => self::get_view_by_id( 'linux.firefox.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'linux.firefox.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'linux.firefox.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'linux.firefox.modified-inputs' ),
			],
			'linux-safari'     => [
				'onImage'          => self::get_view_by_id( 'linux.safari.image' ),
				'onInputs'         => self::get_view_by_id( 'linux.safari.inputs' ),
				'default'          => self::get_view_by_id( 'linux.safari.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'linux.safari.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'linux.safari.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'linux.safari.modified-inputs' ),
			],
			'linux-opera'      => [
				'onImage'          => self::get_view_by_id( 'linux.opera.image' ),
				'onInputs'         => self::get_view_by_id( 'linux.opera.inputs' ),
				'default'          => self::get_view_by_id( 'linux.opera.default' ),
				'onAnchorLink'     => self::get_view_by_id( 'linux.opera.anchor-link' ),
				'onSelectedElement'     => self::get_view_by_id( 'linux.opera.selected-element' ),
				'onModifiedInputs' => self::get_view_by_id( 'linux.opera.modified-inputs' ),
			],
		];
	}

	public static function get_view_by_id( string $id ): string {

		$id = str_replace( '.', DIRECTORY_SEPARATOR, $id );

		$filename = sprintf( '%s/view/%s.php', __DIR__, $id );

		if ( ! file_exists( $filename ) ) {

			return '';
		}

		ob_start();

		include $filename;

		return str_replace( PHP_EOL , '', trim( ob_get_clean() ) );
	}
}
