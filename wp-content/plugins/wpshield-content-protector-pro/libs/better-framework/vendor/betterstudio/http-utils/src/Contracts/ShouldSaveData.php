<?php

namespace BetterFrameworkPackage\Utils\Http\Contracts;

use BetterFrameworkPackage\Utils\Http;

/**
 * Modules Implements this interface when they have to save data.
 *
 * @package BetterStudio\Utils\Http\Handlers
 */
interface ShouldSaveData {

	/**
	 * Name of the hook that the save action will happens.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function save_hook()
	: string;

	/**
	 * Handle the save process.
	 *
	 * @param Http\HttpRequest $request
	 * @param array            $params
	 *
	 * @since 1.0.0
	 * @return true true on success oro false otherwise.
	 */
	public function save_data( \BetterFrameworkPackage\Utils\Http\HttpRequest $request, ...$params )
	: bool;

	/**
	 * Dose user have a right access?
	 *
	 * @since 1.0.0
	 * @return bool True if user have a valid access.
	 */
	public function save_permission()
	: bool;
}
