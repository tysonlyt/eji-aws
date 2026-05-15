<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\CustomCssClasses;

use WPShield\Core\PluginCore\Core\{ComponentBase, Contracts\Installable, Contracts\Localization, Contracts\Module};
use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\{Core\BaseModule};

/**
 * Class CustomURLCondition
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Components\CustomCssClasses
 */
class CssClassesCondition extends BaseModule implements Module, Localization, Installable {

	use ComponentBase;

	/**
	 * @inheritDoc
	 * @return string
	 */
	public function id(): string {

		return 'css-class';
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.0.0
	 * @return bool this is true when 'include' word equal with ${this->filter['type']} is active condition, false when otherwise!
	 */
	public function active(): bool {

		if ( ! isset( $this->filter['css-class'], $this->filter['type'] ) ) {

			return false;
		}

		return 'include' === $this->filter['type'];
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
	 * @return array
	 */
	public function l10n(): array {

		return [
			'filter' => [
				'is-protected' => $this->active(),
				#Extract css classes filter value.
				'css-class'    => array_values(
					array_unique(
						array_map( 'trim', explode( "\n", trim( $this->filter['css-class'] ?? '' ) ) )
					)
				),
			],
		];
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
