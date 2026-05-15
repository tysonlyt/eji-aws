<?php

namespace BetterFrameworkPackage\Framework\Core\Integration;

// use asset loader APIs
use \BetterFrameworkPackage\Asset\{
	Enqueue
};

// use core APIs
use \BetterFrameworkPackage\Core\{
	Module
};

class EnqueueStyle implements \BetterFrameworkPackage\Asset\Enqueue\EnqueueInterface {

	use \BetterFrameworkPackage\Core\Module\Singleton;

	/**
	 * Register an item.
	 *
	 * Registers the item if no item of that name already exists.
	 *
	 * @param string           $handle Name of the item. Should be unique.
	 * @param string|bool      $src    Full URL of the item, or path of the item relative to the WordPress root
	 *                                 directory. If source is set to false, item is an alias of other items it depends
	 *                                 on.
	 * @param string|bool      $path   Absolute path to the file or false if it's not exists.
	 * @param string[]         $deps   Optional. An array of registered item handles this item depends on. Default
	 *                                 empty array.
	 * @param string|bool|null $ver    Optional. String specifying item version number, if it has one, which is added
	 *                                 to the URL as a query string for cache busting purposes. If version is set to
	 *                                 false, a version number is automatically added equal to current installed
	 *                                 WordPress version. If set to null, no version is added.
	 * @param mixed            $args   Optional. Custom property of the item. NOT the class property $args. Examples:
	 *                                 $media, $in_footer.
	 *
	 * @since 4.0.0
	 * @return bool Whether the item has been registered. True on success, false on failure.
	 */
	public function add( string $handle, $src, $path = false, $deps = [], $ver = false, $args = null ): bool {

		return bf_register_style( $handle, $src, $deps, $ver, $args );
	}

	/**
	 * Un-register an item or items.
	 *
	 * @param string[] $handles Item handle and argument (string) or item handles and arguments (array of strings).
	 *
	 * @since 4.0.0
	 * @return bool true on success.
	 */
	public function remove( array $handles ): bool {

		bf_deregister_style( $handles );

		return ! empty( $handles );
	}

	/**
	 * Queue an item or items.
	 *
	 * Decodes handles and arguments, then queues handles and stores
	 * arguments in the class property $args. For example in extending
	 * classes, $args is appended to the item url as a query string.
	 * Note $args is NOT the $args property of items in the $registered array.
	 *
	 * @param string[] $handles Item handle and argument (string) or item handles and arguments (array of strings).
	 *
	 * @since 4.0.0
	 * @return bool true on success.
	 */
	public function enqueue( array $handles ): bool {

		array_map( 'bf_enqueue_style', $handles );

		return ! empty( $handles );
	}

	/**
	 * Dequeue an item or items.
	 *
	 * Decodes handles and arguments, then dequeues handles
	 * and removes arguments from the class property $args.
	 *
	 * @param string[] $handles Item handle and argument (string) or item handles and arguments (array of strings).
	 *
	 * @since 4.0.0
	 * @return bool true on success.
	 */
	public function dequeue( array $handles ): bool {

		array_map( 'bf_dequeue_style', $handles );

		return ! empty( $handles );
	}
}
