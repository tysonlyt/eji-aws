<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface HaveRenderDynamic {

	/**
	 * @param array $control
	 * @param array $render_options
	 *
	 * @return string
	 */
	public function render( array $control = [], array $render_options = [] ): string;
}
