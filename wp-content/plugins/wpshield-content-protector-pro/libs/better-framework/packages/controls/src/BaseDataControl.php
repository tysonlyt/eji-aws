<?php

namespace BetterFrameworkPackage\Component\Control;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

use function \BetterFrameworkPackage\Component\Control\{
	json_decode
};

abstract class BaseDataControl extends \BetterFrameworkPackage\Component\Control\BaseControl implements \BetterFrameworkPackage\Component\Standard\Control\HaveData {

	public function render( array $props = [], array $options = [] ): string {

		$this->options = $options;
		// Auto decode json values.
		if ( isset( $props['value'] ) && \is_string( $props['value'] ) &&
			 \in_array( $this->data_type(), [ 'object', 'array' ], true ) ) {

			$new_value = \BetterFrameworkPackage\Component\Control\json_decode( $props['value'] );

			if ( \is_array( $new_value ) ) {

				$props['value'] = $new_value;
			}
		}

		return parent::render( $props, $options );
	}
}
