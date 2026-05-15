<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\PostType;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class User
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\PostType
 */
class PostTypeCondition extends BaseModule implements Module, Installable {

	use ComponentBase;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function id(): string {

		return 'post';
	}

	/**
	 * @inheritDoc
	 * @return bool this is true when current post type exists in post types selected,
	 * or current post ID exists on post ID's selected, false when otherwise!
	 */
	public function active(): bool {

		global $post;

		if ( ! $post || ! $post->ID || ! is_single() ) {

			return false;
		}

		if ( ( ! isset( $this->filter['post-type'] ) && ! isset( $this->filter['post'] ) ) ) {

			return false;
		}

		$needle = get_post_type( $post->ID );

		if ( isset( $this->filter['post-type'] ) && in_array( $needle, $this->filter['post-type'], true ) ) {

			return true;
		}

		if ( isset( $this->filter['post'] ) ) {

			$haystack = array_values(
				array_map(
					'intval',
					array_unique(
						array_map( 'trim', explode( ',', $this->filter['post'] ) )
					)
				)
			);

			if ( in_array( $post->ID, $haystack, true ) ) {

				return true;
			}
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
