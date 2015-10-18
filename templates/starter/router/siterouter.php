<?php

namespace Starter\router;

/**
 * Class SiteRouter
 * @package Starter\router
 * @decorate AnnotationsDecorator
 */
class SiteRouter extends \Router {
	use TraitStarterRouter;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check']
		];
        $this->response = new \GetResponse();
	}

	public function index() {}
}
