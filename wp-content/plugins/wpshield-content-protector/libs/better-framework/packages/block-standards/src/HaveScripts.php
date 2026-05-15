<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

interface HaveScripts {

	/**
	 * @return array[] {
	 *
	 * @type string $id   Required. unique script id
	 * @type string $url  Required. the file url.
	 * @type string $path Optional. absolute path to the file.
	 * }
	 */
	public function scripts( string $context ): array;
}
