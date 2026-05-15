<?php

namespace WPShield\Plugin\ContentProtector\Core\Utils;

/**
 * Class CurrentPageHelper
 *
 * @package WPShield\Plugin\ContentProtector\Core\Utils
 */
class CurrentPageHelper {

	public static function is_attachment(): bool {

		return WPQueryUtils::get_main_query()->is_attachment ?? false;
	}

	public static function attachment_url(): ?string {

		return WPQueryUtils::get_main_query()->post->guid ?? null;
	}

}