<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core;

/**
 * Interface ComponentFilter
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core
 */
interface CreatorInterface {

	/**
	 * Retrieve component filter identifier.
	 *
	 * @since 1.0.0
	 * @return string include dash(-) or slash(/) identifier.
	 */
	public function id(): string;
}
