<?php

/**
 * Class UserAuthTest
 *
 * @method bool assertTrue($condition)
 * @method bool assertFalse($condition)
 * @method bool assertNull($var)
 */
class UserAuthTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $obj UserAuth
     */
    private $obj;

    public function setUp() {
        if(empty($this->obj)) {
            $this->obj = Application::get_class('User')->get_auth();
        }
    }

    /**
     * @covers UserAuth::login
     */
    public function test_login() {
        $this->assertFalse($this->obj->login(null,null,null));

        /**
         * @var $test_user UserIdentity
         */
        $test_user = Application::get_class('User')->get_identity(1);
        $login = $test_user->login;
        $password = $test_user->password;
        $this->assertTrue($this->obj->login($login, $password));
    }

    /**
     * @covers UserAuth::log_out
     */
    public function test_log_out() {
        $this->assertTrue($this->obj->log_out());
    }
}
