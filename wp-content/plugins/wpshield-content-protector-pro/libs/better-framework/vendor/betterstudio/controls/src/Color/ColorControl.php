<?php

namespace BetterFrameworkPackage\Component\Control\Color;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class ColorControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts,
	\BetterFrameworkPackage\Component\Standard\Control\HaveStyles {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'color';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * List of script dependencies.
	 *
	 * @return array[]
	 */
	public function scripts_list(): array {

		return [
			[
				'id' => 'wp-color-picker',
			],
		];
	}

	/**
	 * List of style dependencies.
	 *
	 * @return array[]
	 */
	public function styles_list(): array {

		return [
			[
				'id' => 'wp-color-picker',
			],
		];
	}

	public function data_type(): string {

		return 'string';
	}
}
