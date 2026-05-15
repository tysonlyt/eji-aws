<?php

namespace BetterFrameworkPackage\Component\Control\Import;

use BetterFrameworkPackage\Component\Control;


// use ProFeature API
use \BetterFrameworkPackage\Component\Control\Features\{
	ProFeature
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

use WP_HTTP_Response;

class ImportAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	/**
	 * Store the request params.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $params;

	/**
	 * The panel configuration.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	protected $config;

	/**
	 * @param array $params
	 *
	 * @throws Exception
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response {

		$this->params = $params;
		$this->config = $this->load_panel_config();
		$file_content = $this->verify();

		if ( ! $this->import_data( $file_content ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Import error.' );
		}

		return $this->response(
			[
				'refresh' => true,
				'success' => true,
			],
			false
		);
	}


	/**
	 * @throws Exception
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function verify(): array {

		$this->verify_permission();
		$file_path = $this->verify_file();

		return $this->verify_file_content( $file_path );
	}

	/**
	 * @throws Exception
	 * @return array panel config array
	 */
	public function load_panel_config(): array {

		if ( empty( $this->params['panel_id'] ) || ! \is_string( $this->params['panel_id'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'No panel_id.' );
		}

		$config = apply_filters( "better-framework/panel/{$this->params['panel_id']}/config", [] );

		if ( empty( $config ) || ! is_array( $config ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Invalid panel_id.' );
		}

		if ( empty( $config['panel-id'] ) ) {

			$config['panel-id'] = $this->params['panel_id'];
		}

		return $config;
	}

	/**
	 * @throws Exception
	 * @return void
	 */
	protected function verify_permission(): void {

		if ( ! is_user_logged_in() ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Invalid request' );
		}

		if ( empty( $this->params['token'] ) || $this->params['token'] !== $this->token() ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Invalid security token.' );
		}

		if ( empty( $this->params['panel_id'] ) || ! \is_string( $this->params['panel_id'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'No panel_id.' );
		}

		$config = apply_filters( "better-framework/panel/{$this->params['panel_id']}/config", [] );

		if ( empty( $config ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Invalid panel_id.' );
		}

		if ( ! current_user_can( $config['config']['capability'] ?? 'manage_options' ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Illegal access.' );
		}
	}

	/**
	 * @throws Exception
	 * @return string
	 */
	protected function verify_file(): string {

		if ( ! isset( $_FILES['import-file-input']['error'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'No file uploaded.' );
		}

		if ( $_FILES['import-file-input']['error'] !== UPLOAD_ERR_OK ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'File upload error.' );
		}

		if ( $_FILES['import-file-input']['size'] > MB_IN_BYTES ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'uploaded file is too large.' );
		}

		return $_FILES['import-file-input']['tmp_name'];
	}

	/**
	 * @throws Exception
	 * @since 1.0.0
	 * @return array
	 */
	protected function verify_file_content( string $file_path ): array {

		$data = \json_decode( file_get_contents( $file_path ), true );

		if ( JSON_ERROR_NONE !== \json_last_error() || ! \is_array( $data ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid file format.' );
		}

		// data is not correct
		if ( empty( $data['panel-id'] ) || empty( $data['panel-data'] ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Imported data is not correct or was corrupted.' );
		}

		if ( $data['panel-id'] !== $this->params['panel_id'] ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( 'Imported data is not for this panel.' );
		}

		return $data;
	}

	/**
	 * @param array $data
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function filter_values( array $data ): array {

		$fields = apply_filters( 'better-framework/panel/' . $this->config['panel-id'] . '/fields', [] );

		array_walk(
			$data,
			static function ( &$field_value, $field_id ) use ( $fields ) {

				if ( ! isset( $fields[ $field_id ] ) ) {

					$field_value = null;

					return;
				}

				$field = &$fields[ $field_id ];

				if ( isset( $field['pro_feature']['modal_id'] ) && \BetterFrameworkPackage\Component\Control\Features\ProFeature::is_active( $field['pro_feature']['modal_id'] ) ) {

					$field_value = null;
				}

				if ( isset( $field['type'] ) ) {

					$field_value = \BetterFrameworkPackage\Component\Control\filter_control_value( $field['type'], $field_value, $field );
				}

				return null;
			}
		);

		// remove null values
		return array_filter(
			$data,
			static function ( $field_value ) {

				return isset( $field_value );
			}
		);
	}


	/**
	 * @param array $data
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function import_data( array &$data ): bool {

		/**
		 * Fires before options import
		 *
		 * @param string $data contain import file data
		 *
		 * @since 1.0.0
		 */
		do_action_ref_array( 'better-framework/panel/import/before', [ &$data ] );

		$lang = '';

		if ( isset( $this->params['lang'] ) && $this->params['lang'] !== 'none' ) {

			$lang = $this->params['lang'];
		}

		$panel_id = $data['panel-id'];

		if ( ! empty( $lang ) ) {

			$panel_id .= '_' . $lang;
		}

		$current_values = get_option( $panel_id, [] );

		if ( ! is_array( $current_values ) ) {
			$current_values = [];
		}

		// Save options
		update_option( $panel_id, array_merge( $current_values, $this->filter_values( $data['panel-data'] ) ) );

		// Imports style
		if ( isset( $data['panel-data']['style'] ) && ! empty( $data['panel-data']['style'] ) ) {

			update_option( $panel_id . '_current_style', $data['panel-data']['style'] );
		}

		return true;
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	protected function token(): string {

		return wp_create_nonce( 'import:' . ( $this->params['panel_id'] ?? '' ) );
	}
}
