<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\CustomURL;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class CustomURLCondition
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\CustomURL
 */
class CustomURLCondition extends BaseModule implements Module, Installable {

	use ComponentBase;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function id(): string {

		return 'url';
	}

	/**
	 * @inheritDoc
	 * @return bool this is true when this current URL exists in selected URL's, false on failure!
	 */
	public function active(): bool {

		global $wp;

		if ( ! isset( $this->filter['url'] ) ) {

			return false;
		}

		#Extract url filter value.
		$urls = explode( "\n", $this->filter['url'] );

		$haystack = array_values(
		//Unique array items.
			array_unique(
			//trim urls after extract of string.
				array_map( 'trim', $urls )
			)
		);

		return in_array( home_url( sprintf( '%s/', $wp->request ) ), $haystack, true );
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function operation(): bool {

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
