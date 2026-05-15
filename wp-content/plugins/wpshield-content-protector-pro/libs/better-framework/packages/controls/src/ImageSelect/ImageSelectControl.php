<?php

namespace BetterFrameworkPackage\Component\Control\ImageSelect;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

use BetterFrameworkPackage\Component\Control as LibRoot;

class ImageSelectControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'image_select';
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


	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! empty( $props['deferred-options'] );
	}

	public function secure_props_token( array $props ): string {

		if ( empty( $props['deferred-options'] ) ) {

			return '';
		}

		return \BetterFrameworkPackage\Component\Control\Helper::deferred_options_token( $props['deferred-options'] );
	}

	public function data_type(): string {

		return 'string';
	}

	public function modify_props( array $props ): array {

		$props['choices'] = [];

		foreach ( $props['options'] ?? [] as $key => $value ) {

			$props['choices'][] = [ (string) $key, $value ];
		}

		unset( $props['options'] );

		return $props;
	}


	public function find_choice( array $choices, $find_key ) {

		foreach ( $choices ?? [] as [$key, $choice] ) {

			if ( $key === $find_key ) {

				return $choice;
			}
		}

		return null;
	}
}
