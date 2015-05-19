<?php

require_once '../../initialize.php';

class SystemExtensionTest extends PHPUnit_Framework_TestCase {

	private $system_obj;
	private $configuration;

	public function __construct() {
		parent::__construct();
		$this->system_obj = Application::get_class('System');
		$file = ROOT_PATH.DS.'resource'.DS.'configuration.json';
		$this->configuration = json_decode(file_get_contents($file), true);
	}

	public function test_initialize() {
		$_COOKIE['pfm_session_id'] = 1;
		$this->assertNull($this->system_obj->initialize());
	}

	public function test_get_configuration() {
		$this->assertEquals($this->system_obj->get_configuration(), $this->configuration);
	}

	public function test_get_template() {
		$this->assertEquals($this->configuration['template'], $this->system_obj->get_template());
	}

	public function test_use_db() {
		$this->assertEquals($this->configuration['use_db'], $this->system_obj->use_db());
	}

	public function test_init_db() {
		$reflection = new ReflectionClass('System');
		$method = $reflection->getMethod('init_db');
		$method->setAccessible(true);
		$this->assertNull($method->invoke($this->system_obj));
	}
}