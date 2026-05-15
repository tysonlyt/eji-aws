<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface HaveSecureProps {

	/**
	 * @param array $props the props list
	 *
	 * @since 1.0.0
	 * @return array new props list
	 */
	public function secure_props( array $props ): array;

	/**
	 * @param array $props the props list
	 * @param bool  $use_dynamic_props
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool;

	/**
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function secure_props_token( array $props ): string;
}
