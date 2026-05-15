<?php

if ( ! function_exists( 'bf_count' ) ) {

	/**
	 * Safe count function
	 *
	 * @since 3.9.3
	 * @return integer
	 */
	function bf_count( $variable ) {

		// PHP > 7.1
		// phpcs:ignore
		if ( function_exists( 'is_countable' ) && is_countable( $variable ) ) {
			return count( $variable );
		}

		// PHP < 7.2
		if ( is_array( $variable ) ) {
			return count( $variable );
		}

		return 0;
	}
}
