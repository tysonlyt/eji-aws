<?php

namespace BetterFrameworkPackage\Utils\Icons;

use \BetterFrameworkPackage\Core\{
	Rest
};

use WP_REST_Response;

class RestHandler extends \BetterFrameworkPackage\Core\Rest\RestHandler {

	public function rest_handler( \WP_REST_Request $request ): \WP_REST_Response {

		$families = $this->families();

		return new WP_REST_Response( compact( 'families' ) );
	}

	public function families(): array {

		$families = [];

		foreach ( \BetterFrameworkPackage\Utils\Icons\IconManager::families() as $family ) {

			if ( empty( $family['url'] ) ) {

				continue;
			}

			$families[ $family['prefix'] ] = [
				'base_url' => $family['url'],
				'id'       => $family['id'],
				'prefix'   => $family['prefix'],
			];
		}

		return $families;
	}

	public function rest_permission(): bool {

		return true;
	}

	public function rest_end_point(): string {

		return 'icon-config';
	}

	public function methods(): string {

		return 'POST';
	}
}
