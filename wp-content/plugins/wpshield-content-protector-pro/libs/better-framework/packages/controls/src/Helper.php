<?php

namespace BetterFrameworkPackage\Component\Control;

class Helper {

	/**
	 * @param array|string $deferred_options
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function deferred_options( $deferred_options ): array {

		$options = [];

		if ( \is_string( $deferred_options ) && \is_callable( $deferred_options ) ) {

			$options = $deferred_options();

		}

		if ( \is_callable( $deferred_options['callback'] ?? '' ) ) {

			if ( isset( $deferred_options['args'] ) ) {
				$options = \call_user_func_array( $deferred_options['callback'], $deferred_options['args'] );
			} else {
				$options = \call_user_func( $deferred_options['callback'] );
			}
		}

		return \is_array( $options ) ? $options : [];
	}

	/**
	 * @param array|callable|string $deferred_options
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function deferred_options_token( $deferred_options ): string {

		if ( $deferred_options instanceof \Closure ) {

			$callback = '';

		} elseif ( \is_string( $deferred_options ) ) {

			$callback = $deferred_options;

		} elseif ( isset( $deferred_options['callback'] ) ) {

			$callback = $deferred_options['callback'];
		}

		if ( empty( $callback ) || ! \is_string( $callback ) ) {

			return '';
		}

		return wp_create_nonce( sprintf( 'deferred:%s', $callback ) );
	}
}
