<?php

namespace Starter\routers;

use common\classes\Request;

class StarterRouter {

    public static function is_admin_panel() {
        return strpos(Request::uri(), 'admin_panel') !== false;
    }
}