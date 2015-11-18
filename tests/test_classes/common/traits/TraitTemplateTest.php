<?php

namespace test_classes\common\traits;


use common\classes\Application;
use Starter\routers\RestRouter;

class TraitTemplateTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $template \Starter
     */
    private $template;

    public function setUp() {
        $this->template = Application::get_class(\Starter::class);
    }

    /**
     * @covers common\traits\TraitTemplate::get_path
     */
    public function test_get_path() {
        self::assertEquals(ROOT_PATH.DS.'templates'.DS.'starter', $this->template->get_path());
    }

    /**
     * @covers common\traits\TraitTemplate::get_lang_path
     */
    public function test_get_lang_path() {
        $lang_path = ROOT_PATH.DS.'templates'.DS.'starter'.DS.'lang'.DS.CURRENT_LANG;
        self::assertEquals($lang_path, $this->template->get_lang_path());
    }

    /**
     * @covers common\traits\TraitTemplate::load_template_class
     */
    public function test_load_template_class() {
        self::assertTrue($this->template->load_template_class(RestRouter::class));
        self::assertFalse($this->template->load_template_class('RestRouter'));
    }

    /**
     * @covers common\traits\TraitTemplate::register_autoload
     */
    public function test_register_autoload() {
        $method = new \ReflectionMethod(\Starter::class, 'register_autoload');
        $method->setAccessible(true);
        $method->invoke($this->template);
    }
}
