<?php
require_once ROOT_PATH.DS."build".DS.'user'.DS.'user.php';
require_once ROOT_PATH.DS."build".DS.'user'.DS.'model'.DS.'usermodel.php';

class UserTest extends PHPUnit_Framework_TestCase {

	private $session_id = 1;
	private $session_obj;
	private $user_obj;
	private $remember_hash = '$2y$10$1hjRr3BYJduVdn/9..aMgeRSMon.D5NSw3SNb5gB5i2USiJFRS2rK';
	private $test_user_login = 'root';
	private $test_user_password = '';

	public function setUp() {
		if(empty($_COOKIE['pfm_session_id'])) {
			$_COOKIE['pfm_session_id'] = $this->session_id;
		}
		if(is_null($this->user_obj)) {
			$this->user_obj = Application::get_class('User');
			$this->session_obj = Application::get_class('Session');
		}
	}

	/**
	 * @covers User::is_logined
	 */
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

	/**
	 * @covers User::get_id
	 */
	public function test_get_id() {
		$this->assertEquals(0, $this->user_obj->get_id());

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertEquals(1, $this->user_obj->get_id());
	}

	/**
	 * @covers User::login
	 */
	public function test_login() {
		$this->assertCount(0, $this->user_obj->login(null,null,null));

		$login = $this->test_user_login;
		$password = $this->test_user_password;
		$fields = $this->user_obj->login($login, $password);
		$this->assertGreaterThan(5, count($fields));
	}

	/**
	 * @covers User::log_out
	 */
	public function test_log_out() {
		$this->assertNull($this->user_obj->log_out());
	}

	/**
	 * @covers User::get_fields
	 */
	public function test_get_fields() {
		$this->assertCount(0, $this->user_obj->get_fields());

		$fields = $this->user_obj->get_fields(1);
		$this->assertGreaterThan(5, $fields);

		$_COOKIE['user'] = $this->remember_hash;
		$fields = $this->user_obj->get_fields(1);
		$this->assertGreaterThan(5, $fields);;
	}

	/**
	 * @covers User::get_field
	 */
	public function test_get_field() {
		$this->assertEquals('', $this->user_obj->get_field('login'));

		$this->assertEquals($this->test_user_login, $this->user_obj->get_field('login', 1));

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertEquals($this->test_user_login, $this->user_obj->get_field('login'));
	}

	/**
	 * @expectedException LoginExistsException
	 * @covers User::register
	 */
	public function test_register_existed() {
		$fields = [
			'login' => 'root'
		];
		$this->user_obj->register($fields);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function test_register_no_login() {
		$this->user_obj->register([]);
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @covers User::register
	 */
	public function test_register_no_array() {
		$this->user_obj->register(new StdClass);
	}

	public function test_register() {
		$fields = [
			'login' => uniqid('test_user', true)
		];
		$register_id = $this->user_obj->register($fields);
		$this->assertInternalType('int', $register_id);
		return $register_id;
	}

	/**
	 * @depends test_register
	 * @covers User::delete_user
	 */
	public function test_delete_user($uid) {
		$this->assertNull($this->user_obj->delete_user($uid));
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @covers User::update_fields
	 */
	public function test_update_empty_fields() {
		$this->assertNull($this->user_obj->update_fields([]));
	}

	/**
	 * @covers User::update_fields
	 */
	public function test_update_fields() {
		$this->assertNull($this->user_obj->update_fields(['deleted' => 1]));
	}

	/**
	 * @covers User::get_users
	 */
	public function test_get_users() {
		$this->assertInternalType('array', $this->user_obj->get_users());

		$this->assertInternalType('array', $this->user_obj->get_users([1,2,3]));
	}

	/**
	 * @covers User::get_users_field
	 */
	public function test_get_users_field() {
		$this->assertInternalType('array', $this->user_obj->get_users_field(''));

		$this->assertInternalType('array', $this->user_obj->get_users_field('login'));

		$this->assertInternalType('array', $this->user_obj->get_users_field(null));

		$this->assertInternalType('array', $this->user_obj->get_users_field('login', 1));

		$this->assertInternalType('array', $this->user_obj->get_users_field('login', [1,2]));
	}

	/**
	 * @covers User::get_user_by_field
	 */
	public function test_get_user_by_field() {
		$this->assertCount(0, $this->user_obj->get_user_by_field('login', ''));

		$fields = $this->user_obj->get_user_by_field('login', 'root');
		$this->assertGreaterThan(5, count($fields));
	}

	/**
	 * @expectedException InvalidArgumentException
	 * @covers User::get_user_by_field
	 */
	public function test_get_user_by_field_not_string() {
		$this->assertCount(0, $this->user_obj->get_user_by_field('login', []));
	}
}