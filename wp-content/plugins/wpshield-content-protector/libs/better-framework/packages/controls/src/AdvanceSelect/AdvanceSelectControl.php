<?php

namespace BetterFrameworkPackage\Component\Control\AdvanceSelect;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

use \BetterFrameworkPackage\Component\Control\{
	Features\ProFeature
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class AdvanceSelectControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'advance_select';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function modify_props( array $props ): array {

		if ( isset( $props['value'] ) && $props['value'] === '' && ! isset( $props['options'][''] ) ) {

			if ( isset( $props['options'][0] ) ) {

				$props['value'] = 0;

			} elseif ( isset( $props['options']['0'] ) ) {

				$props['value'] = '0';
			}
		}

		$props['status_class'] = $this->classes_list( $props );
		$props['choices']      = $this->choices( $props );

		return $props;
	}

	/**
	 * @param array $props
	 *
	 * @return string
	 */
	protected function classes_list( array &$props ): string {

		$classes = '';

		if ( ! empty( $props['vertical'] ) ) {

			$classes .= ' vertical';
		}

		if ( ! empty( $props['multiple'] ) ) {

			$classes .= ' multiple';
		}

		if ( ! empty( $props['allow_deselect'] ) ) {

			$classes .= ' allow_deselect';
		}

		return $classes;
	}

	protected function choices( array &$props ): array {

		$options_list = [];
		$saved_items  = explode( ',', $props['value'] ?? '' );

		foreach ( $props['options'] ?? [] as $option_id => $option ) {

			if ( ! \is_array( $option ) ) {

				$option = [ 'label' => $option ];
			}

			$option['active']              = \in_array( $option_id, $saved_items, false );
			$option['is_pro']              = \BetterFrameworkPackage\Component\Control\Features\ProFeature::register( $option );
			$option['icon']                = $this->normalize_icon( $option['icon'] ?? '' );
			$option['badge']               = $this->normalize_badge( $option['badge'] ?? '' );
			$option['inline_styles']       = $this->generate_css_vars( 'primary-color', $option['color'] ?? '' );
			$option['inline_styles']      .= $this->generate_css_vars( 'text-color', $option['text_color'] ?? '' );
						$option['classes'] = $this->option_classes( $option );

			$options_list[] = [ (string) $option_id, $option ];
		}

		return $options_list;
	}

	/**
	 * @param string|array $icon
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function normalize_icon( $icon ): array {

		if ( \is_string( $icon ) && ! empty( $icon ) ) {

			$icon = [ 'icon' => $icon ];
		}

		return \is_array( $icon ) ? $icon : [];
	}

	/**
	 * @param string|array $badge
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function normalize_badge( $badge ): array {

		if ( \is_string( $badge ) && ! empty( $badge ) ) {

			$badge = [ 'label' => $badge ];
		}

		if ( ! \is_array( $badge ) ) {

			return [];
		}

		$badge['icon']           = $this->normalize_icon( $badge['icon'] ?? '' );
		$badge['inline_styles']  = $this->generate_css_vars( 'primary-color', $badge['color'] ?? '' );
		$badge['inline_styles'] .= $this->generate_css_vars( 'text-color', $badge['text_color'] ?? '' );

		if ( ! isset( $badge['classes'] ) ) {

			$badge['classes'] = '';
		}

		if ( ! empty( $badge['icon']['position'] ) ) {

			$badge['classes'] .= ' icon-' . $badge['icon']['position'];
		}

		return $badge;
	}

	/**
	 * @param string $color Hex color code
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function generate_css_vars( string $prop, string $color ): string {

		$inline_styles = '';

		if ( ! empty( $color ) ) {

			$inline_styles .= sprintf( '--%s: %s;', esc_attr( $prop ), esc_attr( $color ) );
		}

		return $inline_styles;
	}

	/**
	 * @param array $option
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function option_classes( array &$option ): string {

		$classes = $option['classes'] ?? '';

		if ( ! empty( $option['disable'] ) ) {

			$classes .= ' disable';
		}

		if ( ! empty( $option['icon']['position'] ) ) {

			$classes .= ' icon-' . $option['icon']['position'];
		}

		if ( ! empty( $option['is_pro'] ) ) {

			$classes .= ' pro-feature';
		}

		return trim( $classes );
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function data_type(): string {

		return 'string';
	}

	public function secure_props( array $props ): array {

		if ( isset( $props['deferred-options'] ) ) {

			$props['options'] = \BetterFrameworkPackage\Component\Control\Helper::deferred_options( $props['deferred-options'] );
		}

		return $props;
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! empty( $props['deferred-options'] );
	}

	public function secure_props_token( array $props ): string {

		if ( ! empty( $props['deferred-options'] ) ) {

			return \BetterFrameworkPackage\Component\Control\Helper::deferred_options_token( $props['deferred-options'] );
		}

		return '';
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
}
