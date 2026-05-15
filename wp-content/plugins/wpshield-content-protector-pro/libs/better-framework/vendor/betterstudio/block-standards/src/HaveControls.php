<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

interface HaveControls {

	public function fields(): array;

	/***
	 * @return array
	 */
	public function defaults(): array;
}
