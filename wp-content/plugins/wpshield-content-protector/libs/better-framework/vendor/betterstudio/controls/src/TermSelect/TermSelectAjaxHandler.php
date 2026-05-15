<?php

namespace BetterFrameworkPackage\Component\Control\TermSelect;

// use standard APIs
use \BetterFrameworkPackage\Component\Standard\{
	Control as ControlStandard
};

// use core modules
use \BetterFrameworkPackage\Core\{
	Module\Exception
};

// use wp APIs
use WP_HTTP_Response, WP_Error;

class TermSelectAjaxHandler extends \BetterFrameworkPackage\Component\Standard\Control\AjaxHandlerBase {

	/**
	 * Handle the control ajax request.
	 *
	 * @throws Exception
	 * @since 1.0.0
	 * @return WP_HTTP_Response
	 */
	public function handle_request( array $params ): WP_HTTP_Response {

		$filtered_params = $this->validate( $params );

		if ( is_wp_error( $filtered_params ) ) {

			throw new \BetterFrameworkPackage\Core\Module\Exception( $filtered_params->get_error_message(), $filtered_params->get_error_code() );
		}

		return $this->response(
			[
				'raw' => $this->list_taxonomies( $filtered_params['taxonomy'] ?? '' ),
			]
		);
	}


	/**
	 * @param array $params
	 *
	 * @since 1.0.0
	 * @return array|WP_Error array on success or WP_Error when an error occurs.
	 */
	protected function validate( array $params ) {

		if ( empty( $params['taxonomy'] ) || ! taxonomy_exists( $params['taxonomy'] ) ) {

			return new WP_Error( 'invalid-taxonomy', sprintf( 'Invalid taxonomy given:%s', $params['taxonomy'] ) );
		}

		return $params;
	}

	/**
	 * @param string $taxonomy
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function list_taxonomies( string $taxonomy ): string {

		ob_start();

		wp_list_categories(
			[
				'selected_terms' => '',
				'hide_empty'     => false,
				'title_li'       => false,
				'style'          => 'list',
				'taxonomy'       => $taxonomy,
				'input_name'     => 'bf-term-select',
				'walker'         => new \BetterFrameworkPackage\Component\Control\TermSelect\TermSelectWalker(),
			]
		);

		return ob_get_clean();
	}
}
