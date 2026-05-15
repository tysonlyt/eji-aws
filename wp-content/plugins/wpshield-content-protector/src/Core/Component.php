<?php


namespace WPShield\Plugin\ContentProtector\Core;

use WPShield\Core\PluginCore\Core\Contracts\Installable;

/**
 * Class Component
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtector\Core
 */
class Component {

	/**
	 * Check this component is filter?
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
	protected function is_filter(): bool {

		if ( ! $this instanceof Installable ) {

			return false;
		}

		return 'disable' === wpshield_cp_option( $this->id() ) || Utils::is_filtered_with_conditions( $this->id() );
	}
}
