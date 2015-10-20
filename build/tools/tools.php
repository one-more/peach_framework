<?php

use Tools\routers\ToolsRouter;

class Tools implements \interfaces\Extension {
	use \traits\TraitExtension;

	public function __construct() {
		$this->register_autoload();
	}

	public function route() {
        /**
         * @var $router \Tools\routers\ToolsRouter
         */
		$router = \classes\Application::get_class(ToolsRouter::class);
        $router->route();
	}
}