<?php

namespace Starter\routers;
use common\classes\GetResponse;
use common\classes\Router;

/**
 * Class SiteRouter
 * @package Starter\routers
 * @decorate \common\decorators\AnnotationsDecorator
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
