<?php

require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'routers'.DS.'toolsrouter.php';

use Tools\routers\ToolsRouter;

class SilentToolsRouter extends ToolsRouter {

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

    /**
     * @covers Tools\routers\ToolsRouter::show_result
     */
    public function test_show_result() {
        $method = new ReflectionMethod($this->router, 'show_result');
        $method->setAccessible(true);
        ob_start();
        $method->invoke($this->router);
        ob_end_clean();
        self::assertNull(error_get_last());
    }
}
