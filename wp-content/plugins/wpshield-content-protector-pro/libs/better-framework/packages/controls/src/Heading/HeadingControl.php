<?php

namespace BetterFrameworkPackage\Component\Control\Heading;

use BetterFrameworkPackage\Component\Control as LibRoot;

class HeadingControl extends \BetterFrameworkPackage\Component\Control\BaseControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'heading';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}
}
