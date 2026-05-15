<?php

namespace BetterFrameworkPackage\Component\Control\Radio;

use BetterFrameworkPackage\Component\Control as LibRoot;

use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class RadioControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'radio';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function data_type(): string {

		return 'string';
	}

	public function secure_props( array $props ): array {

		if ( isset( $props['deferred-options'] ) ) {

			$props['options'] = \BetterFrameworkPackage\Component\Control\Helper::deferred_options( $props['deferred-options'] );
		}

		return $props;
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! empty( $props['deferred-options'] );
	}

	public function secure_props_token( array $props ): string {

		if ( ! empty( $props['deferred-options'] ) ) {

			return \BetterFrameworkPackage\Component\Control\Helper::deferred_options_token( $props['deferred-options'] );
		}

		return '';
	}

	public function modify_props( array $props ): array {

		$props['choices'] = [];

		foreach ( $props['options'] ?? [] as $key => $value ) {

			$props['choices'][] = [ (string) $key, $value ];
		}

		return $props;
	}
}
