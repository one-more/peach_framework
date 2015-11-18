<?php

namespace test_classes\templates\starter\routers;

use common\classes\Application;
use common\classes\GetResponse;
use Starter\routers\AdminPanelRouter;
use Starter\routers\SiteRouter;
use Starter\routers\TraitStarterRouter;

class TraitStarterRouterObj {
    use TraitStarterRouter;

    public function __destruct() {}
}

class TraitStarterRouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $router TraitStarterRouterObj
     */
    private $router;

    public function setUp() {
        $this->router = new TraitStarterRouterObj();
    }

    /**
     * @covers Starter\routers\TraitStarterRouter::show_result
     */
    public function test_show_result() {
        $method = new \ReflectionMethod($this->router, 'show_result');
        $method->setAccessible(true);
        $response = new GetResponse();
        $response->blocks['left'] = '';
        $response->blocks['main'] = '';
        $response->blocks['header'] = '';
        ob_start();
        \Starter::$current_router = AdminPanelRouter::class;
        $method->invoke($this->router, $response);
        \Starter::$current_router = SiteRouter::class;
        $method->invoke($this->router, $response);
        ob_end_clean();
        self::assertNull(error_get_last());
    }
}
