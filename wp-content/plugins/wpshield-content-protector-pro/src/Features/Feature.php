<?php

namespace WPShield\Plugin\ContentProtectorPro\Features;

use WPShield\Plugin\ContentProtectorPro\ContentProtectorSetup;
use function WPShield\Core\PluginCore\wpshield_plugin_core_is_registered_product as is_registered;

/**
 * Trait Feature
 *
 * @since 1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Features
 */
trait Feature {

	protected function allow_access():bool {

		return is_registered( ContentProtectorSetup::PRODUCT_ITEM_ID );
	}
}
