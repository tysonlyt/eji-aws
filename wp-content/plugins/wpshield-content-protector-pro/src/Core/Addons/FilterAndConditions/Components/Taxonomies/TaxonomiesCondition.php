<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\Taxonomies;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class User
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\Taxonomies
 */
class TaxonomiesCondition extends BaseModule implements Module, Installable {

	use ComponentBase;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function id(): string {

		return 'taxonomies';
	}

	/**
	 * @inheritDoc
	 * @return bool this is true when current tax exists in selected taxonomies or
	 * current post exists in selected post categories,
	 * false on failure!
	 */
	public function active(): bool {

		if ( ! isset( $this->filter['taxonomies'] ) && ! isset( $this->filter['category'] ) ) {

			return false;
		}

		// Taxonomies list maybe included empty item values so filter this list to remove empty values!
		$taxonomies = isset( $this->filter['taxonomies'] ) ? array_filter( $this->filter['taxonomies'] ) : [];

		if ( get_queried_object() && in_array( get_queried_object()->taxonomy, $taxonomies, true ) ) {

			return true;
		}

		global $post;

		if ( ! $post || ! $post->ID || ! is_single() ) {

			return false;
		}

		if ( isset( $this->filter['category'] ) && in_category( explode( ',', $this->filter['category'] ), $post->ID ) ) {

			return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function operation(): bool {

		$this->prepare();

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function assets(): array {

		return [];
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function clear_data(): bool {

		return true;
	}
}
