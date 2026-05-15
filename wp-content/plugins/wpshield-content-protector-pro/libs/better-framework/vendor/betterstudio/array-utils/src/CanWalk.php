<?php

namespace BetterFrameworkPackage\Utils\ArrayUtil;

use BadMethodCallException;

trait CanWalk {

	/**
	 * @string 1.1.2
	 * @var callable
	 */
	protected $callback;

	/**
	 * Recursive walk through collection items.
	 *
	 * @string 1.1.2
	 * @return self
	 */
	public function walk_recursive( callable $callback ): self {

		if ( ! $this instanceof \BetterFrameworkPackage\Utils\ArrayUtil\CollectionBase ) {

			throw new BadMethodCallException( sprintf( '%s only works on collections.', __METHOD__ ) );
		}

		if ( $this->data ) {

			$this->callback = $callback;
			$this->go_walk( $this->data );
		}

		return $this;
	}

	/**
	 * @param array  $data
	 * @param string $current_path
	 *
	 * @string 1.1.2
	 * @internal
	 */
	protected function go_walk( array &$data, string $current_path = '' ): void {

		foreach ( $data as $key => &$item ) {

			$position = ltrim( "$current_path.$key", '.' );

			if ( \is_array( $item ) ) {

				$this->go_walk( $item, $position );

			} else {

				\call_user_func_array( $this->callback, [ &$item, $key, $position ] );
			}
		}
	}
}
