<?php

use \classes\Application;
use adapters\FileAdapter;

/**
 * Class TemplatesMapperTest
 *
 * @method bool assertTrue($var)
 */
class TemplatesMapperTest extends PHPUnit_Framework_TestCase {
    /**
     * @var $mapper \Tools\mappers\TemplatesMapper
     */
    private $mapper;

    public function setUp() {
        $this->mapper = Application::get_class('\Tools\mappers\TemplatesMapper');
    }

    /**
     * @covers \Tools\mappers\TemplatesMapper::get_adapter
     */
    public function test_get_adapter() {
        $this->assertTrue($this->mapper->get_adapter() instanceof FileAdapter);
    }

    /**
     * @covers \Tools\mappers\TemplatesMapper::get_page
     */
    public function test_get_page() {
        $this->assertTrue($this->mapper->get_page()->count() > 0);
    }
}
