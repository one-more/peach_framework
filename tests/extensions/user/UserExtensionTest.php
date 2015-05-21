<?php

require_once '../../initialize.php';

class UserExtensionTest extends PHPUnit_Framework_TestCase {

	private $session_id = 1;
	private $session_obj;
	private $user_obj;
	private $remember_hash = '$2y$10$1hjRr3BYJduVdn/9..aMgeRSMon.D5NSw3SNb5gB5i2USiJFRS2rK';
	private $test_user_login = 'root';
	private $test_user_password = '';

	public function __construct() {
		parent::__construct();
		$_COOKIE['pfm_session_id'] = $this->session_id;
		$this->user_obj = Application::get_class('User');
		$this->session_obj = Application::get_class('Session');
	}

	public function test_is_logined() {
		$this->assertFalse($this->user_obj->is_logined());

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertTrue($this->user_obj->is_logined());
		unset($_COOKIE['user']);

		$_COOKIE['user'] = 123;
		$this->assertFalse($this->user_obj->is_logined());
		unset($_COOKIE['user']);

		$_COOKIE['user'] = null;
		$this->assertFalse($this->user_obj->is_logined());
		unset($_COOKIE['user']);

		$this->session_obj->set_var('user', $this->remember_hash);
		$this->assertTrue($this->user_obj->is_logined());
		$this->session_obj->unset_var('user', $this->remember_hash);
	}

	public function test_get_id() {
		$this->assertEquals(0, $this->user_obj->get_id());

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertEquals(1, $this->user_obj->get_id());
	}

	public function test_login() {
		$this->assertCount(0, $this->user_obj->login(null,null,null));

		$login = $this->test_user_login;
		$password = $this->test_user_password;
		$this->assertCount(6, $this->user_obj->login($login, $password));
	}

	public function test_log_out() {
		$this->assertNull($this->user_obj->log_out());
	}

	public function test_get_fields() {
		$this->assertCount(0, $this->user_obj->get_fields());

		$this->assertCount(6, $this->user_obj->get_fields(1));

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertCount(6, $this->user_obj->get_fields());
	}

	public function test_get_field() {
		$this->assertEquals('', $this->user_obj->get_field('login'));

		$this->assertEquals($this->test_user_login, $this->user_obj->get_field('login', 1));

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertEquals($this->test_user_login, $this->user_obj->get_field('login'));
	}
}