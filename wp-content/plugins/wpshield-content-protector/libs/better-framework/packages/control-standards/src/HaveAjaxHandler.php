<?php

namespace BetterFrameworkPackage\Component\Standard\Control;

interface HaveAjaxHandler {

	/**
	 * @return HandleAjaxRequest
	 */
	public function ajax_handler(): \BetterFrameworkPackage\Component\Standard\Control\HandleAjaxRequest;
}
