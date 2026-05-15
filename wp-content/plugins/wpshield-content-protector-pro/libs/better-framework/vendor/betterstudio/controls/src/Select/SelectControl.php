<?php

namespace BetterFrameworkPackage\Component\Control\Select;

use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use standard APIs
use BetterFrameworkPackage\Component\Control as LibRoot;

// use lib functions
use function \BetterFrameworkPackage\Component\Control\{
	json_decode
};

class SelectControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * Store saved value.
	 *
	 * @var array
	 */
	protected $value = [];

	/**
	 * @var bool
	 */
	protected $is_multiple = false;

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'select';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 *
	 * @param array $props
	 * @param array $render_options
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function render( array $props = [], array $render_options = [] ): string {

		if ( empty( $props['value'] ) ) {

			$this->value = [];

		} elseif ( \is_array( $props['value'] ) ) {

			$this->value = $props['value'];

		} elseif ( \is_string( $props['value'] ) ) {

			if ( $value = \BetterFrameworkPackage\Component\Control\json_decode( $props['value'] ) ) {

				$this->value = $value;

			} else {

				$this->value = explode( ',', $props['value'] );
			}
		}

		$this->value       = (array) $this->value;
		$this->is_multiple = $props['multiple'] ?? false;

		return parent::render( $props, $render_options );
	}

	public function modify_props( array $props ): array {

		if ( ! $this->secure_props_needed( $props, true ) ) {

			$props['options'] = $this->filter_options_list( $props['options'] ?? [] );
		}

		return $props;
	}

	public function secure_props( array $props ): array {

		$props['options'] = $this->filter_options_list( $props['options'] ?? [] );

		if ( isset( $props['deferred-options'] ) ) {

			$props['options'] = \BetterFrameworkPackage\Component\Control\Helper::deferred_options( $props['deferred-options'] );
		}

		return $props;
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		if ( isset( $props['deferred-options'] ) ) {

			return true;
		}

		return $this->have_walker( $props['options'] ?? [] );
	}

	public function secure_props_token( array $props ): string {

		if ( ! empty( $props['deferred-options'] ) ) {

			return \BetterFrameworkPackage\Component\Control\Helper::deferred_options_token( $props['deferred-options'] );
		}

		if ( $this->have_walker( $props['options'] ?? [] ) ) {

			return wp_create_nonce( 'load-taxonomy' );
		}

		return '';
	}

	/**
	 * @param array $all_options
	 *
	 * @return array
	 */
	protected function filter_options_list( array $all_options ): array {

		$filtered = [];

		foreach ( $all_options as $option_id => $option_info ) {

			if ( $option = $this->filter_option( $option_info, $option_id ) ) {

				$filtered[ $option_id ] = $option;
			}
		}

		return $filtered;
	}


	/**
	 * @param string|array $option
	 * @param string|int   $option_key
	 *
	 * @since 1.0.0
	 * @return string|array
	 */
	protected function filter_option( $option, $option_key ) {

		// handle nested options
		if ( isset( $option['options'] ) ) {

			return [
				'label'   => $option['label'] ?? null,
				'options' => $this->filter_options_list( $option['options'] ),
			];
		}

		if ( $option_key === 'category_walker' ) {

			return [ 'raw' => $this->handle_walker( $option['taxonomy'] ?? 'category' ) ];
		}

		return $option;
	}


	/**
	 * @param string $taxonomy
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function handle_walker( string $taxonomy ): string {

		$walker_options = [
			'walker'       => new \BetterFrameworkPackage\Component\Control\Select\DropdownWalker(),
			'orderby'      => 'name',
			'multiple'     => $this->is_multiple,
			'hierarchical' => 1,
			'selected'     => $this->value,
			'show_count'   => 0,
		];

		return walk_category_dropdown_tree( get_terms( $taxonomy, $walker_options ), 0, $walker_options );
	}

	/**
	 * @param array $all_options
	 */
	public function print_options( array $all_options ): void {

		if ( isset( $all_options['label'] ) ) {

			$all_options = [ $all_options ];
		}

		foreach ( $all_options as $option_id => $option ) {

			// is option group
			if ( isset( $option['options'] ) && \is_array( $option['options'] ) ) {

				printf( '<optgroup label="%s">', $option['label'] );
				$this->print_options( $option['options'] );
				echo '</optgroup>';

				continue;
			}

			if ( isset( $option['raw'] ) ) {

				echo $option['raw'];

			} else {

				$selected = in_array( $option_id, $this->value, false ) ? ' selected="selected"' : '';
				$disabled = ! empty( $option['disabled'] ) ? ' disabled="disabled"' : '';

				printf( '<option value="%s"%s%s>%s</option>', $option_id, $selected, $disabled, $option['label'] ?? $option );
			}
		}
	}

	/**
	 * Is there any category_walker in options list
	 *
	 * @param array $options
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function have_walker( array $options ): bool {

		return (bool) strstr( serialize( $options ), 'category_walker' );
	}

	/**
	 * @return string
	 */
	public function data_type(): string {

		return 'array';
	}
}
