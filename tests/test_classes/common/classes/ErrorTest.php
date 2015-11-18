<?php

namespace test_classes\common\classes;


use common\classes\Error;

class ErrorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers common\classes\Error::initialize
     */
    public function test_initialize() {
        Error::initialize();
    }

    /**
     * @covers common\classes\Error::log
     */
    public function test_log() {
        Error::log('log test');
    }
}
