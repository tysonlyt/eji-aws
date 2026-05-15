<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\Everywhere;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class EverywhereCondition
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\Everywhere
 */
class GlobalCondition extends BaseModule implements Module, Installable {

	use ComponentBase;

	/**
	 * Store instance of WP_User Class.
	 *
	 * @var \WP_User $user
	 */
	protected $user;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function id(): string {

		return 'global';
	}

	/**
	 * @inheritDoc
	 * @return bool true.
	 */
	public function active(): bool {

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function operation(): bool {

		$this->prepare();

		return true;
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function assets(): array {

		return [];
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function clear_data(): bool {

		return true;
	}
}
