<?php

namespace BetterFrameworkPackage\Component\Control\Editor;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};


class EditorControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveScripts {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'editor';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function scripts_list(): array {

		return [
			[
				'id'  => 'ace-editor-script',
				'url' => 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.8/ace.js',
			],
		];
	}

	/**
	 * @return string
	 */
	public function data_type(): string {

		return 'string';
	}
}
