<?php


namespace BetterFrameworkPackage\Framework\Core\Injection\Query;

/**
 * Class SimpleQuery
 *
 * @since   4.0.0
 *
 * @package BetterStudio\Framework\Core\Injection\Query
 */
class SimpleQuery extends \BetterFrameworkPackage\Framework\Core\Injection\Query\BaseQuery {

	/**
	 * @inheritDoc
	 * Handle simple query!
	 *
	 * @param array $query
	 * @param array $args
	 *
	 * @since 4.0.0
	 * @return array|null
	 */
	public function handle( array $query, array $args ): ?array {

		$this->update_idx( $query['selector'], $query['idx'] );

		$dom_element = $this->injector->get_parser()->find( $query['selector'], $query['idx'] );

		if ( ! $dom_element ) {

			return null;
		}

		return $this->get_html_replacement_params( $dom_element, $args );
	}
}
