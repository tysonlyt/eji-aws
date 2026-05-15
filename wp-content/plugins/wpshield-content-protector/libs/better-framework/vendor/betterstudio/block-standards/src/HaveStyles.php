<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

interface HaveStyles {

	/**
	 * @return array[] {
	 *
	 * @type string $id   Required. unique style id
	 * @type string $url  Required. the file url.
	 * @type string $path Optional. absolute path to the file.
	 * }
	 */
	public function styles( string $context ): array;
}
