<?php

namespace WPShield\Plugin\ContentProtector\Core\Utils;

/**
 * Class RedirectHelper
 *
 * The helper object for redirect
 *
 * @package WPShield\Plugin\ContentProtector\Core\Utils
 */
class RedirectHelper {

	public static $reason = 'WPShield Content Protector';

	/**
	 * Wraps wp_redirect to allow testing for redirects.
	 *
	 * @codeCoverageIgnore It only wraps a WordPress function.
	 *
	 * @param string $location The path to redirect to.
	 * @param int    $status   The status code to use.
	 * @param string $reason   The reason for the redirect.
	 */
	public static function do_unsafe_redirect( string $location, int $status = 302, string $reason = '' ):void {

		if ( ! empty( $reason ) ) {

			self::$reason = $reason;
		}

		// phpcs:ignore WordPress.Security.SafeRedirect -- intentional, function has been renamed to make unsafe more clear.
		\wp_redirect( $location, $status, $reason );
		exit;
	}

	/**
	 * Wraps wp_safe_redirect to allow testing for safe redirects.
	 *
	 * @codeCoverageIgnore It only wraps a WordPress function.
	 *
	 * @param string $location The path to redirect to.
	 * @param int    $status   The status code to use.
	 * @param string $reason   The reason for the redirect.
	 */
	public static function do_safe_redirect( string $location, int $status = 302, string $reason = '' ): void {

		if ( ! empty( $reason ) ) {

			self::$reason = $reason;
		}

		\wp_safe_redirect( $location, $status, $reason );
		exit;
	}

}