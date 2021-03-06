<?php

use common\decorators\AnnotationsDecorator;
use common\classes\Application;
use common\helpers\ReflectionHelper;

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
     * @covers common\decorators\AnnotationsDecorator::__construct
     */
    public function test__construct() {
        new AnnotationsDecorator(new ObjToTestAnnotations());
    }

    public function setUp() {
        $this->obj = new AnnotationsDecorator(new ObjToTestAnnotations());
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::__call
     */
    public function test_call() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class(User::class);
        $mapper = $ext->get_mapper();
        $user = $mapper->find_where([
            'credentials' => ['=', User::credentials_admin]
        ])->one();
        $_COOKIE['user'] = $user->remember_hash;
        self::assertEquals('test', $this->obj->get_test());
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::__call
     * @expectedException \common\exceptions\WrongRightsException
     */
    public function test_call_no_admin() {
        unset($_COOKIE['user']);
        self::assertEquals('test', $this->obj->get_test());
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::__call
     * @expectedException \common\exceptions\NotExistedMethodException
     */
    public function test_call_none_existed_method() {
        self::assertEquals('test', $this->obj->not_existed_method());
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::handle_annotations
     */
    public function test_handle_annotations() {
        $method = new ReflectionMethod($this->obj, 'handle_annotations');
        $method->setAccessible(true);

        $annotations = ReflectionHelper::get_method_annotations('ObjToTestAnnotations', 'add_test');
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $mapper = $ext->get_mapper();
        $user = $mapper->find_where([
            'credentials' => ['=', User::credentials_admin]
        ])->one();
        if(empty($user)) {
            $user = $mapper->find_where(['credentials' => ['=', User::credentials_super_admin]])->one();
        }
        $_COOKIE['user'] = $user->remember_hash;
        $_SERVER['HTTP_X_REQUESTED_WITH']  = 'xmlhttprequest';
        $method->invoke($this->obj, $annotations);
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::handle_annotations
     * @expectedException \common\exceptions\WrongRightsException
     */
    public function test_handle_annotations_no_admin() {
        $method = new ReflectionMethod($this->obj, 'handle_annotations');
        $method->setAccessible(true);

        $annotations = ReflectionHelper::get_method_annotations('ObjToTestAnnotations', 'add_test');
        $_SERVER['HTTP_X_REQUESTED_WITH']  = 'xmlhttprequest';
        $method->invoke($this->obj, $annotations);
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::handle_annotations
     * @expectedException \common\exceptions\WrongRequestMethodException
     */
    public function test_handle_annotations_not_ajax() {
        $method = new ReflectionMethod($this->obj, 'handle_annotations');
        $method->setAccessible(true);

        $annotations = ReflectionHelper::get_method_annotations('ObjToTestAnnotations', 'add_test');
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $mapper = $ext->get_mapper();
        $user = $mapper->find_where([
            'credentials' => ['=', User::credentials_admin]
        ])->one();
        if(empty($user)) {
            $user = $mapper->find_where(['credentials' => ['=', User::credentials_super_admin]])->one();
        }
        $_COOKIE['user'] = $user->remember_hash;
        $method->invoke($this->obj, $annotations);
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::check_credentials
     */
    public function test_check_credentials() {
        /**
         * @var $ext User
         */
        $ext = Application::get_class('User');
        $mapper = $ext->get_mapper();
        $user = $mapper->find_where(['credentials' => ['=', User::credentials_super_admin]])->one();
        $_COOKIE['user'] = $user->remember_hash;

        $method = new ReflectionMethod($this->obj, 'check_credentials');
        $method->setAccessible(true);

        self::assertTrue($method->invoke($this->obj, 'admin'));
        self::assertTrue($method->invoke($this->obj, 'super_admin'));
        self::assertFalse($method->invoke($this->obj, 'user'));
    }

    /**
     * @covers common\decorators\AnnotationsDecorator::check_request_method
     */
    public function test_check_request_method() {
        $method = new ReflectionMethod($this->obj, 'check_request_method');
        $method->setAccessible(true);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        self::assertTrue($method->invoke($this->obj, 'ajax'));

        $_SERVER['REQUEST_METHOD'] = 'post';
        self::assertTrue($method->invoke($this->obj, 'post'));

        $_SERVER['REQUEST_METHOD'] = 'get';
        self::assertTrue($method->invoke($this->obj, 'get'));

        self::assertFalse($method->invoke($this->obj, 'put'));
    }
}
