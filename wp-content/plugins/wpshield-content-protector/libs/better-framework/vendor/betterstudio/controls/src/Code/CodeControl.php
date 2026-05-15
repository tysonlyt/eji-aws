<?php

namespace BetterFrameworkPackage\Component\Control\Code;

use BetterFrameworkPackage\Component\Control as LibRoot;

class CodeControl extends \BetterFrameworkPackage\Component\Control\BaseDataControl {

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function control_type(): string {

		return 'code';
	}

	/**
	 * @since 1.0.0
	 * @return string
	 */
	public function template_dir(): string {

		return __DIR__ . '/templates';
	}

	/**
	 * @return string
	 */
	protected function language_attr( string $language ): string {

		switch ( $language ) {

			case 'javascript':
			case 'json':
			case 'js':
				$lang = 'text/javascript';
				break;

			case 'php':
				$lang = 'application/x-httpd-php';
				break;

			case 'css':
				$lang = 'text/css';
				break;

			case 'sql':
				$lang = 'text/x-sql';
				break;

			case 'xml':
			case 'html':
			default:
				$lang = 'text/html';
				break;
		}

		return $lang;
	}

	public function data_type(): string {

		return 'string';
	}
}
