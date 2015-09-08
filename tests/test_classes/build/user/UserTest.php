<?php
require_once ROOT_PATH.DS."build".DS.'user'.DS.'user.php';
require_once ROOT_PATH.DS."build".DS.'user'.DS.'model'.DS.'usermodel.php';

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
        $fields = [
			'login' => uniqid('test_user', true)
		];
        Application::get_class('User')->add($fields);
    }

    /**
     * @covers User::add
     * @expectedException InvalidUserDataException
     */
    public function test_add_exception() {
        Application::get_class('User')->add([]);
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