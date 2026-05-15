<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface WillModifySaveValue {

	/**
	 * @param mixed $value
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function modify_save_value( $value, array $props = [] );
}
