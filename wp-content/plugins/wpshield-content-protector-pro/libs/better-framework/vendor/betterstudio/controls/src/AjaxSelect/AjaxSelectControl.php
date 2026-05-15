<?php

namespace BetterFrameworkPackage\Component\Control\AjaxSelect;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class AjaxSelectControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler,
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'ajax_select';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @return ControlStandard\HandleAjaxRequest
	 */
	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\AjaxSelect\AjaxSelectAjaxHandler();
	}

	public function modify_props( array $props ): array {

		if ( isset( $props['callback'] ) ) {

			$props['token'] = self::token( $props['callback'] );
		}

		return $props;
	}

	public function secure_props( array $props ): array {

		$props['token']  = self::token( $props['callback'] ?? '' );
		$props['values'] = $this->prepare_values( $props );

		return $props;
	}

	/**
	 * @param array $props
	 *
	 * @return array
	 */
	protected function prepare_values( array $props ): array {

		$callback = isset( $props['get_name'] ) && \is_callable( $props['get_name'] ) ? $props['get_name'] : '';
		$values   = isset( $props['value'] ) && '' !== $props['value'] ? explode( ',', $props['value'] ) : [];
		$results  = [];

		foreach ( $values as $id ) {

			$label     = $callback ? $callback( $id ) : $id;
			$results[] = compact( 'id', 'label' );
		}

		return $results;
	}

	/**
	 * @param string $callback
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function token( string $callback ): string {

		return wp_create_nonce( sprintf( 'bf-custom-callback:%s', $callback ) );
	}

	public function scripts_list(): array {

		return [
			[
				'id' => 'jquery-ui-sortable',
			],
		];
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		if ( ! empty( $props['callback'] ) ) {

			return true;
		}

		if ( isset( $props['value'] ) && '' !== $props['value'] && ! empty( $props['get_name'] ) && \is_callable( $props['get_name'] ) ) {

			return true;
		}

		return false;
	}

	public function secure_props_token( array $props ): string {

		if ( empty( $props['callback'] ) ) {

			return '';
		}

		return self::token( $props['callback'] );
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function data_type(): string {

		// use string instead of array for backward compatibility
		return 'string';
	}
}
