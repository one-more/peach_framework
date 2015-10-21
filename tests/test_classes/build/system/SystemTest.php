<?php

class SystemTest extends PHPUnit_Framework_TestCase {
	use \common\traits\TraitConfiguration;

    /**
     * @var $system_obj System
     */
	private $system_obj;
	private $configuration;

    public function setUp() {
		$this->system_obj = \common\classes\Application::get_class(System::class);
		$this->configuration = $this->get_params('configuration');
    }

    /**
     * @covers System::__construct
     */
    public function test_construct() {
        new System();
    }

	/**
	 * @covers System::initialize
	 */
	public function test_initialize() {
		$this->system_obj->initialize();
	}

    /**
     * @covers System::set_template
     */
    public function test_set_template() {
        $method = new ReflectionMethod(System::class, 'set_template');
        $method->setAccessible(true);
        $method->invoke($this->system_obj);
        self::assertTrue($this->system_obj->template instanceof \common\interfaces\Template);
    }

	/**
	 * @covers System::init_db
	 */
	public function test_init_db() {
		$reflection = new ReflectionClass(System::class);
		$method = $reflection->getMethod('init_db');
		$method->setAccessible(true);
		self::assertNull($method->invoke($this->system_obj));
	}
}