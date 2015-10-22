<?php

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

    /**
     * @covers Tools::get_templates_mapper
     */
    public function test_get_templates_mapper() {
        self::assertTrue($this->tools_obj->get_templates_mapper() instanceof \Tools\mappers\TemplatesMapper);
    }
}
