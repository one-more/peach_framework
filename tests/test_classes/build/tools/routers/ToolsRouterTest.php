<?php

require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'routers'.DS.'toolsrouter.php';

class SilentToolsRouter extends Tools\routers\ToolsRouter {

    public function __destruct() {}
}

/**
 * Class ToolsRouterTest
 *
 */
class ToolsRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router Tools\routers\ToolsRouter
     */
    private $router;

    public function setUp() {
        $this->router = new SilentToolsRouter();
    }

    /**
     * @covers Tools\routers\ToolsRouter::__construct
     */
    public function test_construct() {
        new SilentToolsRouter();
    }

    /**
     * @covers Tools\routers\ToolsRouter::index
     */
    public function test_index() {
        $this->router->index();
    }
}
