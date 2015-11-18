<?php

namespace test_classes\templates\starter\router;

use Starter\routers\SiteRouter;

class SilentSiteRouter extends SiteRouter {

    public function __destruct() {}
}

class SiteRouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $router SiteRouter
     */
    private $router;

    /**
     * @covers Starter\routers\SiteRouter::__construct
     */
    public function test_construct() {
        new SilentSiteRouter();
    }
}
