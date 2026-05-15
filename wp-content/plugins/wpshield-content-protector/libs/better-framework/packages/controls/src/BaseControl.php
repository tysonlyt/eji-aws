<?php

namespace BetterFrameworkPackage\Component\Control;

use BetterFrameworkPackage\Component\Standard\Control;

abstract class BaseControl extends \BetterFrameworkPackage\Component\Standard\Control\StandardControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveRenderDynamic, \BetterFrameworkPackage\Component\Standard\Control\HaveUnderscoreTemplate {
	/**
	 * Absolute path to the template file.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract public function template_dir(): string;

	public function render( array $props = [], array $options = [] ): string {

		$this->options = $options;
		$context       = $options['context'] ?? 'default';
		$template_file = $this->template_dir() . '/dynamic.php';

		$props = $this->props_init( $props, true );

		ob_start();

		if ( file_exists( $template_file ) ) {
			include $template_file;
		}

		return ob_get_clean();
	}

	public function underscore_template(): string {

		$template_file = $this->template_dir() . '/underscore.php';

		ob_start();

		if ( file_exists( $template_file ) ) {
			include $template_file;
		}

		return ob_get_clean();
	}
}
