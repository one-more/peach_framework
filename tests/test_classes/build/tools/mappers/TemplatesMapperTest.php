<?php

require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'mappers'.DS.'templatesmapper.php';

use \common\classes\Application;
use common\adapters\FileAdapter;
use Tools\mappers\TemplatesMapper; 

/**
 * Class TemplatesMapperTest
 */
class TemplatesMapperTest extends PHPUnit_Framework_TestCase {
    /**
     * @var $mapper TemplatesMapper
     */
    private $mapper;

    public function setUp() {
        Application::get_class(Tools::class);
        $this->mapper = Application::get_class(TemplatesMapper::class);
    }

    /**
     * @covers \Tools\mappers\TemplatesMapper::get_adapter
     */
    public function test_get_adapter() {
        self::assertTrue($this->mapper->get_adapter() instanceof FileAdapter);
    }

    /**
     * @covers \Tools\mappers\TemplatesMapper::get_page
     */
    public function test_get_page() {
        self::assertTrue($this->mapper->get_page()->count() > 0);
    }
}
