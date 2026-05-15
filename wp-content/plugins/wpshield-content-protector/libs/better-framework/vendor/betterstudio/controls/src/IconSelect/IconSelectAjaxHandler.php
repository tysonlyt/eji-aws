<?php

namespace BetterFrameworkPackage\Component\Control\IconSelect;

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use WordPress APIs
use WP_HTTP_Response;

class IconSelectAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {


	public function handle_request( array $params ): WP_HTTP_Response {

		switch ( $params['action'] ?? '' ) {

			case 'remove':
				// validate
				if ( ! isset( $params['icon_id'] ) || ! \is_string( $params['icon_id'] ) ) {

					throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid icon given.' );
				}

				$response = [
					'removed' => $this->icon_remove( $params['icon_id'] ),
				];

				break;

			case 'add':
				// validate
				if ( ! isset( $params['icon'] ) || ! \is_array( $params['icon'] ) ) {

					throw new \BetterFrameworkPackage\Core\Module\Exception( 'invalid icon given.' );
				}

				$icon = $params['icon'];

				if ( $icon_id = $this->icon_add( $icon ) ) {

					$response = [
						'inserted'  => true,
						'insert_id' => $icon_id,
					];
				}

				break;

			case 'template':
				$response = [
					'template' => $this->icons_template(),
				];

				break;

			default:
				$response = [
					'status' => 'icon-select-invalid-request',
				];
		}

		return $this->response( $response ?? [] );
	}


	/**
	 * Insert the given icon into the icons DB.
	 *
	 * @param array $icon
	 *
	 * @since 1.0.0
	 * @return string the inserted icon ID on success or empty string on failure.
	 */
	protected function icon_add( array $icon ): string {

		if ( ! $icons_list = get_option( 'bf_custom_icons_list', [] ) ) {

			$icons_list = [];
		}

		$icon['id']                = 'icon-' . uniqid( '', true );
		$icons_list[ $icon['id'] ] = $icon;

		if ( ! update_option( 'bf_custom_icons_list', $icons_list, 'no' ) ) {

			return '';
		}

		return $icon['id'];
	}


	/**
	 * Delete the given icon from the icons DB.
	 *
	 * @param string $icon_id .
	 *
	 * @since 1.0.0
	 * @return bool true on success or false otherwise.
	 */
	protected function icon_remove( string $icon_id ): bool {

		$icons_list = get_option( 'bf_custom_icons_list' );

		if ( ! isset( $icons_list[ $icon_id ] ) ) {

			return false;
		}

		unset( $icons_list[ $icon_id ] );

		return update_option( 'bf_custom_icons_list', $icons_list, 'no' );
	}

	/**
	 * Get the icons list templates.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function icons_template(): string {

		ob_start();

		include __DIR__ . '/templates/icons.php';

		return ob_get_clean();
	}
}
