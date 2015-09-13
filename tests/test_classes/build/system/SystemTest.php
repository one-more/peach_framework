<?php
require_once ROOT_PATH.DS.'build'.DS.'system'.DS.'system.php';

/**
 * Class SystemTest
 *
 * @method bool assertInternalType($a, $b)
 * @method bool assertEquals($a, $b)
 * @method bool assertNotEmpty($a)
 * @method bool assertNull($a)
 * @method bool assertFalse($a)
 * @method bool assertTrue($a)
 */
class SystemTest extends PHPUnit_Framework_TestCase {
	use TraitConfiguration;

    /**
     * @var $system_obj System
     */
	private $system_obj;
	private $configuration;
	private $session_id = 1;

    public function setUp() {
        if(empty($this->system_obj)) {
            $this->system_obj = Application::get_class('System');
            $this->configuration = $this->get_params('configuration');
        }
    }

	/**
	 * @covers System::initialize
	 */
	public function test_initialize() {
		$_COOKIE['pfm_session_id'] = $this->session_id;
		$this->system_obj->initialize();
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
	 * @covers System::get_use_db_param
	 */
	public function test_use_db_param() {
		$use_db_param = $this->system_obj->get_use_db_param();

		$this->set_params(['use_db' => true], 'configuration');
		$this->assertTrue($this->system_obj->get_use_db_param());

		$this->set_params(['use_db' => false], 'configuration');
		$this->assertFalse($this->system_obj->get_use_db_param());

		$this->set_params(['use_db' => $use_db_param], 'configuration');
		$this->assertEquals($this->configuration['use_db'], $this->system_obj->get_use_db_param());
	}

    /**
     * @covers System::set_use_db_param
     */
	public function test_set_use_db_param() {
        $old_value = $this->system_obj->get_use_db_param();
        $this->system_obj->set_use_db_param(!$old_value);
        $this->assertEquals(!$old_value, $this->system_obj->get_use_db_param());
        $this->system_obj->set_use_db_param($old_value);
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