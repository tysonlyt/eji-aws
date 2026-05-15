<?php

namespace BetterFrameworkPackage\Component\Control\Export;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard,
	Control\HandleAjaxRequest
};

class ExportControl extends \BetterFrameworkPackage\Component\Control\BaseControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler,
	\BetterFrameworkPackage\Component\Standard\Control\HaveStyles {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'export';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @since 1.0.0
	 * @return array
	 */
	public function styles_list(): array {

		return [
			[
				'id' => 'buttons',
			],
		];
	}

	/**
	 * @since 1.0.0
	 * @return HandleAjaxRequest
	 */
	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\Export\ExportAjaxHandler();
	}

	public function secure_props( array $props ): array {

		$props['token'] = $this->secure_props_token( $props );

		return $props;
	}

	public function secure_props_needed( array $props, bool $use_dynamic_props ): bool {

		return ! empty( $props['panel_id'] );
	}

	/**
	 * @param array $props
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function secure_props_token( array $props ): string {

		return wp_create_nonce( 'panel-id:' . $props['panel_id'] ?? '' );
	}
}
