<?php declare( strict_types=1 );

namespace BetterFrameworkPackage\Component\Integration\Block;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

/**
 * @internal
 * @todo    rename
 */
class IntegrationFireUp {

	/**
	 * Store the block integration instance.
	 *
	 * @var BlockStandards\BlockIntegrationInterface
	 * @since 1.0.0
	 */
	protected $integration;

	/**
	 * @var string[]
	 *
	 * @since 1.0.0
	 */
	protected $available_blocks;


	/**
	 * @param BlockStandards\BlockIntegrationInterface $integration
	 *
	 * @since 1.0.0
	 */
	public function __construct( \BetterFrameworkPackage\Component\Standard\Block\BlockIntegrationInterface $integration ) {

		$this->integration = $integration;
	}

	/**
	 * Register active standards blocks.
	 *
	 * @param BlockStandards\BlockInterface[] $blocks
	 *
	 * @since 1.0.0
	 * @return bool true on success or false on failure.
	 */
	public function register( array $blocks ): bool {

		if ( empty( $blocks ) ) {

			return false;
		}

		$active_blocks = $this->available_blocks();
		$enable_filter = $active_blocks !== 'all';

		foreach ( $blocks as $block_id => $block ) {

			if ( $enable_filter && ! \in_array( $block_id, $active_blocks, true ) ) {

				continue;
			}

			$this->integration->register(
				\is_object( $block ) ? $block : new $block()
			);
		}

		return true;
	}

	/**
	 * @see   BlockStandards\BlockIntegrationInterface::blocks_id
	 *
	 * @since 1.0.0
	 * @return string[]|string
	 */
	protected function available_blocks() {

		if ( ! isset( $this->available_blocks ) ) {

			$available_blocks = $this->integration->blocks_id();

			if ( ! \is_bool( $available_blocks ) ) {

				$this->available_blocks = $available_blocks;
			}
		}

		return apply_filters(
			'better-studio/blocks/available',
			$this->available_blocks ? : []
		);
	}
}
