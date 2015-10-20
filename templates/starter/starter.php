<?php

use common\classes\Request;
use common\classes\Application;
use Starter\routers\AdminPanelRouter;
use Starter\routers\SiteRouter;
use Starter\routers\ActionRouter;
use Starter\routers\RestRouter;

class Starter implements \common\interfaces\Template {
    use \common\traits\TraitTemplate;

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
                self::$current_router = Starter\routers\AdminPanelRouter::class;
			    $router = Application::get_class(AdminPanelRouter::class);
                break;
            case 'rest':
                self::$current_router = Starter\routers\RestRouter::class;
			    $router = Application::get_class(RestRouter::class);
                break;
            case 'action':
                self::$current_router = Starter\routers\ActionRouter::class;
			    $router = Application::get_class(ActionRouter::class);
                break;
            default:
                $router = Application::get_class(SiteRouter::class);
                self::$current_router = Starter\routers\SiteRouter::class;
        }
        $router->route();
	}
}