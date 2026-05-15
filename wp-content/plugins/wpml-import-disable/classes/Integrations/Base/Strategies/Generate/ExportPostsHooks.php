<?php

namespace WPML\Import\Integrations\Base\Strategies\Generate;

use WPML\Import\Helper\PostTypes;

class ExportPostsHooks extends ExportObjectsHooks {

	/**
	 * @var \wpdb $wpdb
	 */
	protected $wpdb;

	/**
	 * @var \SitePress $sitepress
	 */
	protected $sitepress;

	/**
	 * @var PostTypes
	 */
	protected $postTypes;

	/**
	 * @param \wpdb      $wpdb
	 * @param \SitePress $sitepress
	 * @param PostTypes  $postTypes
	 */
	public function __construct(
		\wpdb $wpdb,
		\SitePress $sitepress,
		PostTypes $postTypes
	) {
		$this->wpdb      = $wpdb;
		$this->sitepress = $sitepress;
		$this->postTypes = $postTypes;
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
		return $this->wpdb->postmeta;
	}

	/**
	 * @param int    $objectId
	 * @param string $metaKey
	 * @param mixed  $metaValue
	 */
	protected function setObjectMeta( $objectId, $metaKey, $metaValue ) {
		add_post_meta( $objectId, $metaKey, $metaValue, true );
	}

	/**
	 * @param  object $object
	 *
	 * @return bool
	 */
	protected function isPostObject( $object ) {
		return $object instanceof \WP_Post;
	}

	/**
	 * @param  object $object
	 *
	 * @return bool
	 */
	protected function isTranslatable( $object ) {
		if ( $this->isPostObject( $object ) ) {
			return $this->postTypes->isTranslatable( $object->post_type );
		}
		return false;
	}

	/**
	 * @param  object $object
	 *
	 * @return string
	 */
	protected function getObjectIdMetaKey( $object ) {
		if ( $this->isPostObject( $object ) ) {
			return 'post_id';
		}
		return '';
	}

	/**
	 * @param  object $object
	 *
	 * @return int
	 */
	protected function getObjectId( $object ) {
		if ( $this->isPostObject( $object ) ) {
			return $object->ID;
		}
		return 0;
	}

	/**
	 * @param  object $object
	 *
	 * @return object|null
	 */
	protected function getElementLanguageDetails( $object ) {
		if ( $this->isPostObject( $object ) ) {
			return $this->sitepress->get_element_language_details( $object->ID, 'post_' . $object->post_type );
		}
		return null;
	}
}
