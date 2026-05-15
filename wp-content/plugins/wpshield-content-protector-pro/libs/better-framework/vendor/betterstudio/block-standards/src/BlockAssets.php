<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

use BetterFrameworkPackage\Asset\Enqueue;
use BetterFrameworkPackage\Component\Standard\Block as BlockStandards;

class BlockAssets {

	/**
	 * Store Enqueue JS instance
	 *
	 * @var Enqueue\EnqueueInterface
	 */
	protected $enqueue_js;

	/**
	 * Store Enqueue CSS instance
	 *
	 * @var Enqueue\EnqueueInterface
	 *
	 * @since 1.0.0
	 */
	protected $enqueue_css;

	/**
	 * @param Enqueue\EnqueueInterface $enqueue_js
	 * @param Enqueue\EnqueueInterface $enqueue_css
	 *
	 * @since 1.0.0
	 */
	public function __construct( \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface $enqueue_js, \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface $enqueue_css ) {

		$this->enqueue_js  = $enqueue_js;
		$this->enqueue_css = $enqueue_css;
	}

	/**
	 * Enqueue the block JS file.
	 *
	 * @param BlockStandards\BlockInterface $block
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public function enqueue_js( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): bool {

		if ( ! $block instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveScripts ) {

			return false;
		}

		$script_ids = [];

		/**
		 * FIXME: Add Context
		 */
		foreach ( $block->scripts( '' ) as $script ) {

			$script_ids[] = $script['id'];

			$this->enqueue_js->add( $script['id'], $script['url'], $script['path'] ?? '' );
		}

		$this->enqueue_js->enqueue( $script_ids );

		return true;
	}

	/**
	 * Enqueue the block css file.
	 *
	 * @param BlockStandards\BlockInterface $block
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public function enqueue_css( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): bool {

		if ( ! $block instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveStyles ) {

			return false;
		}

		$style_ids = [];

		foreach ( $block->styles( '' ) as $style ) {

			$style_ids[] = $style['id'];

			$this->enqueue_css->add( $style['id'], $style['url'], $style['path'] ?? '' );
		}

		$this->enqueue_css->enqueue( $style_ids );

		return true;
	}
}
