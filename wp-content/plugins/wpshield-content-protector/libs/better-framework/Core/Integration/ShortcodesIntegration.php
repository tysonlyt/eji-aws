<?php

namespace BetterFrameworkPackage\Framework\Core\Integration;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

class ShortcodesIntegration implements \BetterFrameworkPackage\Component\Standard\Block\BlockIntegrationInterface {

	public function register( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): bool {

		add_shortcode( $block->block_id(), [ $block, 'block_render' ] );

		return true;
	}

	/**
	 * @return string
	 */
	public function blocks_id(): string {

		return 'all';
	}
}
