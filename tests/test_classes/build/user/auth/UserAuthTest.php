<?php

require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'auth'.DS.'userauth.php';

/**
 * Class UserAuthTest
 *
 * @method bool assertTrue($condition)
 * @method bool assertFalse($condition)
 * @method bool assertNull($var)
 */
class UserAuthTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $obj \User\auth\UserAuth
     */
    private $obj;

    public function setUp() {
        if(empty($this->obj)) {
            $this->obj = Application::get_class('User')->get_auth();
        }
    }

    /**
     * @covers \User\auth\UserAuth::login
     */
    public function test_login() {
        $this->assertFalse($this->obj->login(null,null,null));

        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        /**
         * @var $test_user \User\identity\UserIdentity
         */
        $test_user = $ext->get_identity_by_field('credentials', User::credentials_user);
        $login = $test_user->login;
        $password = $test_user->password;
        $this->assertTrue($this->obj->login($login, $password));

        $fields = [
            'login' => uniqid('test_', true),
            'password' => uniqid('', true)
        ];
        $id = $ext->add($fields);
        Error::log($id);
        $this->assertTrue($this->obj->login($fields['login'], $fields['password']));
    }

    /**
     *  @covers \User\auth\UserAuth::login_by_ajax
     */
    public function test_login_by_ajax() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->assertFalse($this->obj->login_by_ajax());

        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_user);
        $_REQUEST['login'] = $user->login;
        $_REQUEST['password'] = $user->password;
        $this->assertTrue($this->test_login_by_ajax());
    }

    /**
     * @covers \User\auth\UserAuth::log_out
     */
    public function test_log_out() {
        $this->assertTrue($this->obj->log_out());
    }
}
