<?php
require_once ROOT_PATH.DS."build".DS.'user'.DS.'model'.DS.'usermodel.php';

class UserModelTest extends \PHPUnit_Framework_TestCase {
	private $model;
	private $remember_hash = '$2y$10$1hjRr3BYJduVdn/9..aMgeRSMon.D5NSw3SNb5gB5i2USiJFRS2rK';
	private $test_user_login = 'root';
	private $test_user_password = '';
	private $session_id = 1;

	public function setUp() {
		$system = Application::get_class('System');
		$params = $system->get_configuration()['db_params'];
		$this->model = Application::get_class('UserModel', $params);
	}

	/**
	 * @covers UserModel::login
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function test_login() {
		$this->assertCount(0, $this->model->login(null,null,null));

		$login = $this->test_user_login;
		$password = $this->test_user_password;
		$fields = $this->model->login($login, $password);
		$this->assertGreaterThan(5, count($fields));

		$fields = $this->model->login($login, $password, true);
		$this->assertGreaterThan(5, count($fields));
	}

	/**
	 * @covers UserModel::log_out
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function test_log_out() {
		$this->model->log_out();
		$_COOKIE['pfm_session_id'] = $this->session_id;
		$session = Application::get_class('Session');
		$this->assertFalse($session->get_var('user', false));

		$_COOKIE['user'] = $this->remember_hash;
		$this->assertNull($this->model->log_out());
		unset($_COOKIE['user']);
	}
}
 