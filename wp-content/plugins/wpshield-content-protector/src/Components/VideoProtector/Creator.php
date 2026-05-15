<?php

namespace WPShield\Plugin\ContentProtector\Components\VideoProtector;

use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Plugin\ContentProtector\Core\CreatorBase;

/**
 * Class Creator
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Components\VideoProtector
 */
class Creator extends \WPShield\Core\PluginCore\Core\Contracts\Creator {

	/**
	 * Implements creator base functionalities.
	 */
	use CreatorBase;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return Module
	 */
	public function factory_method(): Module {

		return new VideoProtector( $this->get_plugin() );
	}
}
