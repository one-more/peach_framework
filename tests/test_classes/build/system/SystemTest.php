<?php
require_once ROOT_PATH.DS."build".DS.'system'.DS.'system.php';

class SystemTest extends PHPUnit_Framework_TestCase {
	use trait_configuration;

	private $system_obj;
	private $configuration;
	private $session_id = 1;

	public function __construct() {
		parent::__construct();
		$this->system_obj = Application::get_class('System');
		$file = ROOT_PATH.DS.'resource'.DS.'configuration.json';
		$this->configuration = json_decode(file_get_contents($file), true);
	}

	/**
	 * @covers System::initialize
	 */
	public function test_initialize() {
		$_COOKIE['pfm_session_id'] = $this->session_id;
		$this->assertNull($this->system_obj->initialize());
	}

	/**
	 * @covers System::get_configuration
	 */
	public function test_get_configuration() {
		$this->assertEquals($this->system_obj->get_configuration(), $this->configuration);
	}

	/**
	 * @covers System::get_template
	 */
	public function test_get_template() {
		$this->assertEquals($this->configuration['template'], $this->system_obj->get_template());
	}

	/**
	 * @covers System::use_db
	 */
	public function test_use_db() {
		$use_db_param = $this->system_obj->use_db();

		$this->set_params(['use_db' => true], 'configuration');
		$this->assertTrue($this->system_obj->use_db());

		$this->set_params(['use_db' => false], 'configuration');
		$this->assertFalse($this->system_obj->use_db());

		$this->set_params(['use_db' => $use_db_param], 'configuration');
		$this->assertEquals($this->configuration['use_db'], $this->system_obj->use_db());
	}

	/**
	 * @covers System::init_db
	 */
	public function test_init_db() {
		$reflection = new ReflectionClass('System');
		$method = $reflection->getMethod('init_db');
		$method->setAccessible(true);
		$this->assertNull($method->invoke($this->system_obj));
	}
}