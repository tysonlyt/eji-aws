<?php

namespace BetterFrameworkPackage\Component\Control\Hr;

use BetterFrameworkPackage\Component\Control as LibRoot;

class HrControl extends \BetterFrameworkPackage\Component\Control\BaseControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'hr';
	}

	public function underscore_template(): string {

		return '<hr class="bf-hr">';
	}

	public function render( array $control = [], array $render_options = [] ): string {

		return '<hr class="bf-hr">';
	}

	public function template_dir(): string {

		return '';
	}
}
