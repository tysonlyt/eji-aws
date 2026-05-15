<?php

namespace WPML\Import\Helper;

class Language {

	/**
	 * @param string   $langCode
	 * @param callable $callable
	 *
	 * @return mixed
	 */
	public static function switchAndRun( $langCode, callable $callable ) {
		/** @var \SitePress $sitepress */
		global $sitepress;

		$tempSwitchLang = new \WPML_Temporary_Switch_Language( $sitepress, $langCode );
		$value          = $callable();
		$tempSwitchLang->restore_lang();

		return $value;
	}
}
