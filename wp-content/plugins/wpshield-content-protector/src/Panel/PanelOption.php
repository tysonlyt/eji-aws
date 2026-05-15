<?php

namespace WPShield\Plugin\ContentProtector\Panel;

use BetterStudio\Core\Module\ModuleHandler;
use WPShield\Plugin\ContentProtector\ContentProtectorSetup;

/**
 * Class PanelComponent
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\Components\Panel
 */
class PanelOption extends ModuleHandler {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function init(): bool {

		//Add option panel for this plugin.
		include ContentProtectorSetup::instance()->dir( 'src/Panel/Options/panel.php' );

		return true;
	}
}
