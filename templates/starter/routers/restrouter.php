<?php

namespace Starter\routers;

use common\classes\Application;
use common\models\PageModel;
use common\routers\TemplateRouter;

class RestRouter extends TemplateRouter {

    public function navigate(PageModel $page, $params) {
        if(StarterRouter::is_admin_panel()) {
            $router = Application::get_class(\Starter\routers\AdminPanel\RestRouter::class);
            $router->navigate($page, $params);
        } else {
            $router = Application::get_class(\Starter\routers\site\RestRouter::class);
            $router->navigate($page, $params);
        }
    }
}