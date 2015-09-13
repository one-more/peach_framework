<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'controller'.DS.'templatescontroller.php';

/**
 * Class TemplatesControllerTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 * @method bool assertTrue($var)
 */
class TemplatesControllerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $controller \Tools\controller\TemplatesController
     */
    private $controller;

    public function setUp() {
        if(empty($this->controller)) {
            $this->controller = Application::get_class('\Tools\controller\TemplatesController');
        }
    }

    /**
     * @covers \Tools\controller\TemplatesController::__construct
     */
    public function test_construct() {
        $this->assertInternalType('object', new \Tools\controller\TemplatesController());
    }

    /**
     * @covers \Tools\controller\TemplatesController::get_templates_list
     */
    public function test_get_templates_list() {
        $this->assertInternalType('array', $this->controller->get_templates_list());
    }

    /**
     * @covers \Tools\controller\TemplatesController::get_template
     */
    public function test_get_template() {
        $this->assertTrue($this->controller->get_template(1) instanceof \Tools\record\TemplateRecord);

        $this->assertNull($this->controller->get_template(0));
    }
}
