<?php

class Starter implements Template {
    use trait_template;

    public function __construct() {
        $this->register_autoload();
    }

	public function route() {
		$controller = Application::get_class('RouteController');
		$controller->route();
	}
}