<?php

namespace Starter\routers;
use common\classes\GetResponse;
use common\routers\TemplateRouter;
use Starter\routers\traits\TraitStarterRouter;

/**
 * Class SiteRouter
 * @package Starter\routers
 * @decorate \common\decorators\AnnotationsDecorator
 */
class SiteRouter extends TemplateRouter {
	use TraitStarterRouter;

	public function __construct() {
        $this->response = new GetResponse();
	}

	public function index() {}
}
