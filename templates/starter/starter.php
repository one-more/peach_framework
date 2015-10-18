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
        switch(array_replace_recursive([''], Request::uri_parts())[0]) {
            case 'admin_panel':
                self::$current_router = Starter\router\AdminPanelRouter::class;
			    $router = Application::get_class('Starter\router\AdminPanelRouter');
                break;
            case 'rest':
                self::$current_router = Starter\router\RestRouter::class;
			    $router = Application::get_class('Starter\router\RestRouter');
                break;
            case 'action':
                self::$current_router = Starter\router\ActionRouter::class;
			    $router = Application::get_class('Starter\router\ActionRouter');
                break;
            default:
                $router = Application::get_class('Starter\router\SiteRouter');
                self::$current_router = Starter\router\SiteRouter::class;
        }
        $router->route();
	}
}