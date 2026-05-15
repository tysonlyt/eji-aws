<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

use Elementor as ElementorPlugin;
use BetterFrameworkPackage\Component\Standard\Block as BlockStandards;

use Exception;

class ElementorWidgetAdapter extends ElementorPlugin\Widget_Base {

	/**
	 * @var BlockStandards\BlockInterface
	 */
	protected $block;

	/**
	 * ElementorWidgetAdapter constructor.
	 *
	 * @param array|BlockStandards\BlockInterface $data Widget data. Default is an empty array.
	 * @param array|null                          $args Optional. Widget default arguments. Default is null.
	 *
	 * @throws Exception
	 * @since 4.0.0
	 */
	public function __construct( $data = [], $args = null ) {

		if ( $data instanceof \BetterFrameworkPackage\Component\Standard\Block\BlockInterface ) {

			$this->block = $data;

			parent::__construct();

		} else {

			if ( isset( $data['widgetType'] ) ) {

				$this->block = $this->block( $data['widgetType'] );
			}

			parent::__construct( $data, $args );
		}
	}


	/**
	 * @param string|BlockStandards\BlockInterface $block
	 *
	 * @since 4.0.0
	 * @return BlockStandards\BlockInterface|BlockStandards\BlockInterface[]|null
	 */
	public function block( $block = null ) {

		if ( empty( $block ) ) {

			return $this->block;
		}

		if ( $block instanceof \BetterFrameworkPackage\Component\Standard\Block\BlockInterface ) {

			$this->block = $block;

		} elseif ( $instance = \BetterFrameworkPackage\Component\Standard\Block\BlockStorage::factory( $block ) ) {

			return $instance;
		}

		return null;
	}

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @return string The name.
	 */
	public function get_name(): string {

		if ( $this->block ) {

			return $this->block->block_id();
		}

		return '';
	}

	/**
	 * Get element icon.
	 *
	 * Retrieve the element icon.
	 *
	 * @return string The icon
	 */
	public function get_icon(): string {

		if ( $this->block ) {

			return $this->block->block_icon();
		}

		return parent::get_icon();
	}

	/**
	 * Get the block title.
	 * t
	 *
	 * @since        4.0.0
	 * @return string
	 * @noinspection PhpMissingParentCallCommonInspection
	 */
	public function get_title(): string {

		if ( $this->block ) {

			return $this->block->block_name();
		}

		return '';
	}

	/**
	 * @since        4.0.0
	 * @return array
	 * @noinspection PhpMissingParentCallCommonInspection
	 */
	public function get_categories(): array {

		$categories = [];

		if ( $this->block instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveOptions ) {
			$categories = [ $this->block->options()['category'] ?? 'general' ];
		}

		return $categories;
	}

	/**
	 * @since        4.0.0
	 * @noinspection
	 * PhpMethodNamingConventionInspection
	 * PhpMissingParentCallCommonInspection
	 */
	protected function register_controls(): bool {

		if ( ! $this->block instanceof \BetterFrameworkPackage\Component\Standard\Block\HaveControls ) {

			return false;
		}

		$transformer = new \BetterFrameworkPackage\Framework\Core\Integration\Elementor\BlockControlsTransformer(
			$this,
			$this->block
		);

		$transformer->transform_widget_controls();

		return true;
	}

	/**
	 * Render element.
	 *
	 * Generates the final HTML on the frontend.
	 *
	 * @since        4.0.0
	 * @access       protected
	 * @noinspection PhpMissingParentCallCommonInspection
	 */
	protected function render(): void {

		echo $this->block->block_render( $this->get_settings_for_display() ?? [] );
	}

	/**
	 * @inheritDoc
	 *
	 * @return array
	 */
	protected function get_initial_config(): array {

		$config              = parent::get_initial_config();
		$config['bf_widget'] = true;

		return $config;
	}
}
