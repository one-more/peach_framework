<?php

require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'tools.php';

/**
 * Class ToolsTest
 *
 */
class ToolsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $tools_obj Tools
     */
    private $tools_obj;

    public function setUp() {
        $this->tools_obj = \common\classes\Application::get_class(Tools::class);
    }

    /**
     * @covers Tools::__construct
     */
    public function test_construct() {
        new Tools();
    }
}
