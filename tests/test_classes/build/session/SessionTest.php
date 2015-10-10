<?php
require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'session.php';
require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'model'.DS.'sessionmodel.php';

/**
 * Class SessionTest
 *
 * @method bool assertInternalType($a, $b)
 * @method bool assertEquals($a, $b)
 * @method bool assertNotEmpty($a)
 * @method bool assertNull($a)
 * @method bool assertFalse($a)
 */
class SessionTest extends PHPUnit_Framework_TestCase {
	use TraitConfiguration;

    /**
     * @var $session_obj Session
     */
	private $session_obj;

	public function setUp() {
		$this->session_obj = Application::get_class('Session');
	}

    /**
     * @covers Session::__construct
     */
	public function test_construct() {
        $this->assertInternalType('object', new Session());
    }

	/**
	 * @covers Session::start
     * @expectedException PHPUnit_Framework_Error
	 */
	public function test_start() {
		$session_id = $_COOKIE['pfm_session_id'];
		$this->assertInternalType('int', $this->session_obj->start());
        unset($_COOKIE['pfm_session_id']);
        $this->assertInternalType('int', $this->session_obj->start());
        $_COOKIE['pfm_session_id'] = $session_id;
	}

	/**
	 * @covers Session::get_id
	 */
	public function test_get_id() {
		$this->assertEquals($this->session_obj->get_id(), $_COOKIE['pfm_session_id']);
	}

	/**
	 * @covers Session::set_var
	 */
	public function test_set_var() {
		$this->session_obj->set_var('test', 'test');
        $this->assertEquals('test', $this->session_obj->get_var('test'));
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
		$this->session_obj->unset_var('test');
		$this->assertFalse($this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::set_uid
	 */
	public function test_set_uid() {
		$this->session_obj->set_uid(1);
	}
}