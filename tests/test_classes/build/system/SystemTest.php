<?php
require_once ROOT_PATH.DS.'build'.DS.'system'.DS.'system.php';

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
	 * @covers System::initialize
	 */
	public function test_initialize() {
		$this->system_obj->initialize();
	}

	/**
	 * @covers System::get_configuration
	 */
	public function test_get_configuration() {
		self::assertEquals($this->system_obj->get_configuration(), $this->configuration);
	}

	/**
	 * @covers System::get_template
	 */
	public function test_get_template() {
		self::assertEquals($this->configuration['template'], $this->system_obj->get_template());
	}

	/**
	 * @covers System::get_use_db_param
	 */
	public function test_use_db_param() {
		$use_db_param = $this->system_obj->get_use_db_param();

		$this->set_params(['use_db' => true], 'configuration');
		self::assertTrue($this->system_obj->get_use_db_param());

		$this->set_params(['use_db' => false], 'configuration');
		self::assertFalse($this->system_obj->get_use_db_param());

		$this->set_params(['use_db' => $use_db_param], 'configuration');
		self::assertEquals($this->configuration['use_db'], $this->system_obj->get_use_db_param());
	}

    /**
     * @covers System::set_use_db_param
     */
	public function test_set_use_db_param() {
        $old_value = $this->system_obj->get_use_db_param();
        $this->system_obj->set_use_db_param(!$old_value);
        self::assertEquals(!$old_value, $this->system_obj->get_use_db_param());
        $this->system_obj->set_use_db_param($old_value);
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