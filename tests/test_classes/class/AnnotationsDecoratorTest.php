<?php

class ObjToTestAnnotations {

    /**
     * @credentials admin
     * @return string
     */
    public function get_test() {
        return 'test';
    }

    /**
     * @credentials admin
     * @requestMethod Ajax
     */
    public function add_test() {

    }
}

class AnnotationsDecoratorTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $obj AnnotationsDecorator
     */
    private $obj;

    /**
     * @covers AnnotationsDecorator::__construct
     */
    public function test__construct() {
        new AnnotationsDecorator(new ObjToTestAnnotations());
    }

    public function setUp() {
        if(empty($this->obj)) {
            $this->obj = new AnnotationsDecorator(new ObjToTestAnnotations());
        }
    }

    /**
     * @covers AnnotationsDecorator::__call
     */
    public function test_call() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_admin);
        if(empty($user)) {
            $user = $ext->get_identity_by_field('credentials', User::credentials_super_admin);
        }
        $_COOKIE['user'] = $user->remember_hash;
        $this->assertEquals('test', $this->obj->get_test());
    }

    /**
     * @covers AnnotationsDecorator::__call
     * @expectedException WrongRightsException
     */
    public function test_call_no_admin() {
        unset($_COOKIE['user']);
        $this->assertEquals('test', $this->obj->get_test());
    }

    /**
     * @covers AnnotationsDecorator::__call
     * @expectedException NotExistedMethodException
     */
    public function test_call_none_existed_method() {
        $this->assertEquals('test', $this->obj->not_existed_method());
    }

    /**
     * @covers AnnotationsDecorator::handle_annotations
     */
    public function test_handle_annotations() {
        $method = new ReflectionMethod($this->obj, 'handle_annotations');
        $method->setAccessible(true);

        $annotations = ReflectionHelper::get_method_annotations('ObjToTestAnnotations', 'add_test');
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_admin);
        if(empty($user)) {
            $user = $ext->get_identity_by_field('credentials', User::credentials_super_admin);
        }
        $_COOKIE['user'] = $user->remember_hash;
        $_SERVER['HTTP_X_REQUESTED_WITH']  = 'xmlhttprequest';
        $method->invoke($this->obj, $annotations);
    }

    /**
     * @covers AnnotationsDecorator::handle_annotations
     * @expectedException WrongRightsException
     */
    public function test_handle_annotations_no_admin() {
        $method = new ReflectionMethod($this->obj, 'handle_annotations');
        $method->setAccessible(true);

        $annotations = ReflectionHelper::get_method_annotations('ObjToTestAnnotations', 'add_test');
        $_SERVER['HTTP_X_REQUESTED_WITH']  = 'xmlhttprequest';
        $method->invoke($this->obj, $annotations);
    }

    /**
     * @covers AnnotationsDecorator::handle_annotations
     * @expectedException WrongRequestMethodException
     */
    public function test_handle_annotations_not_ajax() {
        $method = new ReflectionMethod($this->obj, 'handle_annotations');
        $method->setAccessible(true);

        $annotations = ReflectionHelper::get_method_annotations('ObjToTestAnnotations', 'add_test');
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_admin);
        if(empty($user)) {
            $user = $ext->get_identity_by_field('credentials', User::credentials_super_admin);
        }
        $_COOKIE['user'] = $user->remember_hash;
        $method->invoke($this->obj, $annotations);
    }

    /**
     * @covers AnnotationsDecorator::check_credentials
     */
    public function test_check_credentials() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $user = $ext->get_identity_by_field('credentials', User::credentials_super_admin);
        $_COOKIE['user'] = $user->remember_hash;

        $method = new ReflectionMethod($this->obj, 'check_credentials');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->obj, 'admin'));
        $this->assertTrue($method->invoke($this->obj, 'super_admin'));
        $this->assertFalse($method->invoke($this->obj, 'user'));
    }

    /**
     * @covers AnnotationsDecorator::check_request_method
     */
    public function test_check_request_method() {
        $method = new ReflectionMethod($this->obj, 'check_request_method');
        $method->setAccessible(true);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $this->assertTrue($method->invoke($this->obj, 'ajax'));

        $_SERVER['REQUEST_METHOD'] = 'post';
        $this->assertTrue($method->invoke($this->obj, 'post'));

        $_SERVER['REQUEST_METHOD'] = 'get';
        $this->assertTrue($method->invoke($this->obj, 'get'));

        $this->assertFalse($method->invoke($this->obj, 'put'));
    }
}
