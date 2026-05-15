<?php

namespace BetterFrameworkPackage\Core\Queue;

interface QueueAble {

	/**
	 * Register new queue.
	 *
	 * @param string $queue_id
	 * @param mixed  $data
	 *
	 * @since 1.0.0
	 * @return bool true on success
	 */
	public static function queue( $queue_id, array $data ):bool;

	/**
	 * Get list of the all items in the queue.
	 *
	 * @param string $queue_id
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_stack( $queue_id ):array ;

	/**
	 * Push an item into queue list.
	 *
	 * @param string $queue_id
	 * @param array  $data
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public static function set_stack( $queue_id, array $data );

	/**
	 * Fire the queue.
	 *
	 * @param string $queue_id
	 *
	 * @since 1.0.0
	 */
	public static function run_queue( $queue_id = '' );
}
