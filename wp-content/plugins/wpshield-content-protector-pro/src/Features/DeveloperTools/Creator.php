<?php


namespace WPShield\Plugin\ContentProtectorPro\Features\DeveloperTools;

use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Plugin\ContentProtector\Core\CreatorBase;

/**
 * Class Creator
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Features\DeveloperTools
 */
class Creator extends \WPShield\Core\PluginCore\Core\Contracts\Creator {

	use CreatorBase;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return Module
	 */
	public function factory_method(): Module {

		return new Handler( $this->get_plugin() );
	}
}
