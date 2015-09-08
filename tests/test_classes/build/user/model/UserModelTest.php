<?php
require_once ROOT_PATH.DS."build".DS.'user'.DS.'model'.DS.'usermodel.php';

/**
 * Class UserModelTest
 *
 * @method bool assertTrue($cond)
 * @method bool assertFalse($cond)
 * @method bool assertNull($var)
 * @method bool assertCount($a,$b)
 * @method bool assertGreaterThan($a,$b)
 */
class UserModelTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var $model UserModel
     */
    private $model;

	public function setUp() {
		$this->model = Application::get_class('UserModel');
	}

	/**
	 * @covers UserModel::login
	 */
	public function test_login() {
		$this->assertFalse($this->model->login(null,null,null));

		$user = Application::get_class('User')->get_identity(1);
        $this->assertTrue($this->model->login($user['login'], $user['password']));
	}

	/**
	 * @covers UserModel::log_out
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function test_log_out() {
		$user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user['remember_hash'];
        $this->model->log_out();

        /**
         * @var $session Session
         */
        $session = Application::get_class('Session');
        $session->set_var('user', $user['remember_hash']);
        $this->model->log_out();
        $this->assertNull($session->get_var('user', null));
	}

    /**
     * @covers UserModel::get_fields
     */
    public function test_get_fields() {
        $this->assertCount(0, $this->model->get_fields());

        $user = Application::get_class('User')->get_identity(1);
        $this->assertGreaterThan(0, count($this->model->get_fields($user['id'])));

        $_COOKIE['user'] = $user['remember_hash'];
        $this->assertGreaterThan(0, count($this->model->get_fields()));
    }

    /**
     * @covers UserModel::register
     */
    public function test_register() {
        $fields = [
            'login' => uniqid('test_user', true)
        ];
        $this->assertGreaterThan(0, $this->model->register($fields));
    }

    /**
     * @covers UserModel::delete_user
     */
    public function test_delete_user() {
        $user = Application::get_class('User')
            ->get_identity_by_field('credentials', User::credentials_user);

        $this->model->delete_user($user['id']);

        $this->assertCount(0, $this->model->get_fields($user['id']));
    }
}
