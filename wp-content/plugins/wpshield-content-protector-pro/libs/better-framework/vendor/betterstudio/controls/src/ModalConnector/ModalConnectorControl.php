<?php

namespace BetterFrameworkPackage\Component\Control\ModalConnector;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class ModalConnectorControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl {

	/**
	 * Store the list of modal ids.
	 *
	 * @var array
	 */
	protected $modal_ids = [ 'form' ];

	public function __construct() {

		parent::__construct();

		add_action( 'admin_footer', [ $this, 'modal_template' ] );
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'modal_connector';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function data_type(): string {

		return 'array';
	}

	public static function element_data_attributes( array $props ): string {

		if ( empty( $props['modal']['id'] ) ) {

			return '';
		}

		$template    = sprintf( ' data-modal-id="%s"', esc_attr( $props['modal']['id'] ) );
		$name        = trim( $props['modal']['name'] ?? $props['label'] ?? $props['name'] ?? '' );
		$type        = trim( $props['modal']['type'] ?? '' );
		$options     = $props['modal']['options'] ?? [];
		$js_callback = trim( $props['modal']['js_callback'] ?? '' );
		$service_id  = trim( $props['modal']['service'] ?? '' );

		if ( ! empty( $name ) ) {

			$template .= sprintf( ' data-name="%s"', esc_attr( strip_tags( $name ) ) );
		}
		if ( ! empty( $type ) ) {

			$template .= sprintf( ' data-modal-type="%s"', esc_attr( strip_tags( $type ) ) );
		}
		if ( ! empty( $options ) ) {

			$template .= sprintf( ' data-body-fields="%s"', esc_attr( strip_tags( json_encode( $options ) ) ) );
		}
		if ( ! empty( $js_callback ) ) {

			$template .= sprintf( ' data-js-callback="%s"', esc_attr( strip_tags( $js_callback ) ) );
		}
		if ( ! empty( $service_id ) ) {

			$template .= sprintf( ' data-service="%s"', esc_attr( strip_tags( $service_id ) ) );
		}

		return $template;
	}

	/**
	 * Print modal HTML template.
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function modal_template(): void {

		$configs = array_map(
			static function ( $modal_id ) {

				return apply_filters(
					"better-framework/controls/$modal_id/config",
					[
						'id'       => $modal_id,
						'template' => [
							'title'       => '',
							'submit_text' => __( 'Save', 'better-studio' ),
						],
					]
				);

			},
			apply_filters( 'better-framework/controls/modal-connector/ids', $this->modal_ids )
		);

		if ( ! empty( $configs ) ) {

			include __DIR__ . '/templates/modal-fields.php';
		}
	}
}
