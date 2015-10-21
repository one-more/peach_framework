<?php

namespace test_classes\common\classes;


use common\classes\Application;
use common\classes\Configuration;
use common\traits\TraitConfiguration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase {
    use TraitConfiguration;

    /**
     * @var $configuration Configuration
     */
    private $configuration;

    public function setUp() {
        $this->configuration = Application::get_class(Configuration::class);
    }

    /**
     * @covers common\classes\Configuration::__construct
     */
    public function test_construct() {
        new Configuration();
    }

    /**
     * @covers common\classes\Configuration::set_language
     */
    public function test_set_language() {
        $_COOKIE['language'] = 'EN';
        $this->configuration->set_language(CURRENT_LANG);

        unset($_COOKIE['language']);
        $this->configuration->set_language(CURRENT_LANG);
    }

    /**
     * @covers common\classes\Configuration::load
     */
    public function test_load() {
        $method = new \ReflectionMethod($this->configuration, 'load');
        $method->setAccessible(true);
        $method->invoke($this->configuration);
    }

    /**
     * @covers common\classes\Configuration::load_db_params
     */
    public function test_load_db_params() {
        $configuration = $this->get_params('configuration');
        $method = new \ReflectionMethod($this->configuration, 'load_db_params');
        $method->setAccessible(true);
        $method->invoke($this->configuration, $configuration);
    }

    /**
     * @covers common\classes\Configuration::load_language
     */
    public function test_load_language() {
        $configuration = $this->get_params('configuration');
        $method = new \ReflectionMethod($this->configuration, 'load_language');
        $method->setAccessible(true);
        $method->invoke($this->configuration, $configuration);
    }
}
