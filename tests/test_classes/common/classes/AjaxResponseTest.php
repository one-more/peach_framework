<?php

namespace test_classes\common\classes;


use common\classes\AjaxResponse;

class AjaxResponseTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $ajax_response AjaxResponse
     */
    private $ajax_response;

    public function setUp() {
        $this->ajax_response = new AjaxResponse();
    }

    /**
     * @covers common\classes\AjaxResponse::__toString
     */
    public function test_to_string() {
        self::assertTrue(is_string(strtolower($this->ajax_response)));
    }
}
