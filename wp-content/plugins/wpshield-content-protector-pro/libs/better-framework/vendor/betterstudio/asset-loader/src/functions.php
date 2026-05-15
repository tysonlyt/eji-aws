<?php

namespace BetterFrameworkPackage\Asset;

if ( ! function_exists( '\BetterStudio\Asset\header' ) ) {

	function header( ...$args ): void {

		\header( ...$args );
	}
}
