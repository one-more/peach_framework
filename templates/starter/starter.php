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

	public function start() {
        $this->register_autoload();
    }
}