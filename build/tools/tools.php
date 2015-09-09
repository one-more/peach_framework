<?php

class Tools {
	use trait_extension;

	public function __construct() {
		$this->register_autoload();
	}

	public function route() {
        /**
         * @var $router ToolsRouter
         */
		$router = Application::get_class('ToolsRouter');
        $router->route();
	}

	public function check_node_processes() {
        /**
         * @var $controller NodeProcessesController
         */
		$controller = Application::get_class('NodeProcessesController');
		$controller->check_processes();
	}
}