<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core;

/**
 * Interface CreatorInterface
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core
 */
trait BaseCreator {

	/**
	 * Store protector identifier.
	 *
	 * @var string $protector
	 */
	protected $protector;

	/**
	 * Store filter params value as array.
	 *
	 * @var array $filter_props
	 */
	protected $filter_props;

	/**
	 * Get protector name.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_protector(): string {
		return $this->protector;
	}

	/**
	 * Get filter properties.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_filter_props(): array {

		if ( ! isset( $this->filter_props['type'] ) ) {

			$this->filter_props['type'] = 'include';
		}

		return $this->filter_props;
	}

	/**
	 * Setup protector name.
	 *
	 * @param string $protector
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function set_protector( string $protector ): void {
		$this->protector = $protector;
	}

	/**
	 * Setup filter properties.
	 *
	 * @param array $filter_props
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function set_filter_props( array $filter_props ): void {
		$this->filter_props = $filter_props;
	}
}
