<?php

namespace BetterFrameworkPackage\Component\Control\Checkbox;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class CheckboxControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements \BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'checkbox';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function data_type(): string {

		return 'object';
	}

	public function modify_props( array $props ): array {

		$props['choices'] = [];

		foreach ( $props['options'] ?? [] as $key => $value ) {

			$props['choices'][] = [ (string) $key, $value ];
		}

		unset( $props['options'] );

		return $props;
	}
}
