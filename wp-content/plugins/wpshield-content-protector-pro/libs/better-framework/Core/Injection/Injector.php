<?php

namespace BetterFrameworkPackage\Framework\Core\Injection;

/**
 * Interface Injector
 *
 * @since   4.0.0
 *
 * @package BetterStudio\Framework\Core\Injection
 */
interface Injector {

	/**
	 * Inject content with use of arguments.
	 *
	 * @param array $args
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function inject( array $args ): array;

	/**
	 * Standardize custom query selector to valid css selector!
	 *
	 * @param string $query
	 *
	 * @since 4.0.0
	 * @return array
	 */
	public function standard_query( string $query ): array;
}
