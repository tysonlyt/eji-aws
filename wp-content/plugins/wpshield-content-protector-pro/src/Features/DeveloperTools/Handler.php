<?php

namespace WPShield\Plugin\ContentProtectorPro\Features\DeveloperTools;

use WPShield\Core\PluginCore\Core\Contracts\Installable;
use WPShield\Core\PluginCore\Core\Contracts\Module;
use WPShield\Core\PluginCore\Core\ComponentBase;
use WPShield\Plugin\ContentProtectorPro\Features\Feature;


/**
 * Class Module
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Modules\DeveloperTools
 */
class Handler implements Module,Installable {

	/**
	 * @implements Base structure for component module.
	 */
	use ComponentBase,Feature;

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function id(): string {

		return 'developer-tools';
	}

	public function operation(): bool {

		if ( ! $this->allow_access() ) {

			return false;
		}

		$this->prepare();

		return true;
	}

	public function clear_data(): bool {

		return true;
	}

	public function assets(): array {

		return [];
	}

	public function active(): bool {

		return true;
	}
}
