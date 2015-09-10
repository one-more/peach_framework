<?php

/**
 * Class UserIdentityTest
 *
 * @method bool assertTrue($cond)
 * @method bool assertFalse($cond)
 */
class UserIdentityTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        if(!empty($_COOKIE['user'])) {
            unset($_COOKIE['user']);
        }
    }

    /**
     * @covers UserIdentity::is_guest
     */
    public function test_is_guest() {
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_current();
        $this->assertTrue($user->is_guest());

        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;
        $this->assertFalse($user->is_guest());
        unset($_COOKIE['user']);

        $_COOKIE['user'] = 123;
        $user = Application::get_class('User')->get_current();
        $this->assertTrue($user->is_guest());
        unset($_COOKIE['user']);

        $_COOKIE['user'] = null;
        $user = Application::get_class('User')->get_current();
        $this->assertTrue($user->is_guest());
        unset($_COOKIE['user']);

        $user = Application::get_class('User')->get_identity(1);
        /**
         * @var $session_obj Session
         */
        $session_obj = Application::get_class('Session');
        $session_obj->set_var('user', $user->remember_hash);
        $this->assertFalse($user->is_guest());
        $session_obj->unset_var('user');
    }

    /**
     * @covers UserIdentity::is_admin
     */
    public function test_is_admin() {
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->
                get_identity_by_field('credentials', User::credentials_admin);
        if(empty($user)) {
            $user = Application::get_class('User')->get_identity(1);
        }
        $this->assertTrue($user->is_admin());
    }

    /**
     * @covers UserIdentity::is_super_admin
     */
    public function test_is_super_admin() {
        /**
         * @var $user UserIdentity
         */
        $user =
            Application::get_class('User')->
                get_identity_by_field('credentials', User::credentials_super_admin);
        $this->assertTrue($user->is_admin());
    }
}
