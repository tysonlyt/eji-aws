<?php

namespace WPShield\Plugin\ContentProtector\Components\FeedProtector;

use WPShield\Core\PluginCore\Core\{
	Contracts\Module,
	Contracts\Installable,
	ComponentBase as Base
};
use WPShield\Plugin\ContentProtector\Core\Component;

/**
 * Class PrintProtector
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\PrintProtector
 */
class FeedProtector extends Component implements Module, Installable {

	/**
	 * Implements component base functionalities.
	 *
	 * @since 1.0.0
	 */
	use Base;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'feed';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function active(): bool {


		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function operation(): bool {

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( $this->is_filter() ) {

			return false;
		}

		$this->prepare();

		add_action( 'wpshield/content-protector/components/manager/mount', [ $this, 'protection' ] );

		return true;
	}

	/**
	 * Running protection.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function protection(): void {

		if ( ! is_feed() || 'redirect' !== wpshield_cp_option( wp_sprintf( '%s/type', $this->id() ) ) ) {

			return;
		}

		$link = '';

		//
		// All singulars link
		//
		if ( is_singular() ) {

			$link = get_permalink();

		} elseif ( is_post_type_archive() ) {

			//
			// All archive post types link
			//

			$object = get_queried_object();

			if ( $object && ! is_wp_error( $object ) ) {
				if ( isset( $object->query_var ) ) {
					$link = get_post_type_archive_link( $object->query_var );
				}
			}
		} else {
			//
			// All taxonomies
			//

			$object = get_queried_object();

			if ( $object && ! is_wp_error( $object ) ) {

				if ( isset( $object->term_id ) ) {
					$link = bf_get_term_link( $object, $object->taxonomy );
				}
			}
		}

		if ( empty( $link ) ) {
			$link = home_url( '/' );
		}

		wp_safe_redirect( $link );
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

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function assets(): array {

		return [];
	}
}
