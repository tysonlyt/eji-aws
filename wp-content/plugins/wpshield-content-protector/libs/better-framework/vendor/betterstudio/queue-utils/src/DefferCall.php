<?php

namespace BetterFrameworkPackage\Core\Queue;

/**
 * Utility class to defer a callback.
 *
 * @since     1.0.0
 *
 * @package   BetterStudio/Core/Queue
 * @author    BetterStudio <info@betterstudio.com>
 * @link      http://www.betterstudio.com
 *
 * @version   1.0.0
 *
 * TODO: Add support for priority
 */
class DefferCall implements \BetterFrameworkPackage\Core\Queue\QueueAble
{

	/**
	 * Store callbacks to fire in future.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private static $stack = [];

	/**
	 * Register new queue
	 *
	 * @param string $hook wp hook name.
	 * @param array $data {
	 *
	 * @type callable $callback .
	 * @type array $params .
	 * }
	 *
	 * @return bool true on success
	 * @since 1.0.0
	 */
	public static function queue($hook, array $data): bool
	{

		if ( !self::can_queue( $hook ) ) {
			return false;
		}

		/**
		 * @var callable $queue_callback
		 */
		$queue_callback = [ static::class, 'run_queue' ];

		if ( !has_action( $hook, $queue_callback ) ) {

			add_action( $hook, $queue_callback, 9, 99 );
		}

		self::set_stack( $hook, $data );

		return true;
	}

	/**
	 * Get list of the all items in the queue.
	 *
	 * @param string $queue_id
	 *
	 * @return array array on success or false on failure.
	 * @since 1.0.0
	 * @see   queue for more doc
	 */
	public static function get_stack($queue_id): array
	{

		if ( isset( self::$stack[ $queue_id ] ) ) {
			return self::$stack[ $queue_id ];
		}

		return [];
	}

	/**
	 * Push an item into queue list
	 *
	 * @param string $queue_id
	 * @param array $data
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function set_stack($queue_id, array $data)
	{

		self::$stack[ $queue_id ] [] = $data;
	}

	public static function can_queue($hook)
	{

		return !did_action( $hook );
	}

	/**
	 * @param string $queue_id
	 *
	 * @since 1.0.0
	 */
	public static function run_queue($queue_id = '')
	{

		if ( !$callbacks_info = self::get_stack( current_filter() ) ) {

			return;
		}

		foreach ( $callbacks_info as $callback_info ) {

			$cb = &$callback_info['callback'];
			$params = [];

			if ( isset( $callback_info['params'] ) ) {
				$params = &$callback_info['params'];
			}

			if ( !empty( $callback_info['merge_hook_params'] ) ) {

				$params = array_merge( $params, func_get_args() );
			}

			call_user_func_array( $cb, $params );
		}
	}
}