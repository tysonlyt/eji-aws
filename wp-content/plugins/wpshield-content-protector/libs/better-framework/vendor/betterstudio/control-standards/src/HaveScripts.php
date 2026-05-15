<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface HaveScripts {

	/**
	 * @return array[] {
	 *  [
	 *    'id' => 'Unique ID' required.
	 *    'url' => 'Script file URL'. optional
	 *    'path' => 'Absolute path to the file'. optional
	 *    'deps' => dependencies array. optional
	 *  ],
	 *   ....
	 * }
	 */
	public function scripts_list(): array;
}
