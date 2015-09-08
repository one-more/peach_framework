<?php
require_once ROOT_PATH.DS."build".DS.'user'.DS.'user.php';
require_once ROOT_PATH.DS."build".DS.'user'.DS.'model'.DS.'usermodel.php';

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
     * @var $user_obj UserIdentity
     */
    private $user_obj;

	public function setUp() {
		if(empty($_COOKIE['pfm_session_id'])) {
			$_COOKIE['pfm_session_id'] = $this->session_id;
		}
		if(empty($this->user_obj)) {
			$this->user_obj = Application::get_class('User')->get_identity(1);
			$this->session_obj = Application::get_class('Session');
		}
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
        $_COOKIE['user'] = Application::get_class('User')->get_identity(1)['remember_hash'];

        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add_by_ajax
     * @expectedException WrongRequestMethodException
     */
    public function test_add_by_ajax_none_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_COOKIE['user'] = Application::get_class('User')->get_identity(1)['remember_hash'];
        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add_by_ajax
     * @expectedException WrongRightsException
     */
    public function test_add_by_ajax_not_admin() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $_COOKIE['user'] =
            Application::get_class('User')->get_identity_by_field('credentials', 'user')
            ['remember_hash'];

        Application::get_class('User')->add_by_ajax();
    }

    /**
     * @covers User::add
     * @expectedException InvalidUserDataException
     */
    public function test_add_login_exists() {
        $fields = [
            'login' => Application::get_class('User')->get_identity(1)['login']
        ];
        Application::get_class('User')->add($fields);
    }

    /**
     * @covers User::edit
     */
	public function test_edit() {
        /**
         * @var $user UserIdentity
         */
		$user = Application::get_class('User')->get_identity(1);
        $old_login = $user['password'];
        $new_login = 'test';

        Application::get_class('User')->edit([
            'login' => $new_login
        ], $user['id']);

        $user = Application::get_class('User')->get_identity(1);
        $this->assertEquals($new_login, $user['login']);

        Application::get_class('User')->edit([
            'login' => $old_login
        ], $user['id']);
	}

    /**
     * @covers User::edit
     * @expectedException InvalidUserDataException
     */
    public function test_edit_login_exists() {
        $fields = [
            'login' => Application::get_class('User')->get_identity(1)['login']
        ];
        $uid = Application::get_class('User')->get_identity_by_field('credentials', 'user')['id'];
        Application::get_class('User')->edit($fields, $uid);
    }

    /**
     * @covers User::edit_by_ajax
     */
    public function test_edit_by_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $_COOKIE['user'] = Application::get_class('User')->get_identity(1)['remember_hash'];

        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('id', 'MAX(id)');
        $new_login = $_REQUEST['login'];
        $_REQUEST['id'] = $user['id'];

        Application::get_class('User')->edit_by_ajax();

        $user = Application::get_class('User')->get_identity($user['id']);
        $this->assertEquals($user['login'], $new_login);
    }

    /**
     * @covers User::edit_by_ajax
     * @expectedException WrongRequestMethodException
     */
    public function test_edit_by_ajax_none_ajax() {
        $_REQUEST['login'] = uniqid('test_user', true);
        $_COOKIE['user'] = Application::get_class('User')->get_identity(1)['remember_hash'];

        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('id', 'MAX(id)');
        $_REQUEST['id'] = $user['id'];

        Application::get_class('User')->edit_by_ajax();
    }

    /**
     * @covers User::edit_by_ajax
     * @expectedException WrongRightsException
     */
    public function test_edit_by_ajax_not_admin() {
        $_COOKIE['user'] =
            Application::get_class('User')->get_identity_by_field('credentials', 'user')
            ['remember_hash'];
        $_REQUEST['login'] = uniqid('test_user', true);

        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('id', 'MAX(id)');
        $_REQUEST['id'] = $user['id'];

        Application::get_class('User')->edit_by_ajax();
    }

    /**
     * @covers User::delete_user
     */
    public function test_delete_user() {
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('id', 'MAX(id)');
        $this->assertTrue($user instanceof UserIdentity);

        Application::get_class('User')->delete($user['id']);

        $user = Application::get_class('User')->get_identity_by_field('id', 'MAX(id)');
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