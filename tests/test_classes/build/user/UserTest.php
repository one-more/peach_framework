<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'user.php';
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'model'.DS.'usermodel.php';

class TestModel extends \User\model\UserModel {

    public function get_user_with_max_id() {
        return $this->select()
            ->where(['deleted' => ['=', 0]])
            ->order_by(['id DESC'])
            ->limit(1)
            ->execute()
            ->get_array();
    }
}

/**
 * Class UserTest
 *
 * @method bool assertTrue($cond)
 * @method bool assertFalse($cond)
 * @method bool assertNull($var)
 * @method bool assertEquals($a, $b)
 * @method bool assertInternalType($a, $b)
 */
class UserTest extends PHPUnit_Framework_TestCase {

	private $session_id = 1;
    /**
     * @var $session_obj Session
     */
    private $session_obj;
    /**
     * @var $user_obj \User\identity\UserIdentity
     */
    private $user_obj;

    /**
     * @var $test_model TestModel
     */
    private $test_model;

	public function setUp() {
		if(empty($_COOKIE['pfm_session_id'])) {
			$_COOKIE['pfm_session_id'] = $this->session_id;
		}
		if(empty($this->user_obj)) {
			$this->user_obj = Application::get_class('User')->get_identity(1);
			$this->session_obj = Application::get_class('Session');
            $this->test_model = new TestModel();
		}
	}

    /**
     * @covers User::__construct
     */
    public function test_construct() {
        new User();
    }

    /**
     * @covers User::get_auth
     */
    public function test_get_auth() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $this->assertTrue($ext->get_auth() instanceof AnnotationsDecorator);
    }

    /**
     * @covers User::get_current
     */
    public function test_get_current() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $this->assertTrue($ext->get_current() instanceof \User\identity\UserIdentity);
    }

    /**
     * @covers User::get_identity
     */
    public function test_get_identity() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $this->assertNull($ext->get_identity(0));

        $this->assertTrue($ext->get_identity(1) instanceof \User\identity\UserIdentity);
    }

    /**
     * @covers User::get_identity_by_field
     */
    public function test_get_identity_by_field() {
         /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $this->assertNull($ext->get_identity_by_field('id', 0));

        $this->assertTrue($ext->get_identity_by_field('id', 1) instanceof \User\identity\UserIdentity);
    }

    /**
     * @covers User::add
     */
	public function test_add() {
        foreach([1,2,3] as $el) {
            $fields = [
                'login' => uniqid('test_user', true)
            ];
            Application::get_class('User')->add($fields);
        }
    }

    /**
     * @covers User::add
     * @expectedException InvalidUserDataException
     */
    public function test_add_exception() {
        Application::get_class('User')->add([]);
    }

    /**
     * @covers User::add_by_ajax
     */
    public function test_add_by_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add_by_ajax
     * @expectedException WrongRequestMethodException
     */
    public function test_add_by_ajax_none_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);

        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        }

        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add_by_ajax
     * @expectedException WrongRightsException
     */
    public function test_add_by_ajax_not_admin() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('credentials', User::credentials_user);
        $_COOKIE['user'] = $user->remember_hash;

        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add_by_ajax
     */
    public function test_add_by_ajax_none_login() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add
     * @expectedException InvalidUserDataException
     */
    public function test_add_login_exists() {
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $fields = [
            'login' =>$user->login
        ];
        Application::get_class('User')->add($fields);
    }

    /**
     * @covers User::edit
     */
	public function test_edit() {
        /**
         * @var $user \User\identity\UserIdentity
         */
		$user = Application::get_class('User')->get_identity(1);
        $old_login = $user->login;
        $new_login = 'test';

        Application::get_class('User')->edit([
            'login' => $new_login
        ], $user->id);

        $user = Application::get_class('User')->get_identity(1);
        $this->assertEquals($new_login, $user->login);

        Application::get_class('User')->edit([
            'login' => $old_login
        ], $user->id);
	}

    /**
     * @covers User::edit
     * @expectedException InvalidUserDataException
     */
    public function test_edit_login_exists() {
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $uid = $user->id;

        $user = Application::get_class('User')->get_identity_by_field('credentials', User::credentials_user);
        $fields = [
            'login' =>$user->login
        ];

        Application::get_class('User')->edit($fields, $uid);
    }

    /**
     * @covers User::edit_by_ajax
     */
    public function test_edit_by_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        $uid = $this->test_model->get_user_with_max_id()['id'];
        $new_login = $_REQUEST['login'];
        $_REQUEST['id'] = $uid;

        Application::get_class('User')->edit_by_ajax();

        $user = Application::get_class('User')->get_identity($uid);
        $this->assertEquals($user->login, $new_login);
    }

    /**
     * @covers User::edit_by_ajax
     * @expectedException WrongRequestMethodException
     */
    public function test_edit_by_ajax_none_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        }

        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity($this->test_model->get_user_with_max_id()['id']);
        $_REQUEST['id'] = $user->id;

        Application::get_class('User')->edit_by_ajax();
    }

    /**
     * @covers User::edit_by_ajax
     * @expectedException WrongRightsException
     */
    public function test_edit_by_ajax_not_admin() {
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('credentials', User::credentials_user);
        $_COOKIE['user'] = $user->remember_hash;
        $_REQUEST['login'] = uniqid('test_user', true);

        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity($this->test_model->get_user_with_max_id()['id']);
        $_REQUEST['id'] = $user->id;

        Application::get_class('User')->edit_by_ajax();
    }

    /**
     * @covers User::edit_by_ajax
     * @expectedException InvalidArgumentException
     */
    public function test_edit_by_ajax_not_id() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = $ext->get_identity_by_field('credentials', User::credentials_super_admin);
        $_COOKIE['user'] = $user->remember_hash;

        $uid = $this->test_model->get_user_with_max_id()['id'];
        $new_login = $_REQUEST['login'];

        Application::get_class('User')->edit_by_ajax();

        $user = Application::get_class('User')->get_identity($uid);
        $this->assertEquals($user->login, $new_login);
    }

    /**
     * @covers User::delete
     */
    public function test_delete_user() {
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity($this->test_model->get_user_with_max_id()['id']);
        $this->assertTrue($user instanceof \User\identity\UserIdentity);

        Application::get_class('User')->delete($user->id);

        $user = Application::get_class('User')->get_identity($user->id);
        $this->assertNull($user);
    }

	/**
	 * @covers User::get_list
	 */
	public function test_get_list() {
		$this->assertInternalType('array', Application::get_class('User')->get_list());

		$this->assertInternalType('array', Application::get_class('User')->get_list([1,2,3]));
	}
}