<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface WillModifyProps {

	/**
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return array new props array
	 */
	public function modify_props( array $props ): array;
}
