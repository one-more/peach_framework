<?php

class Tools {
	use trait_extension;
	private $path;

	public function __construct() {
		$this->path = ROOT_PATH.DS.'extensions'.DS.'tools.tar.gz';
		$this->register_autoload();
	}

	public function route() {
		$router = Application::get_class('ToolsRouter');
		$router->route();
	}
}