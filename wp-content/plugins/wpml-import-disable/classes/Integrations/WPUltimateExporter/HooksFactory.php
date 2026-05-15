<?php // phpcs:ignore

namespace WPML\Import\Integrations\WPUltimateExporter;

use function WPML\Container\make;
use WPML\FP\Obj;

class HooksFactory implements \IWPML_Backend_Action_Loader, \IWPML_CLI_Action_Loader {

	/**
	 * @return \IWPML_Action[]|null
	 */
	public function create() {
		$hooks = [];

		if ( self::isExporting() ) {
			$hooks[] = make( ExportPostsHooks::class );
			$hooks[] = make( ExportTermsHooks::class );
		}

		if ( self::isCounting() ) {
			$hooks[] = make( CountObjectsHooks::class );
		}

		return $hooks;
	}

	/**
	 * @param  string $action
	 *
	 * @return bool
	 */
	private function isAjaxAction( $action ) {
		if (
			defined( 'DOING_AJAX' )
			&& DOING_AJAX
			// phpcs:disable WordPress.PHP.YodaConditions
			&& $action === Obj::prop( 'action', $_POST )
			// phpcs:enable WordPress.PHP.YodaConditions
			&& null !== Obj::prop( 'module', $_POST )
		) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	private function isExporting() {
		return $this->isAjaxAction( 'parse_data' );
	}

	/**
	 * @return bool
	 */
	private function isCounting() {
		return $this->isAjaxAction( 'total_records' );
	}
}
