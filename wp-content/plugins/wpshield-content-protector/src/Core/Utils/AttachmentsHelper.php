<?php

namespace WPShield\Plugin\ContentProtector\Core\Utils;

use BetterStudio\Core\Module\Singleton;

/**
 * Class AttachmentHelper
 *
 * the object to help working with attachment
 *
 * @package WPShield\Plugin\ContentProtector\Core\Utils
 */
class AttachmentsHelper {

	use Singleton;

	protected static $results = [];

	public static function get_attachment_related_posts( int $attachment_id ): ?self {

		if ( ! $attachment_id ) {

			return null;
		}

		global $wpdb;

		static::$results = $wpdb->get_results(
			"SELECT post_id from {$wpdb->postmeta} where meta_value={$attachment_id}"
		);

		if (!static::$results){

			return null;
		}

		return self::instance();
	}

	public function first(): ?int {

		return array_shift( self::$results )->post_id ?? null;
	}

}
