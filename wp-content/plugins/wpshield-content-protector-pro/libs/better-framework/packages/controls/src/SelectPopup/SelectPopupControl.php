<?php

namespace BetterFrameworkPackage\Component\Control\SelectPopup;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use pro-feature api
use \BetterFrameworkPackage\Component\Control\{
	Features\ProFeature
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class SelectPopupControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue,
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'select_popup';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function modify_props( array $props ): array {

		$props['data2print'] = $this->data2print( $props );

		return $props;
	}


	public function secure_props( array $props ): array {

		if ( ! empty( $props['deferred-options'] ) ) {

			$props['options'] = \BetterFrameworkPackage\Component\Control\Helper::deferred_options( $props['deferred-options'] );
			$props['options'] = $this->pro_feature_options( $props['options'] );

			$props['data2print'] = $this->data2print( $props );
		}

		return $props;
	}


	/**
	 * @param array $props
	 *
	 * @return array
	 */
	protected function data2print( array $props ): array {

		$data2print = wp_array_slice_assoc(
			$props,
			[
				'texts',
				'confirm_texts',
				'column_class',
				'confirm_changes',
			]
		);

		foreach ( $props['options'] ?? [] as $key => $option ) {

			if ( empty( $option['info'] ) ) {
				$option['info'] = [];
			}

			$option['info']['img']   = $option['img'];
			$option['info']['label'] = $option['label'];

			if ( isset( $option['badges'] ) ) {
				$option['info']['badges'] = $option['badges'];
			}

			if ( isset( $option['class'] ) ) {
				$option['info']['class'] = $option['class'];
			}

			if ( ! empty( $option['current_img'] ) ) {
				$option['info']['current_img'] = $option['current_img'];
			}

			$data2print['options'][ $key ] = map_deep( $option['info'], 'esc_attr' );

			if ( isset( $option['attributes'] ) ) {

				$data2print['options'][ $key ]['attributes'] = $option['attributes'];
			}
		}

		return $data2print;
	}

	public function pro_feature_options( array $options ): array {

		return array_map(
			static function ( $option ) {

				if ( ! isset( $option['class'] ) ) {
					$option['class'] = '';
				}

				$option['is_pro'] = \BetterFrameworkPackage\Component\Control\Features\ProFeature::register( $option );

				if ( $option['is_pro'] ) {

					$option['class']     .= ' pro-feature';
					$option['attributes'] = \BetterFrameworkPackage\Component\Control\Features\ProFeature::element_data_attributes( $option );
				}

				return $option;

			},
			$options
		);
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! empty( $props['deferred-options'] );
	}

	public function secure_props_token( array $props ): string {

		if ( empty( $props['deferred-options'] ) ) {

			return '';
		}

		return \BetterFrameworkPackage\Component\Control\Helper::deferred_options_token( $props['deferred-options'] );
	}


	/**
	 * Don't save pro_feature option when it's active.
	 *
	 * @param mixed $value
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function modify_save_value( $value, array $props = [] ) {

		$options = [];

		if ( isset( $props['deferred-options'] ) ) {

			$options = \BetterFrameworkPackage\Component\Control\Helper::deferred_options( $props['deferred-options'] );

		} elseif ( isset( $props['options'] ) ) {

			$options = &$props['options'];
		}

		$option = $options[ $value ] ?? [];

		// is pro-feature modal active this option?
		if ( isset( $option['pro_feature']['modal_id'] ) && \BetterFrameworkPackage\Component\Control\Features\ProFeature::is_active( $option['pro_feature']['modal_id'] ) ) {

			return null;
		}

		return $value;
	}

	public function data_type(): string {

		return 'string';
	}
}
