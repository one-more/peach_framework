<?php

namespace common\routers;

use common\models\PageModel;

abstract class TemplateRouter {

    public function navigate(PageModel $page, $params) {
        $method = new \ReflectionMethod($this, $page->name);
        $method->invokeArgs($this, $params);
    }
}