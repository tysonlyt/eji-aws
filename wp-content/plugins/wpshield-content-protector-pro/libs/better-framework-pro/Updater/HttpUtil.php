<?php

namespace BetterStudio\Framework\Pro\Updater;

class HttpUtil {

	/**
	 * Fetch remote style files.
	 *
	 * @param array $urls
	 *
	 * @since 3.16.0
	 * @return string
	 */
	public static function remote_files_content( array $urls ): string {

		$results = '';

		foreach ( $urls as $url ) {

			if ( ! $content = self::remote_file_content( $url ) ) {

				return '';
			}

			$results .= $content;
		}

		return $results;
	}

	/**
	 * Fetch the remote file content.
	 *
	 * @param string $url File url.
	 *
	 * @since 3.16.0
	 * @return string The remote file content
	 */
	public static function remote_file_content( string $url ): string {

		if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {

			return '';
		}

		$request = wp_remote_get( $url, [ 'sslverify' => false ] );

		if ( ! $request || is_wp_error( $request ) ) {

			return '';
		}

		if ( wp_remote_retrieve_response_code( $request ) !== 200 ) {

			return '';
		}

		$content      = wp_remote_retrieve_body( $request );
		$content_type = strtolower( wp_remote_retrieve_header( $request, 'content-type' ) );

		if ( 'application/json' === $content_type ) {

			$content = json_decode( $content, true );
		}

		return $content;
	}
}
