<?php

namespace BetterFrameworkPackage\Component\Control\SwitchControl;

use BetterFrameworkPackage\Component\Control as LibRoot;

class SwitchControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'switch';
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
}
