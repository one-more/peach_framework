<?php

class Starter implements Template {
    use TraitTemplate;

    /**
     * @var string $current_router
     */
    public static $current_router;

    public function __construct() {
        $this->register_autoload();
    }

	public function route() {
        /**
         * @var $router Router
         */
		if(strpos(Request::uri(), 'admin_panel') === false) {
			$router = Application::get_class('Starter\router\SiteRouter');
            self::$current_router = Starter\router\SiteRouter::class;
        } else {
            self::$current_router = Starter\router\AdminPanelRouter::class;
			$router = Application::get_class('Starter\router\AdminPanelRouter');
		}
        $router->route();
	}
}