<?php

namespace BetterFrameworkPackage\Component\Control\Typography;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class TypographyControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'typography';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\Typography\TypographyAjaxHandler();
	}

	/**
	 * @inheritDoc
	 */
	public function scripts_list(): array {

		$this->load_media_assets();

		return [];
	}

	public function modify_props( array $props ): array {

		$parent_font  = trim( $props['parent_typo_options']['family'] ?? '' );
		$current_font = trim( $props['value']['family'] ?? '' );

		if ( $parent_font && $parent_font === $current_font ) {

			$props['family_input_value'] = $current_font;
			$props['value']['family']    = $parent_font;
			$props['use_parent_font']    = true;
		}

		// Get current font
		$props['font'] = \BetterFrameworkPackage\Component\Control\Typography\Helpers::font( $props['value']['family'] ?? '' );

		if ( ! $props['font'] ) {

			$props['font'] = [
				'type' => '',
			];
		}

		$props['variants_options'] = \BetterFrameworkPackage\Component\Control\Typography\Helpers::font_variants_option_elements( $props['font'], $props['value']['variant'] ?? '' );
		$props['subset_options']   = \BetterFrameworkPackage\Component\Control\Typography\Helpers::font_subset_option_elements( $props['font'], $props['value']['subset'] ?? '' );

		if ( ! isset( $props['classes'] ) ) {
			$props['classes'] = [];
		}

		$props['classes'] = (array) $props['classes'];

		if ( ! isset( $props['container_attributes'] ) ) {
			$props['container_attributes'] = [];
		}

		$is_top_level_parent_font = isset( $props['parent_typo'] ) && $props['parent_typo'] === true;

		if ( $is_top_level_parent_font ) {

			$props['container_attributes']['data-control-id'] = $props['id'];
			$props['classes'][]                               = 'bf-parent-font-section';

		} elseif ( ! empty( $props['parent_typo'] ) ) {

			$props['classes'][] = 'bf-child-font-section';
						$props['container_attributes']['data-parent-font'] = $props['parent_typo'];
		}

		return $props;
	}

	public function data_type(): string {

		return 'object';
	}

	public function modify_save_value( $value, array $props = [] ) {

		$parent_font         = trim( $props['parent_typo_options']['family'] ?? '' );
		$current_font        = trim( $value['family'] ?? '' );
		$value['use_parent'] = $parent_font && ( $parent_font === $current_font || 'parent_font' === $current_font );

		if ( $value['use_parent'] ) {

			$value['family']     = $parent_font;
			$value['use_parent'] = true;
		}

		return $value;
	}
}
