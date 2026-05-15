<?php

namespace BetterFrameworkPackage\Component\Control\Custom;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class CustomControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'custom';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @since 1.0.0
	 * @return ControlStandard\HandleAjaxRequest
	 */
	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\Custom\CustomAjaxHandler();
	}

	public function modify_props( array $props ): array {

		if ( empty( $props['input_callback'] ) ) {

			return $props;
		}

		[ $callback, $callback_args ] = $this->callback( $props );

		// Serialized arguments.
		$serialized_args = serialize( $callback_args );

		// When ${callback} is array should be use callback name!
		if ( is_array( $callback ) && ! empty( $callback[1] ) ) {
			$serialized_callback = $callback[1] . $serialized_args;
		} else {
			$serialized_callback = $callback . $serialized_args;
		}

		$props['token']         = wp_create_nonce( md5( $serialized_callback ) );
		$props['callback']      = $callback;
		$props['callback_args'] = $callback_args;

		return $props;
	}

	/**
	 * @param array $props
	 *
	 * @return string[]
	 */
	protected function callback( array $props ): array {

		if ( \is_string( $props['input_callback'] ) ) {

			$callback      = $props['input_callback'];
			$callback_args = [ [ 'field_options' => $props ] ];

		} elseif ( \is_array( $props['input_callback'] ) ) {

			$callback = $props['input_callback']['callback'];

			if ( isset( $props['input_callback']['args'][0] ) ) {

				$callback_args                     = $props['input_callback']['args'];
				$callback_args[0]['field_options'] = $props;
			} else {

				$callback_args = [ [ 'field_options' => $props ] ];
			}
		}

		return [ $callback ?? '', $callback_args ?? '' ];
	}

	/**
	 * Double check save value.
	 *
	 * @return string
	 */
	public function data_type(): string {

		return 'string';
	}
}
