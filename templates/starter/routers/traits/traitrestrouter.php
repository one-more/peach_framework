<?php

namespace Starter\routers\traits;

use common\classes\AjaxResponse;
use common\classes\PageTitle;

trait TraitRestRouter {

    public function __construct() {

        $this->response = new AjaxResponse();
    }

    public function __destruct() {
        echo $this->response;
    }
}