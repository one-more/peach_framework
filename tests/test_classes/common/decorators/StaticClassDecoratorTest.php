<?php

use common\classes\Application;
use common\decorators\StaticClassDecorator;

class StaticClassDecoratorTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $obj Application
     */
    private $obj;

    public function setUp() {
        $this->obj = new StaticClassDecorator(Application::class);
    }

    /**
     * @covers common\decorators\StaticClassDecorator::__construct
     */
    public function test_construct() {
        new StaticClassDecorator(Application::class);
    }

    /**
     * @covers common\decorators\StaticClassDecorator::__call
     * @expectedException \common\exceptions\NotExistedMethodException
     */
    public function test_call() {
        $this->obj->is_dev();

        $this->obj->not_existed_method();
    }
}
