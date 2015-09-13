<?php

require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'tools.php';

/**
 * Class ToolsTest
 *
 * @method bool assertInternalType($a, $b)
 * @method bool assertEquals($a, $b)
 * @method bool assertNotEmpty($a)
 * @method bool assertNull($a)
 * @method bool assertFalse($a)
 * @method bool assertTrue($a)
 */
class ToolsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $tools_obj Tools
     */
    private $tools_obj;

    public function setUp() {
        if(empty($this->tools_obj)) {
            $this->tools_obj = Application::get_class('Tools');
        }
    }

    /**
     * @covers Tools::__construct
     */
    public function test_construct() {
        $this->assertInternalType('object', new Tools());
    }
}
