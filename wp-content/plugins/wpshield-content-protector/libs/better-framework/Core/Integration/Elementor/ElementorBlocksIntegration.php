<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

// use asset loader APIs
use \BetterFrameworkPackage\Asset\{
	Enqueue
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Block as BlockStandards
};

use Elementor;


class ElementorBlocksIntegration implements \BetterFrameworkPackage\Component\Standard\Block\BlockIntegrationInterface {

	/**
	 * Blocks list stack
	 *
	 * @var BlockStandards\BlockInterface[]
	 * @since 4.0.0
	 */
	protected $blocks = [];

	/**
	 * Store BlockAssets instance
	 *
	 * @var BlockStandards\BlockAssets
	 * @since 4.0.0
	 */
	protected $assets;

	/**
	 * Integration constructor.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {

		if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {

			add_action( 'elementor/widgets/register', [ $this, 'register_widget' ] );

		} else {

			add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widget' ], 20 );
		}

		add_action( 'elementor/elements/categories_registered', [ $this, 'register_categories' ] );

		$this->assets = new \BetterFrameworkPackage\Component\Standard\Block\BlockAssets( \BetterFrameworkPackage\Asset\Enqueue\EnqueueScript::instance(), \BetterFrameworkPackage\Asset\Enqueue\EnqueueStyle::instance() );
	}

	/**
	 * @param BlockStandards\BlockInterface $block
	 *
	 * @since 4.0.0
	 * @return bool
	 */
	public function register( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): bool {

		$this->blocks[ $block->block_id() ] = $block;

		$this->assets->enqueue_js( $block );
		$this->assets->enqueue_css( $block );

		return true;
	}


	/**
	 * @param Elementor\Widgets_Manager $widget_manager
	 *
	 * @since 4.0.0
	 */
	public function register_widget( Elementor\Widgets_Manager $widget_manager ): void {

		foreach ( $this->blocks as $block ) {

			if ( ! $instance = $this->elementor_widget_instance( $block ) ) {

				continue;
			}

			if ( \is_callable( [ $widget_manager, 'register' ] ) ) {

				$widget_manager->register( $instance );

			} else {

				$widget_manager->register_widget_type(
					$instance
				);
			}
		}
	}


	/**
	 * Registering custom categories.
	 *
	 * @param Elementor\Elements_Manager $elements_manager
	 *
	 * @since 4.0.0
	 */
	public function register_categories( Elementor\Elements_Manager $elements_manager ): void {

		foreach ( $this->blocks as $block ) {

			if ( ! $instance = $this->elementor_widget_instance( $block ) ) {

				continue;
			}

			$custom_categories = array_merge( $custom_categories ?? [], $instance->get_categories() );
		}

		if ( empty( $custom_categories ) ) {

			return;
		}

		$custom_categories = array_values( array_filter( array_unique( $custom_categories ) ) );

		foreach ( $custom_categories as $category ) {

			$elements_manager->add_category(
				$category,
				[
					'title' => $category,
					'icon'  => 'fa fa-plug',
				]
			);
		}
	}


	/**
	 * @param BlockStandards\BlockInterface $block
	 *
	 * @since 4.0.0
	 * @return ElementorWidgetAdapter|null object on success
	 */
	public function elementor_widget_instance( \BetterFrameworkPackage\Component\Standard\Block\BlockInterface $block ): ?\BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorWidgetAdapter {

		try {

			return new \BetterFrameworkPackage\Framework\Core\Integration\Elementor\ElementorWidgetAdapter( $block );

			// @codeCoverageIgnoreStart
		} catch ( \Exception $e ) {
		}

		return null;
	}
	// @codeCoverageIgnoreEnd

	/**
	 * List of blocks id that will be used/rendered in the page.
	 *
	 * @hooked template_redirect
	 *
	 * @since  4.0.0
	 * @return string[]|string
	 */
	public function blocks_id() {

		if ( is_admin() || Elementor\Plugin::$instance->preview->is_preview_mode() ) {

			return 'all';
		}

		if ( is_singular() && Elementor\Plugin::$instance->documents->get( \get_the_ID() )->is_built_with_elementor() ) {

			return $this->list_post_widgets_id(
				\get_the_ID()
			);
		}

		return [];
	}


	/**
	 * @param int $post_id
	 *
	 * @since 4.0.0
	 * @return string[]
	 */
	public function list_post_widgets_id( int $post_id ): array {

		$elementor_data = get_post_meta( $post_id, '_elementor_data', true );

		if ( preg_match_all( '#widgetType\"\s*\:\s*"([^\"]+)#', $elementor_data, $matches ) ) {

			return $matches[1];
		}

		return [];
	}


	/**
	 * @since 4.0.0
	 * @return bool
	 */
	public static function is_enable(): bool {

		return \defined( 'ELEMENTOR_VERSION' ) && ELEMENTOR_VERSION;
	}
}
