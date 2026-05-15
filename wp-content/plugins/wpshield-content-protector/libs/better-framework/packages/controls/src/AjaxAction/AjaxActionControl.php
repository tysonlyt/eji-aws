<?php

namespace BetterFrameworkPackage\Component\Control\AjaxAction;

// use current component
use BetterFrameworkPackage\Component\Control as LibRoot;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

class AjaxActionControl extends \BetterFrameworkPackage\Component\Control\BaseControl implements
	\BetterFrameworkPackage\Component\Standard\Control\HaveStyles,
	\BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler,
	\BetterFrameworkPackage\Component\Standard\Control\WillModifyProps {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'ajax_action';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @inheritDoc
	 */
	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest {

		return new \BetterFrameworkPackage\Component\Control\AjaxAction\AjaxActionAjaxHandler();
	}

	public function modify_props( array $props ): array {

		if ( ! empty( $props['callback'] ) ) {

			$props['token'] = wp_create_nonce( sprintf( 'bf-custom-callback:%s', $props['callback'] ?? '' ) );
		}

		if ( ! isset( $props['button-class'] ) ) {

			$props['button-class'] = '';
		}

		if ( ! isset( $props['primary'] ) || $props['primary'] ) {

			$props['button-class'] .= ' button-primary';
		}

		return $props;
	}

	public function styles_list(): array {

		return [
			[
				'id' => 'buttons',
			],
		];
	}
}
