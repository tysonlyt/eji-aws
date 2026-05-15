<?php

namespace BetterFrameworkPackage\Component\Control\BackgroundImage;

use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class BackgroundImageControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveStyles,
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'background_image';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function styles_list(): array {

		return [
			[
				'id' => 'buttons',
			],
		];
	}

	public function scripts_list(): array {

		$this->load_media_assets();

		return [];
	}

	public function data_type(): string {

		return 'object';
	}
}
