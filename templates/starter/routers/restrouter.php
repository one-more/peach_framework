<?php

namespace Starter\routers;

use common\models\PageModel;
use common\routers\TemplateRouter;

class RestRouter extends TemplateRouter {

    public function navigate(PageModel $page, $params) {
        if(StarterRouter::is_admin_panel()) {
            (new \Starter\routers\AdminPanel\RestRouter())->navigate($page, $params);
        } else {
            (new \Starter\routers\site\RestRouter())->navigate($page, $params);
        }
    }
}