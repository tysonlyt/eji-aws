<?php


namespace WPShield\Plugin\ContentProtector\Components\RightClickProtector;

use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Plugin\ContentProtector\Core\CreatorBase;

/**
 * Class Creator
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\RightClickProtector
 */
class Creator extends \WPShield\Core\PluginCore\Core\Contracts\Creator {

	/**
	 * Implements creator base functionalities.
	 */
	use CreatorBase;

	/**
	 * @inheritDoc
	 *
	 * @return Module
	 */
	public function factory_method(): Module {

		return new RightClickComponent( $this->get_plugin() );
	}
}
