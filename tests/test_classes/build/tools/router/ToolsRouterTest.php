<?php

require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'router'.DS.'toolsrouter.php';

class SilentToolsRouter extends Tools\router\ToolsRouter {
    public function __destruct() {}
}

/**
 * Class ToolsRouterTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 */
class ToolsRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router Tools\router\ToolsRouter
     */
    private $router;

    public function setUp() {
        if(empty($this->router)) {
            $this->router = new SilentToolsRouter();
        }
    }

    /**
     * @covers Tools\router\ToolsRouter::__construct
     */
    public function test_construct() {
        $this->assertInternalType('object', new SilentToolsRouter());
    }

    /**
     * @covers Tools\router\ToolsRouter::index
     */
    public function test_index() {
        $this->router->index();
        $this->assertNull(error_get_last());
    }
}
