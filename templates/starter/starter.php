<?php

class Starter implements Template {
    use TraitTemplate;

    public function __construct() {
        $this->register_autoload();
    }

	public function route() {
        /**
         * @var $router Router
         */
		if(strpos(Request::uri(), 'admin_panel') === false) {
			$router = Application::get_class('Starter\router\SiteRouter');
		} else {
			$router = Application::get_class('Starter\router\AdminPanelRouter');
		}
        $router->route();
	}
}