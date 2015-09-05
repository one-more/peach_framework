<?php

class UserIdentityTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $obj UserIdentity
     */
    private $obj;

    public function setUp() {
        if(empty($this->obj)) {
            $this->obj = Application::get_class('User')->get_identity();
        }
    }

    /**
     * @covers UserIdentity::is_guest
     */
    public function test_is_guest() {
        $this->assertTrue($this->obj->is_guest());

        $_COOKIE['user'] = $this->obj['remember_hash'];
        $this->assertFalse($this->obj->is_guest());
        unset($_COOKIE['user']);

        $_COOKIE['user'] = 123;
        $this->assertTrue($this->obj->is_guest());
        unset($_COOKIE['user']);

        $_COOKIE['user'] = null;
        $this->assertTrue($this->obj->is_guest());
        unset($_COOKIE['user']);

        /**
         * @var $session_obj Session
         */
        $session_obj = Application::get_class('Session');
        $session_obj->set_var('user', $this->obj['remember_hash']);
        $this->assertFalse($this->obj->is_guest());
        $session_obj->unset_var('user', $this->obj['remember_hash']);
    }
}
