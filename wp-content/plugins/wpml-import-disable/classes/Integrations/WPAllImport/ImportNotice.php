<?php

namespace WPML\Import\Integrations\WPAllImport;

use WPML\Import\Fields;
use WPML\FP\Lst;
use WPML\LIB\WP\Hooks;
use WPML\Import\Integrations\Base\Notice;
use function WPML\FP\spreadArgs;

class ImportNotice extends Notice {

	const NOTICE_ID = 'wp-all-import';

	/**
	 * @return string
	 */
	protected function getId() {
		return self::NOTICE_ID;
	}

	/**
	 * @return callable
	 */
	protected function getDisplayCallback() {
		return [ HooksFactory::class, 'isOnImportPage' ];
	}

	/**
	 * @return string
	 */
	protected function getMessage() {
		if ( HooksFactory::hasWooCommerceAddon() ) {
			return $this->getShopImportMessage();
		}

		return $this->getImportMessage();
	}

}
