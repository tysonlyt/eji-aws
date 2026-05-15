<?php

namespace BetterFrameworkPackage\Component\Control\Features;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use core libraries
use \BetterFrameworkPackage\Core\{
	Module
};

class ProFeature implements \BetterFrameworkPackage\Core\Module\NeedSetup {

	/**
	 * @var mixed[]
	 */
	protected static $modals_id = [];

	/**
	 * @var string[]
	 */
	protected static $option_items = [ 'selectable' ];

	public static function setup(): bool {

		\BetterFrameworkPackage\Component\Control\Setup::register_wrapper( 'pro-feature', [ __CLASS__, 'pro_feature_wrapper' ] );
				add_action( 'admin_footer', [ __CLASS__, 'pro_feature_template' ] );

		return true;
	}

	/**
	 *
	 * @param string $input   output buffer
	 * @param array  $props   the control props
	 * @param array  $options render options
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function pro_feature_wrapper( string $input, array $props, array $options = [] ): string {

		if ( self::register( $props ) ) {

			ob_start();

			include __DIR__ . '/templates/pro-feature.php';

			return ob_get_clean();
		}

		return $input;
	}

	public static function register( array $props ): bool {

		if ( empty( $props['pro_feature']['activate'] ) || empty( $props['pro_feature']['modal_id'] ) ) {

			return false;
		}

		$modal_id = $props['pro_feature']['modal_id'];

		if ( ! self::is_active( $modal_id ) ) {

			return false;
		}

		if ( ! \in_array( $modal_id, self::$modals_id, true ) ) {

			self::$modals_id[] = $modal_id;
		}

		return true;
	}

	public static function is_active( string $modal_id ): bool {

		return apply_filters( "better-framework/controls/pro-features/$modal_id/enable", true );
	}

	public static function pro_feature_template(): void {

		$configs = array_map(
			static function ( $modal_id ) {

				return apply_filters(
					"better-framework/controls/pro-features/$modal_id/config",
					[
						'id'       => $modal_id,
						'template' => [
							'title'            => __( '{{ name }} is a PRO Feature', 'better-studio' ),
							'desc'             => __( 'We\'re sorry, the Filter In is not available on your plan . Please upgrade to the PRO plan to unlock all these awesome features . ', 'better-studio' ),
							'button_text'      => __( 'Upgrade to PRO', 'better-studio' ),
							'purchased_text'   => __( 'Already Purchased?', 'better-studio' ),
							'discount_desc'    => '',
							'interested_title' => __( 'Almost Done', 'better-studio' ),
							'interested_p1'    => '',
							'interested_p2'    => '',
							'interested_p3'    => '',
						],
					]
				);

			},
			self::$modals_id
		);

		if ( ! empty( $configs ) ) {

			include __DIR__ . '/templates/pro-feature-modal.php';
		}
	}


	public static function element_data_attributes( array $props ): string {

		if ( empty( $props['pro_feature']['modal_id'] ) ) {

			return '';
		}

		$template = sprintf( ' data-modal-id="%s"', esc_attr( $props['pro_feature']['modal_id'] ) );
		$name     = trim( $props['pro_feature']['name'] ?? $props['label'] ?? $props['name'] ?? '' );

		if ( ! empty( $name ) ) {

			$template .= sprintf( ' data-name="%s"', esc_attr( strip_tags( $name ) ) );
		}
		if ( ! empty( $props['pro_feature']['template'] ) ) {

			$template .= sprintf( ' data-template="%s"', esc_attr( json_encode( $props['pro_feature']['template'] ) ) );
		}

		if ( $options = array_intersect_key( $props['pro_feature'], array_flip( self::$option_items ) ) ) {

			$template .= sprintf( ' data-options="%s"', esc_attr( json_encode( $options ) ) );
		}

		return $template;
	}
}
