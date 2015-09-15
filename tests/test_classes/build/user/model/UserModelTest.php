<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'model'.DS.'usermodel.php';

/**
 * Class UserModelTest
 *
 * @method bool assertTrue($cond)
 * @method bool assertFalse($cond)
 * @method bool assertNull($var)
 * @method bool assertCount($a,$b)
 * @method bool assertGreaterThan($a,$b)
 * @method bool assertEquals($a,$b)
 */
class UserModelTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var $model \User\model\UserModel
     */
    private $model;

	public function setUp() {
		$this->model = Application::get_class('\User\model\UserModel');
	}

    /**
     * @covers \User\model\UserModel::get_table
     */
    public function test_get_table() {
        $method = new ReflectionMethod($this->model, 'get_table');
        $method->setAccessible(true);
        $this->assertEquals('users', $method->invoke($this->model));
    }

	/**
	 * @covers \User\model\UserModel::login
     * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function test_login() {
		$this->assertFalse($this->model->login(null,null,null));

        /**
         * @var $user \User\identity\UserIdentity
         */
		$user = Application::get_class('User')->get_identity(1);
        $this->assertTrue($this->model->login($user->login, $user->password));

        $this->assertTrue($this->model->login($user->login, $user->password, $remember = true));
	}

    /**
     * @covers \User\model\UserModel::add_remember_hash
     */
    public function test_add_remember_hash() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('remember_hash', '');
        $method = new ReflectionMethod($this->model, 'add_remember_hash');
        $method->setAccessible(true);
        $method->invoke($this->model, (array)$user);
    }

	/**
	 * @covers \User\model\UserModel::log_out
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function test_log_out() {
		/**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;
        $this->model->log_out();
        unset($_COOKIE['user']);

        /**
         * @var $session Session
         */
        $session = Application::get_class('Session');
        $session->set_var('user', $user->remember_hash);
        $this->model->log_out();
        $this->assertNull($session->get_var('user', null));
	}

    /**
     * @covers \User\model\UserModel::get_fields
     */
    public function test_get_fields() {
        $this->assertCount(0, $this->model->get_fields());

        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $this->assertGreaterThan(0, count($this->model->get_fields($user->id)));

        $_COOKIE['user'] = $user->remember_hash;
        $this->assertGreaterThan(0, count($this->model->get_fields()));
    }

    /**
     * @covers \User\model\UserModel::register
     */
    public function test_register() {
        $fields = [
            'login' => uniqid('test_user', true)
        ];
        $this->assertGreaterThan(0, $this->model->register($fields));
    }

    /**
     * @covers \User\model\UserModel::delete_user
     */
    public function test_delete_user() {
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')
            ->get_identity_by_field('credentials', User::credentials_user);

        $this->model->delete_user($user->id);

        $this->assertCount(0, $this->model->get_fields($user->id));
    }

    /**
     * @covers \User\model\UserModel::update_fields
     */
    public function test_update_fields() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_user);
        $new_login = uniqid('test_user', true);
        $this->model->update_fields(['login' => $new_login], $user->id);
        $user = $ext->get_identity($user->id);
        $this->assertEquals($new_login, $user->login);

        $_COOKIE['user'] = $user->remember_hash;
        $new_login = uniqid('test_user', true);
        $this->model->update_fields(['login' => $new_login]);
        $user = $ext->get_identity($user->id);
        $this->assertEquals($new_login, $user->login);
    }

    /**
     * @covers \User\model\UserModel::get_users
     */
    public function test_get_users() {
        $this->assertCount(0, $this->model->get_users([0]));

        $this->assertGreaterThan(1, count($this->model->get_users()));
    }

    /**
     * @covers \User\model\UserModel::get_id
     */
    public function test_get_id() {
        $this->assertEquals(0, $this->model->get_id());

        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_user);
        $_COOKIE['user'] = $user->remember_hash;
        $this->assertEquals($user->id, $this->model->get_id());

        /**
         * @var $session Session
         */
        $session = Application::get_class('Session');
        $session->set_var('user', $user->remember_hash);
        $this->assertEquals($user->id, $this->model->get_id());
        $session->unset_var('user');
    }

    /**
     * @covers \User\model\UserModel::get_user_by_field
     */
    public function test_get_user_by_field() {
        $this->assertCount(0, $this->model->get_user_by_field('login', ''));

        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_user);
        $this->assertGreaterThan(1, count($this->model->get_user_by_field('login', $user->login)));
    }

    /**
     * @covers \User\model\UserModel::get_user_by_field
     * @expectedException InvalidArgumentException
     */
    public function test_get_user_by_field_exception() {
        $this->model->get_user_by_field(null, null);
    }
}
