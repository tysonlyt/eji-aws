<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\User;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class User
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\User
 */
class UserCondition extends BaseModule implements Module, Installable {

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

		return 'user';
	}

	/**
	 * @inheritDoc
	 * @return bool this is true when current user ID exists in selected users, false on failure!
	 */
	public function active(): bool {

		#Get current logged in user.
		$this->user = wp_get_current_user();

		if ( ! isset( $this->filter['user'] ) || ! $this->user || ! $this->user->ID ) {

			return false;
		}

		$haystack = explode( ',', $this->filter['user'] );
		$haystack = array_values( array_filter( array_map( 'intval', $haystack ) ) );

		return in_array( $this->user->ID, $haystack, true );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return true
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
