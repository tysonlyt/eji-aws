<?php

namespace BetterFrameworkPackage\Component\Control\Button;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// buttons
class ButtonControl extends \BetterFrameworkPackage\Component\Control\BaseControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveStyles {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'button';
	}

	public function styles_list(): array {

		return [
			[
				'id' => 'buttons',
			],
		];
	}

	public function template_dir(): string {

		return __DIR__ . '/templates';
	}
}
