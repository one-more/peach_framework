<?php

namespace Starter\routers;
use classes\GetResponse;
use classes\Router;

/**
 * Class SiteRouter
 * @package Starter\routers
 * @decorate AnnotationsDecorator
 */
class SiteRouter extends Router {
	use TraitStarterRouter;

	public function __construct() {
		$this->routes = [
			'/' => [$this, 'index', 'no check']
		];
        $this->response = new GetResponse();
	}

	public function index() {}
}
