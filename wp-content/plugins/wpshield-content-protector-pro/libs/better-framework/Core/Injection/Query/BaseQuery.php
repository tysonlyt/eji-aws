<?php

namespace BetterFrameworkPackage\Framework\Core\Injection\Query;

use BetterFrameworkPackage\Framework\Core\Injection\Injector;
use voku\helper\{SimpleHtmlDomInterface, SimpleHtmlDomNodeInterface};

/**
 * Class BaseQuery
 *
 * @since   4.0.0
 *
 * @package BetterStudio\Framework\Core\Injection\Query
 */
abstract class BaseQuery {

	/**
	 * Store the injector instance.
	 *
	 * @var Injector
	 */
	protected $injector;

	/**
	 * BaseQuery constructor.
	 *
	 * @param Injector $injector
	 *
	 * @since 4.0.0
	 */
	public function __construct( \BetterFrameworkPackage\Framework\Core\Injection\Injector $injector ) {

		$this->injector = $injector;
	}

	/**
	 * Handle query selector.
	 *
	 * @param array $query
	 * @param array $args
	 *
	 * @return array|null
	 */
	abstract public function handle( array $query, array $args ): ?array;

	/**
	 * Update selector for element with idx value!
	 *
	 * @param string                                                                                                  $selector
	 * @param int|null                                                                                                $idx
	 * @param SimpleHtmlDomInterface|SimpleHtmlDomInterface[]|SimpleHtmlDomNodeInterface<SimpleHtmlDomInterface>|null $dom_element
	 *
	 * @since 4.0.0
	 * @return void
	 */
	protected function update_idx( string $selector, int &$idx = null, $dom_element = null ): void {

		if ( - 1 !== $idx ) {

			// break: because {$idx} of selector is set!
			return;
		}

		$dom = $this->injector->get_parser()->find( $selector );

		if ( ! $dom ) {

			// break: because selector not found!
			return;
		}

		if ( $dom_element ) {

			$_dom = $dom_element->find( $selector );

			if ( $_dom && 1 === $_dom->count() ) {

				$idx = 0;
			}
		}

		if ( ! $idx ) {

			// break: because this is last child!
			return;
		}

		// Update idx number to last child.
		$idx += $dom->count();
	}

	/**
	 * @param SimpleHtmlDomInterface|SimpleHtmlDomInterface[]|SimpleHtmlDomNodeInterface<SimpleHtmlDomInterface> $dom
	 * @param string                                                                                             $selector
	 * @param int|null                                                                                           $idx
	 *
	 * @since 4.0.0
	 * @return SimpleHtmlDomInterface|SimpleHtmlDomInterface[]|SimpleHtmlDomNodeInterface|false
	 */
	protected function get_child( $dom, string $selector, int $idx = null ) {

		$this->update_idx( $selector, $idx, $dom );

		$dom_element = $dom->find( $selector, $idx );

		try {

			if ( ! $dom_element->count() ) {

				// {$dom} doesn't has children!
				return false;
			}
		} catch ( \Exception $exception ) {

			if ( ! $dom_element->getNode() ) {

				// {$dom} doesn't has children!
				return false;
			}
		}

		// $dom has children! retrieve selector children.
		return $dom_element;
	}

	/**
	 * Get replacement data.
	 *
	 * @param SimpleHtmlDomInterface|SimpleHtmlDomInterface[]|SimpleHtmlDomNodeInterface<SimpleHtmlDomInterface> $dom_element
	 * @param array                                                                                              $args
	 *
	 * @since 4.0.0
	 * @return array{search: mixed, replace: string[]|string}
	 */
	protected function get_html_replacement_params( $dom_element, array $args ): array {

		$callback = $args['callback'] ?? 'outerHTML';

		$_html['search'] = $dom_element->{$callback}();

		if ( 'after' === $args['position'] ) {

			$_html['replace'] = str_replace(
				$dom_element->{$callback}(),
				$dom_element->{$callback}() . $args['content'],
				$dom_element->{$callback}()
			);

		} else {

			$_html['replace'] = str_replace(
				$dom_element->{$callback}(),
				$args['content'] . $dom_element->{$callback}(),
				$dom_element->{$callback}()
			);
		}

		return $_html;
	}
}
