<?php

namespace Starter\routers;

use common\classes\Request;
use common\models\PageModel;
use common\routers\TemplateRouter;

/**
 * Class ActionRouter
 * @package Starter\routers
 */
class ActionRouter extends TemplateRouter {

    public function navigate(PageModel $page, $params) {
        if(strpos(Request::uri(), 'admin_panel') !== false) {
            (new AdminPanelActionRouter())->navigate($page, $params);
        } else {
            (new SiteActionRouter())->navigate($page, $params);
        }
    }
}