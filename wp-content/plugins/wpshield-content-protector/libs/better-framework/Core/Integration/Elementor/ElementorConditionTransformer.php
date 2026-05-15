<?php

namespace BetterFrameworkPackage\Framework\Core\Integration\Elementor;

class ElementorConditionTransformer {

	/**
	 * @var array
	 * @since 4.0.0
	 */
	protected $show_on;

	/**
	 * @return array{relation: string, terms: array}
	 */
	public function transform( array $show_on ): array {

		$this->show_on = $show_on;

		$all_terms = [];

		foreach ( $show_on as $and_items ) {

			$terms = $this->transform_terms( (array) $and_items );

			$all_terms[] = [
				'relation' => 'and',
				'terms'    => $terms,
			];
		}

		return [
			'relation' => 'or',
			'terms'    => $all_terms,
		];
	}

	protected function transform_terms( array $items ): array {

		$results = [];

		foreach ( $items as $item ) {

			if ( ! preg_match( '/(.*?)(\!?\=)(.*)/', $item, $match ) ) {

				continue;
			}

			[ , $name, $operator, $value ] = $match;

			if ( $operator === '=' ) {

				$operator = '==';
			}

			$results[] = compact( 'name', 'operator', 'value' );
		}

		return $results;
	}
}
