<?php

class SessionExtensionTest extends PHPUnit_Framework_TestCase {

	private $session_obj;
	private $session_id = 1;

	public function __construct() {
		parent::__construct();
		$this->session_obj = Application::get_class('Session');
		$_COOKIE['pfm_session_id'] = $this->session_id;
	}

	/**
	 * @covers Session::start
	 */
	public function test_start() {
		$system = Application::get_class('System');
		if($system->use_db()) {
			$this->assertInternalType('int', $this->session_obj->start());
		} else {
			$session_id = $this->session_obj->start();
			$this->assertInternalType('string', $session_id);
			$this->assertNotEmpty($session_id);
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
	public function test_set_var() {
		$this->assertNull($this->session_obj->set_var('test', 'test'));
	}

	/**
	 * @covers Session::get_var
	 */
	public function test_get_var() {
		$this->assertEquals('test', $this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::unset_var
	 */
	public function test_unset_var() {
		$this->assertNull($this->session_obj->unset_var('test'));
		$this->assertFalse($this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::set_uid
	 */
	public function test_set_uid() {
		$this->assertNull($this->session_obj->set_uid(1));
	}
}