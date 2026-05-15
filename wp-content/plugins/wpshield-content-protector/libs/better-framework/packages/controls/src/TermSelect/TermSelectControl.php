<?php

namespace BetterFrameworkPackage\Component\Control\TermSelect;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class TermSelectControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'term_select';
	}

	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\TermSelect\TermSelectAjaxHandler();
	}

	public function data_type(): string {

		return 'string';
	}
}
