<?php

namespace BetterFrameworkPackage\Asset\Loader;

class LoadStyles extends \BetterFrameworkPackage\Asset\Loader\LoaderBase {

	public function headers(): array {

		$expires_offset = 31536000; // 1 year.

		return [
			'Expires'       => gmdate( 'D, d M Y H:i:s', time() + $expires_offset ) . ' GMT',
			'Content-Type'  => 'text/css; charset=UTF-8',
			'Cache-Control' => "public, max-age=$expires_offset",
		];
	}
}
