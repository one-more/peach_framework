<?php
require_once ROOT_PATH.DS."build".DS.'session'.DS.'session.php';
require_once ROOT_PATH.DS."build".DS.'session'.DS.'model'.DS.'sessionmodel.php';

class SessionTest extends PHPUnit_Framework_TestCase {
	use trait_configuration;

	private $session_obj;
	private $session_id = 1;
	private $system_obj;
	private static $use_db_original;

	public static function setUpBeforeClass() {
		static::$use_db_original = Application::get_class('System')->use_db();
	}

	public function setUp() {
		$this->session_obj = Application::get_class('Session');
		$this->system_obj = Application::get_class('System');
		$_COOKIE['pfm_session_id'] = $this->session_id;
	}

	public function tearDown() {
		if($this->system_obj->use_db() !== static::$use_db_original) {
			$this->set_params(['use_db' => static::$use_db_original], 'configuration');
		}
	}

	/**
	 * @covers Session::start
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test_start() {
		if($this->system_obj->use_db()) {
			$this->assertInternalType('int', $this->session_obj->start());

			unset($_COOKIE['pfm_session_id']);
			$this->assertInternalType('int', $this->session_obj->start());
		} else {
			$session_id = $this->session_obj->start();
			$this->assertInternalType('string', $session_id);
			$this->assertNotEmpty($session_id);
		}
	}

	/**
	 * @covers Session::start
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function test_start_no_db() {
		if($this->system_obj->use_db()) {
			$this->set_params(['use_db' => false], 'configuration');
			$this->assertInternalType('string', $this->session_obj->start());
		}
	}

	/**
	 * @covers Session::get_id
	 */
	public function test_get_id() {
		$this->assertEquals($this->session_obj->get_id(), $this->session_id);
	}

	/**
	 * @covers Session::set_var
	 */
	public function test_set_var_db() {
		$this->assertNull($this->session_obj->set_var('test', 'test'));
	}

	/**
	 * @covers Session::get_var
	 * @depends test_set_var_db
	 */
	public function test_get_var_db() {
		$this->assertEquals('test', $this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::set_var
	 */
	public function test_set_var_session() {
		if($this->system_obj->use_db()) {
			$this->set_params(['use_db' => false], 'configuration');
			$this->assertNull($this->session_obj->set_var('test', 'test'));
		}
	}

	/**
	 * @covers Session::get_var
	 * @depends test_set_var_session
	 */
	public function test_get_var_session() {
		if($this->system_obj->use_db()) {
			$this->set_params(['use_db' => false], 'configuration');
			$val = empty($_SESSION['test']) ? '' : $_SESSION['test'];
			$this->assertEquals($val, $this->session_obj->get_var('test'));
		}
	}

	/**
	 * @covers Session::unset_var
	 */
	public function test_unset_var_db() {
		$this->assertNull($this->session_obj->unset_var('test'));
		$this->assertFalse($this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::unset_var
	 */
	public function test_unset_var_session() {
		if($this->system_obj->use_db()) {
			$this->set_params(['use_db' => false], 'configuration');
			$this->assertNull($this->session_obj->unset_var('test'));
			$this->assertFalse($this->session_obj->get_var('test'));
		}
	}

	/**
	 * @covers Session::set_uid
	 */
	public function test_set_uid() {
		$this->assertNull($this->session_obj->set_uid(1));
	}
}