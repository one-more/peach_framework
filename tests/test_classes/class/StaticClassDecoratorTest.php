<?php

class StaticClassDecoratorTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $obj StaticClassDecorator
     */
    private $obj;

    public function setUp() {
        if(empty($this->obj)) {
            $this->obj = new StaticClassDecorator('Application');
        }
    }

    /**
     * @covers StaticClassDecorator::__construct
     */
    public function test_construct() {
        new StaticClassDecorator('Application');
    }

    /**
     * @covers StaticClassDecorator::__call
     * @expectedException NotExistedMethodException
     */
    public function test_call() {
        $this->obj->is_dev();

        $this->obj->not_existed_method();
    }
}
