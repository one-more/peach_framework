<?php

/**
 * Class SessionTest
 *
 */
class SessionTest extends PHPUnit_Framework_TestCase {
	use \common\traits\TraitConfiguration;

    /**
     * @var $session_obj Session
     */
	private $session_obj;

    private static $session_id;

    public static function setUpBeforeClass() {
        /**
         * @var $session Session
         */
        $session = \common\classes\Application::get_class(Session::class);
        self::$session_id = $session->get_id();
    }

	public function setUp() {
		$this->session_obj = \common\classes\Application::get_class(Session::class);
        if(empty($_COOKIE['pfm_session_id'])) {
            $_COOKIE['pfm_session_id'] = static::$session_id;
        }
	}

    /**
     * @covers Session::__construct
     */
	public function test_construct() {
        new Session();
    }

	/**
	 * @covers Session::start
	 */
	public function test_start() {
		$session_id = $_COOKIE['pfm_session_id'];
		self::assertInternalType('int', $this->session_obj->start());
        unset($_COOKIE['pfm_session_id']);
        self::assertInternalType('int', $this->session_obj->start());
        $_COOKIE['pfm_session_id'] = $session_id;
        (new Session())->start();
	}

	/**
	 * @covers Session::get_id
	 */
	public function test_get_id() {
		self::assertEquals($this->session_obj->get_id(), $_COOKIE['pfm_session_id']);
	}

	/**
	 * @covers Session::set_var
     * @expectedException ErrorException
	 */
	public function test_set_var() {
		$this->session_obj->set_var('test', 'test');
        self::assertEquals('test', $this->session_obj->get_var('test'));

        unset($_COOKIE['pfm_session_id']);
        $this->session_obj->set_var('test', 'test');
	}

	/**
	 * @covers Session::get_var
     * @expectedException ErrorException
	 */
	public function test_get_var() {
		self::assertEquals('test', $this->session_obj->get_var('test'));

        unset($_COOKIE['pfm_session_id']);
        self::assertEquals('test', $this->session_obj->get_var('test'));
	}

	/**
	 * @covers Session::unset_var
     * @expectedException ErrorException
	 */
	public function test_unset_var() {
		$this->session_obj->unset_var('test');
		self::assertFalse($this->session_obj->get_var('test'));

        unset($_COOKIE['pfm_session_id']);
        $this->session_obj->unset_var('test');
	}

	/**
	 * @covers Session::set_uid
     * @expectedException ErrorException
	 */
	public function test_set_uid() {
		$this->session_obj->set_uid(1);

        unset($_COOKIE['pfm_session_id']);
        $this->session_obj->set_uid(1);
	}
}