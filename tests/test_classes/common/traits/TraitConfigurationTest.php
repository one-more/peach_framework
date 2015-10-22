<?php

namespace test_classes\common\traits;

use common\traits\TraitConfiguration;

class TraitConfigurationImpl {
    use TraitConfiguration {
        TraitConfiguration::get_base_path as trait_get_base_path;
        TraitConfiguration::get_params as trait_get_params;
        TraitConfiguration::set_params as trait_set_params;
        TraitConfiguration::unset_param as trait_unset_param;
        TraitConfiguration::save_params as trait_save_params;
    }

    public function get_base_path() {
        return $this->trait_get_base_path();
    }

    public function get_params($name = __CLASS__) {
        return $this->trait_get_params($name);
    }

    public function set_params(array $params, $name = __CLASS__) {
        $this->trait_set_params($params, $name);
    }

    public function unset_param($param, $name = __CLASS__) {
        $this->trait_unset_param($param, $name);
    }

    public function save_params(array $params, $name = __CLASS__) {
        $this->trait_save_params($params, $name);
    }
}

class TraitConfigurationTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $obj TraitConfigurationImpl
     */
    private $obj;

    public function setUp() {
        $this->obj = new TraitConfigurationImpl();
    }

    /**
     * @covers common\traits\TraitConfiguration::get_base_path
     */
    public function test_get_base_path() {
        self::assertEquals(ROOT_PATH, $this->obj->get_base_path());
    }

    /**
     * @covers common\traits\TraitConfiguration::get_params
     * @covers common\traits\TraitConfiguration::set_params
     * @covers common\traits\TraitConfiguration::save_params
     */
    public function test_get_params() {
        $file = $this->obj->get_base_path().DS.'resource'.DS.strtolower(get_class($this->obj)).'.json';
        if(file_exists($file)) {
            unlink($file);
        }
        self::assertCount(0, $this->obj->get_params());

        $this->obj->set_params(['field' => uniqid('', true)]);
        self::assertCount(1, $this->obj->get_params());
    }

    /**
     * @covers common\traits\TraitConfiguration::unset_param
     */
    public function test_unset_param() {
        $this->obj->unset_param('field');
        self::assertCount(0, $this->obj->get_params());
    }
}
