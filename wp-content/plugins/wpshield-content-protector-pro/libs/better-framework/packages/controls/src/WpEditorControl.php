<?php

namespace BetterFrameworkPackage\Component\Control;

use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class WpEditorControl extends \BetterFrameworkPackage\Component\Control\BaseControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveData {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'wp_editor';
	}

	public function underscore_template(): string {

		return '';
	}

	public function render( array $control = [], array $render_options = [] ): string {

		$editor_settings = $control['settings'] ?? [];
		if ( ! isset( $editor_settings['textarea_name'] ) ) {
			$editor_settings['textarea_name'] = $control['input_name'] ?? '';
		}

		ob_start();

		wp_editor( $control['value'] ?? '', $control['id'] ?? '', $editor_settings );

		return ob_get_clean();
	}

	public function template_dir(): string {

		return '';
	}

	public function data_type(): string {

		return 'string';
	}
}
