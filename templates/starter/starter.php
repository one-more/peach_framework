<?php

class Starter implements Template {
    use trait_template;

    public function __construct() {
        $this->register_autoload();
    }

	public function route() {
        /**
         * @var $router Router
         */
		if(strpos(Request::uri(), 'admin_panel') === false) {
			$router = Application::get_class('SiteRouter');
			$router->route();
		} else {
			$router = Application::get_class('AdminPanelRouter');
			$router->route();
		}
	}
}