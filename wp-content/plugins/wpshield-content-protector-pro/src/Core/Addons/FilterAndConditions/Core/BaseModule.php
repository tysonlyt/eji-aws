<?php


namespace WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core;

use WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\FilterAndConditionsSetup;

/**
 * Class BaseModule
 *
 * @since   1.0.0
 *
 * @package WPShield\Plugin\ContentProtectorPro\Core\Addons\FilterAndConditions\Core
 */
abstract class BaseModule {

	/**
	 * Store options for current filter.
	 *
	 * @var array $filter
	 */
	public $filter;

	/**
	 * @return array
	 */
	public function get_filter(): array {
		return $this->filter;
	}

	/**
	 * @param array $filter
	 */
	public function set_filter( array $filter ): void {
		$this->filter = $filter;
	}

	/**
	 * Preparing value of option or meta data!
	 *
	 * @param array  $current_value old option value for this protector
	 * @param array  $value         option value of this protector
	 * @param string $protector     protector name.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function prepare_value( array $current_value, array $value, string $protector ): array {

		{#TODO: Refactor this block of method body to convert a standalone method.
			$handler_details = array_map( 'array_keys',
				get_option(
					sprintf( '%s-handler', FilterAndConditionsSetup::instance()->product_id() ),
					[]
				)
			);

			$detail_key = sprintf( '%s/filters', $protector );

			$value = $this->hold_current_value( $current_value[ $protector ] ?? [], $value );

			//phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact
			if ( isset( $handler_details[ $detail_key ] ) && ! in_array( $protector, $handler_details, true ) ) {

				unset( $current_value[ $protector ] );
				//phpcs:ignore Generic.WhiteSpace.ScopeIndent.IncorrectExact
			}
		}

		#If it did not exist for aby reason.
		if ( empty( $current_value ) ) {

			$current_value = [
				$protector => $value,
			];

		} else {

			#Merged new meta value with old meta value to not lose the remaining protectors meta value.
			$merged_value                = array_merge( $current_value[ $protector ] ?? [], $value );
			$current_value[ $protector ] = $merged_value;
		}

		return $current_value;
	}

	/**
	 * Holding current saved data to prevent set incorrect data or remove older data.
	 *
	 * @param array $current
	 * @param array $new
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function hold_current_value( array $current, array $new ): array {

		//When current value is empty.
		if ( empty( $current ) ) {

			return $new;
		}

		//When current value equal with new value.
		if ( $current === $new ) {

			return $current;
		}

		$_new = [];

		//Deep merging current value with new value.
		foreach ( $new as $key => $field ) {

			if ( empty( $field ) && ! empty( $current[ $key ] ) ) {

				$_new[ $key ] = $current[ $key ];
				continue;
			}

			$_new[ $key ] = $field;
		}

		foreach ( $current as $key => $field ) {

			if ( ! empty( $_new[ $key ] ) ) {

				continue;
			}

			$_new[ $key ] = $field;
		}

		return $_new;
	}

	/**
	 * Enqueue component assets files.
	 *
	 * @since 1.0.0
	 * @return bool true on success, false when otherwise.
	 */
//	protected function prepare(): bool {
//
//		add_filter( 'wpshield/content-protector/enqueue-assets/assets', [ $this, 'register_assets' ] );
//
//		return true;
//	}

	/**
	 * Enqueue component assets files.
	 *
	 * @param array $assets
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function register_assets( array $assets ): array {

		return array_merge( $assets, $this->assets() );
	}

	/**
	 * Enqueue components assets on the page.
	 *
	 * @since 1.0.0
	 * @return array of assets files with details.
	 */
//	abstract public function assets(): array;
}
