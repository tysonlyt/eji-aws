<?php

namespace BetterFrameworkPackage\Component\Control\ImageUpload;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class ImageUploadControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveStyles {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'image_upload';
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

	public function data_type(): string {

		return 'string';
	}
}
