<?php

namespace BetterFrameworkPackage\Component\Control\IconSelect;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use lib functions
use function \BetterFrameworkPackage\Component\Control\{
	json_decode
};

class IconSelectControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl implements
	\BetterFrameworkPackage\Component\Standard\Control\ManageControlData,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifySaveValue,
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts,
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'icon_select';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\IconSelect\IconSelectAjaxHandler();
	}

	/**
	 * @return array[]
	 */
	public function scripts_list(): array {

		$this->load_media_assets();

		return [];
	}

	public function data_type(): string {

		return 'object';
	}

	public function modify_save_value( $value, array $props = [] ) {

		if ( \is_string( $value ) ) {

			$value = \BetterFrameworkPackage\Component\Control\json_decode( $value );
		}

		unset( $value['icon_tag'] );

		return $value;
	}

	public function secure_props( array $props ): array {

		// append the icon on elementor/ gutenberg version
		if ( ! empty( $props['value'] ) ) {

			$props['icon_tag'] = \BetterFrameworkPackage\Component\Control\the_icon( $props['value'] );
		}

		return $props;
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! $use_dynamic_props; // enable secure props only in elementor/ gutenberg
	}

	public function secure_props_token( array $props ): string {

		return wp_create_nonce( 'icon-select' );
	}

	/**
	 * @return string[]
	 */
	public function dynamic_values_indexes(): array {

		return [ 'icon_tag' ];
	}
}
