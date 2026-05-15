<?php

namespace BetterFrameworkPackage\Framework\Core\Injection;

use voku\helper\{
	SimpleHtmlDom as HTMLDom,
	HtmlDomParser as ParserBase,
	SimpleHtmlDomNode as DomNode,
	SimpleHtmlDomBlank as HTMLDomBlank,
	SimpleHtmlDomNodeBlank as DomNodeBlank,
	SimpleHtmlDomInterface as HTMLDomInterface,
	SimpleHtmlDomNodeInterface as DomNodeInterface
};

/**
 * Class DomParser
 *
 * @since   4.0.0
 *
 * @package BetterStudio\Framework\Core\Adapters
 */
class DomParser extends ParserBase {

	/**
	 * @inheritDoc
	 *
	 * @param string   $selector
	 * @param int|null $idx
	 *
	 * @return false|HTMLDom|HtmlDomBlank|HtmlDomInterface|HtmlDomInterface[]|DomNode|DomNodeBlank|DomNodeInterface
	 */
	public function find( string $selector, $idx = null ) {

		$element = parent::find( $selector, $idx );

		try {

			if ( ! $element->count() ) {

				return false;
			}
		} catch ( \Exception $exception ) {

			if ( ! $element->getNode() ) {

				return false;
			}
		}

		return $element;
	}
}
