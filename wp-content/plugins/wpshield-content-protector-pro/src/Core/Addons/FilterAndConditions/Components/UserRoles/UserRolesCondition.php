<?php

namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\UserRoles;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class UserRolesCondition
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\UserRoles
 */
class UserRolesCondition extends BaseModule implements Module, Installable {

	use ComponentBase;

	/**
	 * Store instance of WP_User Class.
	 *
	 * @var \WP_User $user
	 */
	protected $user;

	/**
	 * Store meta key name.
	 *
	 * @var string $meta_key
	 */
	protected $meta_key = 'wpshield_cp_condition_filter_user_role';

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
	 * @since 1.0.0
	 * @return bool this is true when current user roles equal with selected user roles,
	 * false on failure.
	 */
	public function active(): bool {

		if ( ! isset( $this->filter['user-role'] ) ) {

			return false;
		}

		$roles = $this->filter['user-role'];

		#Get current logged in user.
		$this->user = wp_get_current_user();

		if ( ! $this->user || ! isset( $this->user->ID ) ) {

			return false;
		}

		#Get differences between filter fields and current logged-in user roles!
		$different_roles = array_diff( $roles, $this->user->roles );

		return $roles !== $different_roles;
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

		return delete_user_meta( $this->user->ID ?? - 1, $this->meta_key );
	}
}
