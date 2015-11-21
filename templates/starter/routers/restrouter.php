<?php

namespace Starter\routers;

use common\classes\Request;
use common\models\PageModel;
use common\routers\TemplateRouter;

class RestRouter extends TemplateRouter {

    public function navigate(PageModel $page, $params) {
        if(strpos(Request::uri(), 'admin_panel') !== false) {
            (new AdminPanelRestRouter())->navigate($page, $params);
        } else {
            (new SiteRestRouter())->navigate($page, $params);
        }
    }
}