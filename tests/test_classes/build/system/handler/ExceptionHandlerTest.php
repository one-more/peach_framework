<?php

use System\handler\ExceptionHandler;

class ExceptionHandlerTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers System\handler\ExceptionHandler::initialize
     */
    public function test_initialize() {
        ExceptionHandler::initialize();
    }

    /**
     * @covers System\handler\ExceptionHandler::show_error
     */
    public function test_show_error() {
        ob_start();
        ExceptionHandler::show_error('an error occurred');
        ob_end_clean();
        self::assertNull(error_get_last());
    }

    /**
     * @covers ::\System\handler\peach_exception_handler
     */
    public function test_exception_handler() {
        ob_start();
        \System\handler\peach_exception_handler(new ErrorException('all went wrong'));
        ob_end_clean();
    }

    /**
     * @covers ::\System\handler\peach_error_handler
     *
     */
    public function test_error_handler() {
        ob_start();
        $not_existed_var;
        ob_end_clean();
    }
}
