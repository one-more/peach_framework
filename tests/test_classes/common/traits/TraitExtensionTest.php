<?php

namespace test_classes\common\traits;

use common\classes\Application;
use Session\models\SessionModel;

class TraitExtensionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $obj \Session
     */
    private $obj;

    public function setUp() {
        $this->obj = Application::get_class(\Session::class);
    }

    /**
     * @covers common\traits\TraitExtension::get_path
     */
    public function test_get_path() {
        self::assertEquals('pfmextension://session', $this->obj->get_path());
    }

    /**
     * @covers common\traits\TraitExtension::get_lang_path
     */
    public function test_get_lang_path() {
        self::assertEquals('pfmextension://session'.DS.'lang'.DS.CURRENT_LANG, $this->obj->get_lang_path());
    }

    /**
     * @covers common\traits\TraitExtension::load_extension_class
     */
    public function test_load_extension_class() {
        self::assertFalse($this->obj->load_extension_class('NotExistedExtension\BaseModel'));
        self::assertFalse($this->obj->load_extension_class('Session\models\BaseModel'));
        self::assertTrue($this->obj->load_extension_class(SessionModel::class));
    }

    /**
     * @covers common\traits\TraitExtension::register_autoload
     */
    public function test_register_autoload() {
        $method = new \ReflectionMethod(\Session::class, 'register_autoload');
        $method->setAccessible(true);
        $method->invoke($this->obj);
    }
}
