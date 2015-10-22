<?php

namespace test_classes\templates\starter\router;

use common\classes\Application;
use common\classes\Error;
use Starter\routers\ActionRouter;
use User\models\UserModel;

class SilentActionRouter extends ActionRouter {

    public $response;

    public function __destruct() {
        ob_start();
        parent::__destruct();
        ob_end_clean();
    }
}

class ActionRouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $router ActionRouter
     */
    private $router;

    public function setUp() {
        $this->router = Application::get_class(SilentActionRouter::class);
    }

    /**
     * @covers Starter\routers\ActionRouter::__construct
     */
    public function test_construct() {
        new SilentActionRouter();
    }

    /**
     * @covers Starter\routers\ActionRouter::login
     * @covers Starter\routers\ActionRouter::admin_panel_login
     */
    public function test_login() {
        $this->router->login();
        self::assertTrue($this->router->response->status == 'error');

        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'password' => ['=', ''],
            'and' => [
                'credentials' => ['=', \User::credentials_user]
            ]
        ])->one();
        self::assertEquals($model->credentials, \User::credentials_user);
        $_REQUEST['login'] = $model->login;
        $_REQUEST['password'] = $model->password;
        $this->router->login();
        self::assertTrue($this->router->response->status == 'success');

        $this->router->admin_panel_login();
        self::assertTrue($this->router->response->status == 'error');

        $model = $mapper->find_where([
            'password' => ['=', ''],
            'and' => [
                'credentials' => ['=', \User::credentials_admin]
            ]
        ])->one();
        self::assertEquals($model->credentials, \User::credentials_admin);
        $_REQUEST['login'] = $model->login;
        $_REQUEST['password'] = $model->password;
        $this->router->admin_panel_login();
        self::assertTrue($this->router->response->status == 'success');
    }

    /**
     * @covers Starter\routers\ActionRouter::logout
     */
    public function test_logout() {
        $this->router->logout();
        self::assertTrue($this->router->response->status == 'success');
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $identity = $user->get_identity();
        self::assertTrue($identity->is_guest());
    }

    /**
     * @covers Starter\routers\ActionRouter::site_add_user
     * @covers Starter\routers\ActionRouter::save_user
     */
    public function test_site_add_user() {
        $this->router->site_add_user();
    }

    /**
     * @covers Starter\routers\ActionRouter::admin_panel_add_user
     * @covers Starter\routers\ActionRouter::save_user
     */
    public function test_admin_panel_add_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', \User::credentials_super_admin]
        ])->one();
        $_COOKIE['user'] = $model->remember_hash;
        $this->router->admin_panel_add_user();

        unset($_COOKIE['user']);
        $this->router->admin_panel_add_user();
    }

    /**
     * @covers Starter\routers\ActionRouter::site_edit_user
     * @covers Starter\routers\ActionRouter::save_user
     */
    public function test_site_edit_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', \User::credentials_user]
        ])->one();
        $_REQUEST['id'] = $model->id;
        $this->router->site_edit_user();
        $this->assertTrue($this->router->response->status == 'error');

        $_COOKIE['user'] = $model->remember_hash;
        $_REQUEST['login'] = $model->login;
        $this->router->site_edit_user();
        $this->assertTrue($this->router->response->status == 'success');
    }

    /**
     * @covers Starter\routers\ActionRouter::admin_panel_edit_user
     * @covers Starter\routers\ActionRouter::save_user
     */
    public function test_admin_panel_edit_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', \User::credentials_super_admin]
        ])->one();
        $_COOKIE['user'] = $model->remember_hash;
        $_REQUEST['id'] = $model->id;
        $this->router->admin_panel_edit_user();

        unset($_COOKIE['user']);
        $this->router->admin_panel_edit_user();
    }

    /**
     * @covers Starter\routers\ActionRouter::site_delete_user
     */
    public function test_site_delete_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', \User::credentials_user],
            'and' => [
                'remember_hash' => ['!=', '']
            ]
        ])->one();
        $_REQUEST['id'] = $model->id;
        $this->router->site_delete_user();
        $this->assertTrue($this->router->response->status == 'error');

        $_COOKIE['user'] = $model->remember_hash;
        $this->router->site_delete_user();
        $this->assertTrue($this->router->response->status == 'success');
    }

    /**
     * @covers Starter\routers\ActionRouter::admin_panel_delete_user
     */
    public function test_admin_panel_delete_user() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', \User::credentials_super_admin]
        ])->one();
        $_COOKIE['user'] = $model->remember_hash;
        $_REQUEST['id'] = $model->id;
        $this->router->admin_panel_delete_user();

        unset($_COOKIE['user']);
        $this->router->admin_panel_delete_user();
    }

    /**
     * @covers Starter\routers\ActionRouter::delete_user
     */
    public function test_delete_user() {
        $method = new \ReflectionMethod($this->router, 'delete_user');
        $method->setAccessible(true);
        $method->invoke($this->router);
    }

    /**
     * @covers Starter\routers\ActionRouter::save_user
     */
    public function test_save_user() {
        $method = new \ReflectionMethod($this->router, 'save_user');
        $method->setAccessible(true);
        $method->invoke($this->router);

        self::assertTrue($this->router->response->status == 'error');

        $_REQUEST['login'] = uniqid('test', true);
        $method->invoke($this->router);
        self::assertTrue($this->router->response->status == 'success');

        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model UserModel
         */
        $model = $mapper->find_where([
            'remember_hash' => ['!=', '']
        ])->one();
        $_REQUEST['id'] = $model->id;
        $_REQUEST['login'] = uniqid('test', true);
        $method->invoke($this->router);
        self::assertTrue($this->router->response->status == 'success');
    }
}
