<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

interface BlockInterface {

	/**
	 * The block ID.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function block_id(): string;

	/**
	 * The block title.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function block_name(): string;

	/**
	 * Render the block.
	 *
	 * @param array $settings
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function block_render( array $settings ): string;
}
