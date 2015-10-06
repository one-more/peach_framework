<?php

/**
 * Class TemplatesMapperTest
 *
 * @method bool assertTrue($var)
 */
class TemplatesMapperTest extends PHPUnit_Framework_TestCase {
    /**
     * @var $mapper \Tools\mapper\TemplatesMapper
     */
    private $mapper;

    public function setUp() {
        $this->mapper = Application::get_class('\Tools\mapper\TemplatesMapper');
    }

    /**
     * @covers \Tools\mapper\TemplatesMapper::get_adapter
     */
    public function test_get_adapter() {
        $this->assertTrue($this->mapper->get_adapter() instanceof FileAdapter);
    }

    /**
     * @covers \Tools\mapper\TemplatesMapper::get_page
     */
    public function test_get_page() {
        $this->assertTrue($this->mapper->get_page()->count() > 0);
    }
}
