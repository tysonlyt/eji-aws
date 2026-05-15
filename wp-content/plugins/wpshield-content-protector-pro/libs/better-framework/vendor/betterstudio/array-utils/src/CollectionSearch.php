<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

class CollectionSearch {

	/**
	 * Store the exact search rule.
	 *
	 * @var array
	 */
	protected $exact_rule;


	/**
	 * Store the equals search rule.
	 *
	 * @var array
	 */
	protected $equals_rule;


	/**
	 * Store the contains search rule.
	 *
	 * @var array
	 */
	protected $contains_rule;

	/**
	 * Store the collection instance.
	 *
	 * @var CollectionBase
	 * @since 1.0.0
	 */
	protected $collection;


	public function __construct( \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase $collection = null ) {

		$collection && $this->collection( $collection );
	}

	/**
	 * Collection getter/setter.
	 *
	 * @param CollectionBase|null $collection
	 *
	 * @since 1.0.0
	 * @return CollectionBase|null
	 */
	public function collection( \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase $collection = null ): ?\BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase {

		if ( isset( $collection ) ) {

			$this->collection = $collection;
		}

		return $this->collection;
	}

	/**
	 * Set exact phrase to search.
	 *
	 * @param array $array
	 *
	 * @since 1.0.0
	 * @return self
	 */
	public function exact( array $array ): self {

		$this->exact_rule = $array;

		return $this;
	}

	/**
	 * Set equals phrase to search.
	 *
	 * @param array $array
	 *
	 * @since 1.0.0
	 * @return self
	 */
	public function equals( array $array ): self {

		$this->equals_rule = $array;

		return $this;
	}

	/**
	 * Set equals phrase to search.
	 *
	 * @param array $array
	 *
	 * @since 1.0.0
	 * @return self
	 */
	public function contains( array $array ): self {

		$this->contains_rule = $array;

		return $this;
	}

	/**
	 * Loop for the value.
	 *
	 * @since 1.0.0
	 * @return mixed null on failure.
	 */
	public function search(): array {

		$result = [];

		$array = $this->collection->to_array();
		$this->array_search_recursive(
			$array,
			$result
		);

		return $result;
	}

	/**
	 * @param array $array
	 * @param array $_result
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function array_search_recursive( array &$array, &$_result = [] ): array {

		foreach ( $array as $key => $value ) {

			if ( is_array( $value ) ) {

				$this->array_search_recursive( $value, $_result );

			} else if ( $this->check_rule( $array ) ) {

				$_result[] = $array;

				break;
			}
		}

		return $_result;
	}

	/**
	 * @param array &$value
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function check_rule( array &$value ): bool {

		if ( isset( $this->exact_rule ) && $this->exact_rule === $value ) {

			return true;
		}

		if ( isset( $this->equals_rule ) && ! array_diff_assoc( $value, $this->equals_rule ) && ! array_diff_assoc( $this->equals_rule, $value ) ) {

			return true;
		}

		if ( isset( $this->contains_rule ) && ! array_diff_assoc( $this->contains_rule, $value ) ) {

			return true;
		}

		return false;
	}
}
