<?php

namespace BetterFrameworkPackage\Framework\Core\Injection\Query;

/**
 * Class MultiDimensionalQuery
 *
 * @since   4.0.0
 *
 * @package BetterStudio\Framework\Core\Injection\Query
 */
class MultiDimensionalQuery extends \BetterFrameworkPackage\Framework\Core\Injection\Query\BaseQuery {

	/**
	 * Handle multi-dimensional query of selector!
	 *
	 * @param array $query
	 * @param array $args
	 *
	 * @return array|null
	 */
	public function handle( array $query, array $args ): ?array {

		// in this paradigm the query is multi-dimensional but have one item!
		if ( count( $query ) > 1 ) {

			$selectors = array_column( $query, 'selector' );
			$ids       = array_column( $query, 'idx' );

			if ( empty( $selectors ) || empty( $ids ) ) {

				return null;
			}

			// Combine selectors with idx params to create clone of query!
			$_query = array_combine( $selectors, $ids );

			$parent     = current( $selectors );
			$parent_idx = current( $ids );

			$this->update_idx( $parent, $parent_idx );

			$parent_element = $this->injector->get_parser()->find( $parent, $parent_idx );

			if ( ! $parent_element ) {

				return null;
			}

			// Remove parent and hold just children!
			array_shift( $_query );

			foreach ( $_query as $name => $child ) {

				if ( 1 === count( $_query ) ) {

					$child_element = $this->get_child( $parent_element, $name, $child );

					$_html = $this->get_html_replacement_params( $child_element, $args );

					continue;
				}

				// phpcs:ignore -- use polyfill-php73 if array_key_last not exists
				if ( isset( $child_element ) && $child_element && array_key_last( $_query ) === $name ) {

					$child_element = $this->get_child( $child_element, $name, $child );

					$_html[] = $this->get_html_replacement_params( $child_element, $args );

					continue;
				}

				$child_element = $this->get_child( $parent_element, $name, $child );
			}

			return $_html ?? [];
		}

		// query has simple items!
		foreach ( $query as $info ) {

			$_html[] = ( new \BetterFrameworkPackage\Framework\Core\Injection\Query\SimpleQuery( $this->injector ) )->handle( $info, $args );
		}

		return $_html ?? [];
	}
}
