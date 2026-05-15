<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\UserRoles;

use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core\CreatorInterface;

/**
 * Class Creator
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\UserRoles
 */
class Creator extends \WPShield\Core\PluginCore\Core\Contracts\Creator implements CreatorInterface {

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'user-role';
	}

	/**
	 * @inheritDoc
	 *
	 * @return Module
	 */
	public function factory_method(): Module {

		return new UserRolesCondition();
	}
}
