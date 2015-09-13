<?php

class Tools {
	use TraitExtension;

	public function __construct() {
		$this->register_autoload();
	}

	public function route() {
        /**
         * @var $router \Tools\router\ToolsRouter
         */
		$router = Application::get_class('\Tools\router\ToolsRouter');
        $router->route();
	}
}