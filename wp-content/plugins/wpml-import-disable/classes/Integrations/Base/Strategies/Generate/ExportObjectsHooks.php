<?php

namespace WPML\Import\Integrations\Base\Strategies\Generate;

use WPML\LIB\WP\Hooks;
use WPML\Import\Integrations\Base\Fields;

abstract class ExportObjectsHooks implements \IWPML_Backend_Action, \IWPML_DIC_Action {
	use Fields;

	/**
	 * @var bool $needsCleanup
	 */
	protected $needsCleanup = false;

	public function add_hooks() {
		Hooks::onAction( 'shutdown' )->then( [ $this, 'cleanupFields' ] );
	}

	/**
	 * @return \wpdb $wpdb
	 */
	abstract protected function getWpdb();

	/**
	 * @return string
	 */
	abstract protected function getMetaTable();

	/**
	 * @param int    $objectId
	 * @param string $metaKey
	 * @param mixed  $metaValue
	 */
	abstract protected function setObjectMeta( $objectId, $metaKey, $metaValue );

	/**
	 * @param object $object
	 */
	abstract protected function isTranslatable( $object );

	/**
	 * @param  object $object
	 *
	 * @return string
	 */
	abstract protected function getObjectIdMetaKey( $object );

	/**
	 * @param  object $object
	 *
	 * @return int
	 */
	abstract protected function getObjectId( $object );

	/**
	 * @param  object $object
	 *
	 * @return object|null
	 */
	abstract protected function getElementLanguageDetails( $object );

	/**
	 * @param  string $identifier
	 *
	 * @return string
	 * @see https://developer.wordpress.org/reference/classes/wpdb/quote_identifier/
	 */
	protected function quoteIdentifier( $identifier ) {
		return '`' . str_replace( '`', '``', $identifier ) . '`';
	}

	/**
	 * @param object|null $object
	 */
	public function setMetaFields( $object ) {
		if ( ! $object ) {
			return;
		}

		if ( ! $this->isTranslatable( $object ) ) {
			return;
		}

		// phpcs:disable WordPress.WP.PreparedSQL.NotPrepared
		// phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
		$exportFields = $this->getImportFields();
		$objectId     = $this->getObjectId( $object );
		$wpdb         = $this->getWpdb();
		$existingKeys = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT meta_key 
				FROM {$this->quoteIdentifier( $this->getMetaTable() )}
				WHERE {$this->quoteIdentifier( $this->getObjectIdMetaKey( $object ) )} = %d 
				AND meta_key IN (" . wpml_prepare_in( $exportFields ) . ") 
				LIMIT %d",
				$objectId,
				count( $exportFields )
			)
		);
		$missingKeys  = array_diff(
			$exportFields,
			$existingKeys
		);
		// phpcs:enable WordPress.WP.PreparedSQL.NotPrepared
		// phpcs:enable Squiz.Strings.DoubleQuoteUsage.NotRequired

		if ( empty( $missingKeys ) ) {
			$this->needsCleanup = true;
			return;
		}

		$element = $this->getElementLanguageDetails( $object );
		if ( ! $element ) {
			return;
		}

		array_walk(
			$missingKeys,
			function( $key, $index, $args ) {
				$value    = $this->getFieldValue( $key, $args['element'] );
				$objectId = $args['objectId'];
				$this->setObjectMeta( $objectId, $key, $value );
			},
			[
				'element'  => $element,
				'objectId' => $objectId,
			]
		);

		$this->needsCleanup = true;
	}

	public function cleanupFields() {
		if ( ! $this->needsCleanup ) {
			return;
		}

		$exportFields = $this->getImportFields();
		$wpdb         = $this->getWpdb();
		// phpcs:disable WordPress.WP.PreparedSQL.NotPrepared
		// phpcs:disable Squiz.Strings.DoubleQuoteUsage.NotRequired
		$wpdb->query(
			"DELETE FROM {$this->quoteIdentifier( $this->getMetaTable() )} WHERE meta_key IN (" . wpml_prepare_in( $exportFields ) . ")"
		);
		// phpcs:enable Squiz.Strings.DoubleQuoteUsage.NotRequired
		// phpcs:enable WordPress.WP.PreparedSQL.NotPrepared
	}

}
