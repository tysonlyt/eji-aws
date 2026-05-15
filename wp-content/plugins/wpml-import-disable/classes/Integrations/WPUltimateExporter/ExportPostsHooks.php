<?php

namespace WPML\Import\Integrations\WPUltimateExporter;

use WPML\LIB\WP\Hooks;
use function WPML\FP\spreadArgs;
use WPML\Import\Helper\PostTypes;
use WPML\Import\Integrations\Base\Strategies\Generate\QueriedObject;

class ExportPostsHooks extends \WPML\Import\Integrations\Base\Strategies\Generate\ExportPostsHooks {
	use QueriedObject;
	use MetaHeaders;

	public function add_hooks() {
		Hooks::onFilter( 'query' )->then( spreadArgs( [ $this, 'setPostMetaFields' ] ) );
		parent::add_hooks();
	}

	/**
	 * @param  string $query
	 *
	 * @return string
	 */
	public function setPostMetaFields( $query ) {
		if ( $this->isMetaQuery( $query ) ) {
			$this->setMetaHeaders();
			$this->setMetaFields( $this->getQueriedPost( $query ) );
		}
		return $query;
	}

	/**
	 * @return string
	 */
	protected function getQuerySignature() {
		// phpcs:disable WordPress.WP.PreparedSQL.NotPrepared
		// phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
		return $this->wpdb->prepare( "SELECT post_id,meta_key,meta_value FROM {$this->wpdb->prefix}posts wp JOIN {$this->wpdb->prefix}postmeta wpm ON wpm.post_id = wp.ID where meta_key NOT IN (%s,%s) AND ID=", '_edit_lock', '_edit_last' );
		// phpcs:enable WordPress.WP.PreparedSQL.NotPrepared
		// phpcs:enable Squiz.Strings.DoubleQuoteUsage.NotRequired
	}

}
