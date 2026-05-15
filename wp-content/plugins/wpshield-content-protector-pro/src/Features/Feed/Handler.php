<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\Feed;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtector\Core\Component;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\Feed
 */
class Handler extends Component implements Module, Installable {

	/**
	 * @implements Base structure for component module.
	 */
	use ComponentBase, Feature;

	/**
	 * Store instance of images creator object.
	 *
	 * @var \WPShield\Plugin\ContentProtectorPro\Features\Feed\Creator
	 */
	protected $creator;

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
	 * @return bool
	 */
	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		if ( $this->is_filter() ) {

			return false;
		}

		add_action( 'template_redirect', [ $this, 'template_redirect_handle' ], 22 );

		if ( 'disable' === wpshield_cp_option( $this->id() ) ) {

			return false;
		}

		$feed_type = wpshield_cp_option( 'feed/type' );

		if ( 'excerpt' === $feed_type ) {

			add_action( 'wpshield/content-protector/components/manager/mount', [ $this, 'update_rss_use_excerpt' ] );

		} elseif ( '404' === $feed_type ) {

			add_action( 'template_redirect', [ $this, 'redirect_feed_to_404' ], 22 );
		}

		return true;
	}

	/**
	 * Add message to footer and header of feed items.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function template_redirect_handle(): void {

		if ( ! wpshield_cp_option( 'feed/message/before' ) && ! wpshield_cp_option( 'feed/message/after' ) ) {

			return;
		}

		add_filter( 'the_content_feed', [ $this, 'append_feed_header_text' ], 10 );
		add_filter( 'the_excerpt_rss', [ $this, 'append_feed_header_text' ], 10 );
		add_filter( 'the_content_feed', [ $this, 'append_feed_footer_text' ], 20 );
		add_filter( 'the_excerpt_rss', [ $this, 'append_feed_footer_text' ], 20 );
	}

	/**
	 * Redirect all feeds to 404 page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function redirect_feed_to_404(): void {

		if ( ! is_feed() ) {
			return;
		}

		wp_safe_redirect( home_url( '/404.php' ) );
		exit;
	}

	/**
	 * Update rss_use_excerpt of core option turn on.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function update_rss_use_excerpt(): void {

		if ( ! is_feed() ) {

			return;
		}

		update_option( 'rss_use_excerpt', '1' );
	}

	/**
	 * Append custom defined message at the first of the posts
	 *
	 * @hooked the_content_feed
	 *
	 * @param string $content The current post content.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function append_feed_header_text( string $content ): string {

		return wpshield_cp_option( 'feed/message/before' ) . $content;
	}

	/**
	 * Append custom defined message at the first of the posts
	 *
	 * @hooked the_content_feed
	 *
	 * @param string $content The current post content.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function append_feed_footer_text( string $content ): string {

		return $content . wpshield_cp_option( 'feed/message/after' );
	}

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function clear_data(): bool {

		return true;
	}

	/**
	 * @inheritDoc
	 * @return array
	 */
	public function assets(): array {

		return [];
	}

	/**
	 * @inheritDoc
	 * @return bool
	 */
	public function active(): bool {

		return true;
	}
}
