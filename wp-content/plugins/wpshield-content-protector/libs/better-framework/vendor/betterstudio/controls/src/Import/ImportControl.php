<?php

namespace BetterFrameworkPackage\Component\Control\Import;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{Control as ControlStandard, Control\HandleAjaxRequest};

class ImportControl extends \BetterFrameworkPackage\Component\Control\BaseControl implements
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps,
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler,
	\BetterFrameworkPackage\Component\Standard\Control\HaveScripts,
	\BetterFrameworkPackage\Component\Standard\Control\HaveStyles {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'import';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	public function styles_list(): array {

		return [
			[
				'id' => 'buttons',
			],
		];
	}

	public function scripts_list(): array {

		return [
			[
				'id' => 'jquery-ui-core',
			],
		];
	}

	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\Import\ImportAjaxHandler();
	}

	public function modify_props( array $props ): array {

		$props['token'] = wp_create_nonce( 'import:' . ( $props['panel_id'] ?? '' ) );

		return $props;
	}
}
