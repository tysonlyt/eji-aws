<?php

namespace BetterFrameworkPackage\Framework\Core\Injection;

use BetterFrameworkPackage\Framework\Core\Injection\Query\MultiDimensionalQuery;
use BetterFrameworkPackage\Framework\Core\Injection\Query\SimpleQuery;

/**
 * Class InjectSelector
 *
 * @since   4.0.0
 *
 * @package BetterStudio\Framework\Core\Injection
 */
class SelectorInject implements \BetterFrameworkPackage\Framework\Core\Injection\Injector {

	/**
	 * Store the instance of the DomParser Adapter
	 *
	 * @var DomParser
	 */
	protected $parser;

	/**
	 * InjectSelector constructor.
	 *
	 * @param DomParser $parser
	 *
	 * @since 4.0.0
	 */
	public function __construct( \BetterFrameworkPackage\Framework\Core\Injection\DomParser $parser ) {

		$this->parser = $parser;
	}

	/**
	 * Retrieve the array of inject action details.
	 *
	 * @param array $args
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function inject( array $args ): array {

		$queries = explode( "\n", $args['query_string'] );
		$queries = array_map( [ $this, 'standard_query' ], $queries );

		return $this->injection( $queries, $args );
	}

	/**
	 * Prepare html injection details.
	 *
	 * @param array $queries
	 * @param array $args
	 *
	 * @since 4.0.0
	 * @return array
	 */
	protected function injection( array $queries, array $args ): array {

		foreach ( $queries as $query ) {

			// ignore empty query!
			if ( empty( $query ) ) {

				continue;
			}

			$query_instance = ! isset( $query['selector'] ) ? new \BetterFrameworkPackage\Framework\Core\Injection\Query\MultiDimensionalQuery( $this ) : new \BetterFrameworkPackage\Framework\Core\Injection\Query\SimpleQuery( $this );

			/**
			 * When {$query} is multi dimensional!
			 * For example:
			 * $query = [['selector' => (string) ,'idx' => (int) ],['selector' => (string) ,'idx' => (int) ]]
			 */
			if ( $query_instance instanceof \BetterFrameworkPackage\Framework\Core\Injection\Query\MultiDimensionalQuery ) {

				$handler_output = $query_instance->handle( $query, $args );

				if ( ! isset( $handler_output['search'] ) ) {

					$_html[] = array_filter( array_merge( ...$handler_output ) );

				} else {

					$_html[] = array_filter( $handler_output );
				}

				continue;
			}

			/**
			 * When {$query} is simple.
			 * For example:
			 * $query = ['selector' => (string) ,'idx' => (int) ]
			 */
			$_html[] = $query_instance->handle( $query, $args );
		}

		return $_html ?? [];
	}

	/**
	 * Standardize custom query selector to valid css selector!
	 *
	 * @param string $query
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function standard_query( string $query ): array {

		// Detect element number
		$preg_math = preg_match_all( '/\[(\w+)]/', $query, $m );

		if ( ! $preg_math ) {

			return [
				'selector' => $query,
				// Default is first element!
				'idx'      => 0,
			];
		}

		$dimension = count( $m[0] );
		$selectors = [];

		$subject = $query;
		// Extract all selectors.
		$query_details = explode( ' ', $query );

		for ( $i = 0; $i < $dimension; $i ++ ) {

			if ( ! isset( $m[1][ $i ], $m[0][ $i ] ) ) {

				continue;
			}

			if ( count( $query_details ) > 1 ) {

				$subject = $query_details[ $i ];
			}

			$bracket_position = strpos( $subject, '[' );

			// to support query selector with item number as label "first" or "last"
			if ( in_array( $m[1][ $i ], [ 'first', 'last' ], true ) ) {

				$idx = false === $bracket_position || 'first' === $m[1][ $i ] ? 0 : - 1;

				$selectors[] = [
					'idx'      => $idx,
					'selector' => str_replace( $m[0][ $i ], '', $subject ),
				];

				continue;
			}

			$idx = false === $bracket_position || 'first' === $m[1][ $i ] ? 0 : - 1;

			$selectors[] = [
				'idx'      => ! $idx ? $idx : ( (int) $m[1][ $i ] ) - 1,
				'selector' => str_replace( $m[0], '', $subject ),
			];
		}

		if ( 1 === $dimension && isset( $query_details[ $dimension ] ) ) {

			if ( in_array( $m[1][0], [ 'first', 'last' ], true ) ) {

				$selectors[] = [
					'idx'      => 'first' === $m[1][0] ? 0 : - 1,
					'selector' => str_replace( $m[0][0], '', $query_details[ $dimension ] ),
				];

			} else {

				$selectors[] = [
					'idx'      => ( (int) $m[1][0] ) - 1,
					'selector' => str_replace( $m[0], '', $query_details[ $dimension ] ),
				];
			}
		}

		return ! empty( $selectors ) ? $selectors : [
			'selector' => $query,
			'idx'      => 0,
		];
	}

	/**
	 * Retrieve the instance of DomParser class.
	 *
	 * @since 4.0.0
	 * @return DomParser
	 */
	public function get_parser(): \BetterFrameworkPackage\Framework\Core\Injection\DomParser {

		return $this->parser;
	}

	/**
	 * Setup parser with instance of DomParser class.
	 *
	 * @param DomParser $parser
	 *
	 * @since 4.0.0
	 * @return void
	 */
	public function set_parser( \BetterFrameworkPackage\Framework\Core\Injection\DomParser $parser ): void {

		$this->parser = $parser;
	}
}
