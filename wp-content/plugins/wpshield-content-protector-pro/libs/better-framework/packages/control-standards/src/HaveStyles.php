<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface HaveStyles {

	/**
	 * @return array[] {
	 *  [
	 *    'url' => 'Style file URL',
	 *    'path' => 'Absolute path to the file'. optional
	 *    'id' => 'Unique ID'
	 *    'deps' => dependencies array. optional
	 *  ],
	 *   ....
	 * }
	 */
	public function styles_list(): array;
}
