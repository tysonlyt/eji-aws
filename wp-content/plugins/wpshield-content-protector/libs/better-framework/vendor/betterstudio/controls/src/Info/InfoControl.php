<?php

namespace BetterFrameworkPackage\Component\Control\Info;

use BetterFrameworkPackage\Component\Control as LibRoot;

class InfoControl extends \BetterFrameworkPackage\Component\Control\BaseControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'info';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}
}
