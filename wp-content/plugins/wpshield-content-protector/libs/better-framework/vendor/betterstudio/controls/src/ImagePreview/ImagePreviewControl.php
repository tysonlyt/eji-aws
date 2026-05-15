<?php

namespace BetterFrameworkPackage\Component\Control\ImagePreview;

use BetterFrameworkPackage\Component\Control as LibRoot;

class ImagePreviewControl extends \BetterFrameworkPackage\Component\Control\BaseControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

			return 'image_preview';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}
}
