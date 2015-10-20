<?php

/**
 * Class SessionTest
 *
 */
class SessionTest extends PHPUnit_Framework_TestCase {
	use \traits\TraitConfiguration;

    /**
     * @var $session_obj Session
     */
	private $session_obj;

	public function setUp() {
		$this->session_obj = \classes\Application::get_class(Session::class);
	}

    /**
     * @covers Session::__construct
     */
	public function test_construct() {
        self::assertInternalType('object', new Session());
    }

	/**
	 * @covers Session::start
     * @expectedException PHPUnit_Framework_Error
	 */
	public function test_start() {
		$session_id = $_COOKIE['pfm_session_id'];
		self::assertInternalType('int', $this->session_obj->start());
        unset($_COOKIE['pfm_session_id']);
        self::assertInternalType('int', $this->session_obj->start());
        $_COOKIE['pfm_session_id'] = $session_id;
	}

	/**
	 * @covers Session::get_id
	 */
	public function test_get_id() {
		self::assertEquals($this->session_obj->get_id(), $_COOKIE['pfm_session_id']);
	}

	/**
	 * @covers Session::set_var
	 */
	public function test_set_var() {
		$this->session_obj->set_var('test', 'test');
        self::assertEquals('test', $this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::get_var
	 */
	public function test_get_var() {
		self::assertEquals('test', $this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::unset_var
	 */
	public function test_unset_var() {
		$this->session_obj->unset_var('test');
		self::assertFalse($this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::set_uid
	 */
	public function test_set_uid() {
		$this->session_obj->set_uid(1);
	}
}