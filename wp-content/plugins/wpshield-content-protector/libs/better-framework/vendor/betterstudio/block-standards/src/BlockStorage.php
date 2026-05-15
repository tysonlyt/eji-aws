<?php

namespace BetterFrameworkPackage\Component\Standard\Block;

class BlockStorage {

	/**
	 * Store list of blocks instances or class names.
	 *
	 * @var BlockInterface[]
	 *
	 * @since 1.0.0
	 */
	protected static $blocks = [];

	/**
	 * Get the block instance.
	 *
	 * @param string $block_id the block ID.
	 *
	 * @since 1.0.0
	 * @return BlockInterface|BlockInterface[]|null object on success.
	 */
	public static function factory( string $block_id ) {

		$block = static::$blocks[ $block_id ] ?? null;

		return \is_string( $block ) ? new $block() : $block;
	}

	/**
	 * Is block registered.
	 *
	 * @param string $block_id
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public static function exists( string $block_id = '' ): bool {

		return ! empty( static::$blocks[ $block_id ] );
	}

	/**
	 * @param BlockInterface|string $block
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public static function register( string $block_id, $block ): bool {

		if ( ! isset( static::$blocks[ $block_id ] ) ) {

			static::$blocks[ $block_id ] = $block;

			return true;
		}

		return false;
	}

	/**
	 * Clear registered blocks.
	 *
	 * @since 1.0.0
	 */
	public static function flush(): void {

		static::$blocks = [];
	}

	/**
	 * List of registered blocks.
	 *
	 * @since 1.0.0
	 * @return BlockInterface[]
	 */
	public static function blocks(): array {

		return apply_filters( 'better-studio/blocks/list', static::$blocks );
	}
}
