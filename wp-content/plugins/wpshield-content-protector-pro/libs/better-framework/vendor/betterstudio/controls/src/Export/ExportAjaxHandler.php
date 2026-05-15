<?php

namespace BetterFrameworkPackage\Component\Control\Export;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

use WP_HTTP_Response;

class ExportAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	/**
	 * Store the request params.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $params;

	/**
	 * @param array $params
	 *
	 * @throws Exception
	 * @since 1.0.0
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response {

		$this->params = $params;

		$this->validate();

		$response = new WP_HTTP_Response();
		$response->set_headers( $this->headers() );
		$response->set_data( $this->export_data() );

		return $response;
	}

	/**
	 * @since 1.0.0
	 * @return array
	 */
	protected function headers(): array {

		$headers                        = wp_get_nocache_headers();
				$headers['Pragma']      = 'no-cache';
		$headers['Content-Type']        = 'application/force-download';
		$headers['Content-Disposition'] = sprintf( 'attachment; filename="%s"', $this->filename() );

		return $headers;
	}


	/**
	 * Validate the request.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	protected function validate(): void {

		if ( ! is_user_logged_in() ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid request' );
		}

		if ( empty( $this->params['panel_id'] ) || ! \is_string( $this->params['panel_id'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'no panel_id.' );
		}

		$config = apply_filters( "better-framework/panel/{$this->params['panel_id']}/config", [] );

		if ( empty( $config ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid panel_id.' );
		}

		if ( ! current_user_can( $config['config']['capability'] ?? 'manage_options' ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Illegal access.' );
		}
	}


	/**
	 * Generate filename.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function filename(): string {

		if ( ! empty( $this->params['file_name'] ) ) {

			$file_name = $this->params['file_name'] . '-' . date( 'm-d-Y h:i:s a' );

		} else {
			$file_name = 'options-backup-' . date( 'm-d-Y h:i:s a' );
		}

		return $file_name . '.json';
	}

	/**
	 * Get export array.
	 *
	 * @return array|bool array on success or false on failure.
	 */
	public function export_data() {

		$export                     = [];
		$panel_id                   = sanitize_key( $this->params['panel_id'] );
				$export['panel-id'] = $panel_id;

		$lang                         = sanitize_key( $this->params['lang'] ?? 'none' );
		$export['panel-multilingual'] = $lang;

		if ( $lang !== 'none' ) {
			$lang = '_' . $lang;
		} else {
			$lang = '';
		}

		$export['panel-data'] = get_option( $panel_id . $lang );

		/**
		 * Filter for export data
		 *
		 * @param string $options contains export data
		 *
		 * @since BF 1.0.0
		 */
		return apply_filters( 'better-framework/panel/export/data', $export );
	}
}
