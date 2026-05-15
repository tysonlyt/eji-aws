<?php

abstract class BF_Demo_Item_Manager {

	protected $remote_cache = array();

	/**
	 * Read content of the local or remote file.
	 *
	 * @param string $path
	 *
	 * @throws \Exception
	 * @return string
	 */
	protected function read_file( $path ) {

		if ( filter_var( $path, FILTER_VALIDATE_URL ) ) {

			if ( ! isset( $this->remote_cache[ $path ] ) ) {

				$this->remote_cache[ $path ] = $this->remote_file_content( $path );
			}

			return $this->remote_cache[ $path ];

		} else {

			if ( ! is_readable( $path ) ) {
				throw new Exception( 'cannot read content of post.' );
			}

			return bf_get_local_file_content( $path );
		}
	}

	/**
	 * Read content of a remote file.
	 *
	 * @param string $path
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function remote_file_content( $path ) {

		$request = wp_remote_get( $path );

		if ( is_wp_error( $request ) ) {
			throw new RuntimeException( $request->get_error_message() );
		}

		if ( 200 != wp_remote_retrieve_response_code( $request ) ) {
			throw new RuntimeException( trim( wp_remote_retrieve_response_message( $request ) ) );
		}

		return wp_remote_retrieve_body( $request );
	}
}
