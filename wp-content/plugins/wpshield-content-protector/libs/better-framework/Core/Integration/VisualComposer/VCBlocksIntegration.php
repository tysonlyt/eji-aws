<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\VisualComposer;

use BetterFrameworkPackage\Framework\Core\Integration;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

class VCBlocksIntegration implements \BetterFrameworkPackage\Component\Standard\Block\BlockIntegrationInterface {

	public function register( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): bool {

		if ( ! $block instanceof \BetterFrameworkPackage\Framework\Core\Integration\BlockAdapter ) {

			return false;
		}

		$options = $block->shortcode_options();

		if ( isset( $options['have_vc_add_on'] ) && ! $options['have_vc_add_on'] ) {

			return false;
		}

		return vc_map(
			[
				'params' => $this->controls( $block ),
				'name'   => $block->block_name(),
				'base'   => $block->block_id(),
				'icon'   => $block->block_icon(),
			]
		);
	}

	/**
	 * @param BlockStandards\HaveControls $block
	 *
	 * @since 4.0.0
	 * @return array
	 */
	protected function controls( \BetterFrameworkPackage\Component\Standard\Block\HaveControls $block ): array {

		return ( new \BetterFrameworkPackage\Framework\Core\Integration\VisualComposer\VCControlsTransformer( $block->fields(), $block->defaults() ) )->transform();
	}

	/**
	 * @return bool
	 */
	public static function is_enable(): bool {

		return \defined( 'WPB_VC_VERSION' ) && WPB_VC_VERSION;
	}


	/**
	 * @since 4.0.0
	 * @return array|string
	 */
	public function blocks_id() {
		global $pagenow;

		if ( \in_array( $pagenow, [ 'post.php', 'post-new.php' ], true ) ) {

			return 'all';
		}

		if ( self::is_edit_form() || vc_is_inline() || vc_is_editor() || vc_is_frontend_ajax() ) {

			return 'all';
		}

		return [];
	}

	public static function is_edit_form(): bool {

		return bf_is_doing_ajax( 'vc_edit_form' );
	}
}
