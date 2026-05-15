<?php

namespace BetterFrameworkPackage\Component\Control\Core\Validation;

// use utilities
use \BetterFrameworkPackage\Utils\{
	Validator
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use integration APIs
use \BetterFrameworkPackage\Component\Integration\{
	Control as ControlIntegration
};

class ControlValidation extends \BetterFrameworkPackage\Utils\Validator\Rules\Rule {

	/**
	 * @inheritDoc
	 */
	public function check( $value ): bool {

		if ( ! $instance = \BetterFrameworkPackage\Component\Integration\Control\ControlsStorage::factory(
			$value
		) ) {

			return false;
		}

		if ( $this->options['handle_ajax_request'] && ! $instance instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveAjaxHandler ) {

			return false;
		}

		if ( $this->options['modify_settings'] && ! $instance instanceof \BetterFrameworkPackage\Component\Standard\Control\HaveSecureProps ) {

			return false;
		}

		return true;
	}

	/**
	 * @inheritDoc
	 * @return array{handle_ajax_request: true, modify_settings: true}
	 */
	public function default_options(): array {

		return [
			'handle_ajax_request' => false,
			'modify_settings'     => false,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function unnamed_option(): string {

		return '';
	}


	/**
	 * @inheritDoc
	 */
	protected function error_message( $placeholders ): string {

		return __( 'Invalid control.', 'better-studio' );
	}
}
