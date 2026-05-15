<?php

namespace BetterFrameworkPackage\Component\Control\SorterCheckbox;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use lib functions
use function \BetterFrameworkPackage\Component\Control\{
	json_decode
};

class SorterCheckboxControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'sorter_checkbox';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function secure_props( array $props ): array {

		if ( ! empty( $props['deferred-options'] ) ) {

			$props['options'] = \BetterFrameworkPackage\Component\Control\Helper::deferred_options( $props['deferred-options'] );
		}

		return $props;
	}

	/**
	 * @return array[]
	 */
	public function scripts_list(): array {

		return [
			[
				'id' => 'jquery-ui-sortable',
			],
		];
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! empty( $props['deferred-options'] );
	}

	public function secure_props_token( array $props ): string {

		if ( empty( $props['deferred-options'] ) ) {

			return '';
		}

		return \BetterFrameworkPackage\Component\Control\Helper::deferred_options_token( $props['deferred-options'] );
	}

	public function modify_save_value( $value, array $props = [] ) {

		if ( \is_string( $value ) ) {

			$value = \BetterFrameworkPackage\Component\Control\json_decode( $value );
		}

		if ( ! \is_array( $value ) ) {

			return [];
		}

		$new_struct = [];

		foreach ( $value as $item_id => $item_option ) {

			if ( \is_array( $item_option ) ) {

				if ( ! isset( $item_option['id'] ) ) {

					$item_option['id'] = $item_id;
				}

				$new_struct[] = $item_option;

			} else {

				$new_struct[] = [
					'id'     => $item_id,
					'label'  => ucwords( str_replace( [ '_', '-' ], ' ', $item_id ) ),
					'active' => ! empty( $item_option ),
				];
			}
		}

		return $new_struct;
	}

	/**
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function modify_props( array $props ): array {

		$props['value']   = $this->modify_save_value( $props['value'] ?? [], $props );
		$props['choices'] = [];

		foreach ( $props['options'] ?? [] as $key => $option ) {

			if ( ! isset( $option['id'] ) ) {

				$option['id'] = $key;
			}

			$props['choices'][] = [ (string) $key, $option ];
		}

		return $props;
	}

	public function data_type(): string {

		return 'array';
	}
}
