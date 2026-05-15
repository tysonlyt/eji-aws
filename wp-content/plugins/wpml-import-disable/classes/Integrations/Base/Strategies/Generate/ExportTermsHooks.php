<?php

namespace WPML\Import\Integrations\Base\Strategies\Generate;

use WPML\Import\Helper\Taxonomies;

class ExportTermsHooks extends ExportObjectsHooks {

	/**
	 * @var \wpdb $wpdb
	 */
	protected $wpdb;

	/**
	 * @var \SitePress $sitepress
	 */
	protected $sitepress;

	/**
	 * @var Taxonomies
	 */
	protected $taxonomies;

	/**
	 * @param \wpdb      $wpdb
	 * @param \SitePress $sitepress
	 * @param Taxonomies $taxonomies
	 */
	public function __construct(
		\wpdb $wpdb,
		\SitePress $sitepress,
		Taxonomies $taxonomies
	) {
		$this->wpdb       = $wpdb;
		$this->sitepress  = $sitepress;
		$this->taxonomies = $taxonomies;
	}

	/**
	 * @return \wpdb $wpdb
	 */
	protected function getWpdb() {
		return $this->wpdb;
	}

	/**
	 * @return string
	 */
	protected function getMetaTable() {
		return $this->wpdb->termmeta;
	}

	/**
	 * @param int    $objectId
	 * @param string $metaKey
	 * @param mixed  $metaValue
	 */
	protected function setObjectMeta( $objectId, $metaKey, $metaValue ) {
		add_term_meta( $objectId, $metaKey, $metaValue, true );
	}

	/**
	 * @param  object $object
	 *
	 * @return bool
	 */
	protected function isTermObject( $object ) {
		return $object instanceof \WP_Term;
	}

	/**
	 * @param  object $object
	 *
	 * @return bool
	 */
	protected function isTranslatable( $object ) {
		if ( $this->isTermObject( $object ) ) {
			return $this->taxonomies->isTranslatable( $object->taxonomy );
		}
		return false;
	}

	/**
	 * @param  object $object
	 *
	 * @return string
	 */
	protected function getObjectIdMetaKey( $object ) {
		if ( $this->isTermObject( $object ) ) {
			return 'term_id';
		}
		return '';
	}

	/**
	 * @param  object $object
	 *
	 * @return int
	 */
	protected function getObjectId( $object ) {
		if ( $this->isTermObject( $object ) ) {
			return $object->term_id;
		}
		return 0;
	}

	/**
	 * @param  object $object
	 *
	 * @return object|null
	 */
	protected function getElementLanguageDetails( $object ) {
		if ( $this->isTermObject( $object ) ) {
			return $this->sitepress->get_element_language_details( $object->term_id, 'tax_' . $object->taxonomy );
		}
		return null;
	}
}
