<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

interface BlockIntegrationInterface {

	/**
	 * Register a block.
	 *
	 * @param BlockInterface $block
	 *
	 * @since 1.0.0
	 * @return bool true on success or false otherwise.
	 */
	public function register( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): bool;


	/**
	 * List of blocks id that will be used/rendered in the page.
	 *
	 * @hooked template_redirect
	 *
	 * @since  1.0.0
	 * @return string[]|bool|string
	 *
	 * string      : 'all' to register all blocks without filter
	 * bool        : false on failure.
	 * string[]    : on success, return blocks list id
	 * empty array : will not register any block.
	 */
	public function blocks_id();
}
