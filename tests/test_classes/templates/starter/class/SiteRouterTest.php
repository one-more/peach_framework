<?php

class SilentSiteRouter extends SiteRouter {

    public function __destruct() {}

    protected function show_result(AjaxResponse $response) {}
}

class SiteRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router SiteRouter
     */
    private $router;

    public function setUp() {
        if(empty($this->router)) {
            $this->router = new SilentSiteRouter();
        }
    }

    /**
     * @covers SiteRouter::login
     */
    public function test_login() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->login();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::login
     * @expectedException WrongRequestMethodException
     */
    public function test_login_wrong_request() {
        $this->router->login();
        $this->assertNull(error_get_last());
    }

    public function test_language_model() {
        $this->router->language_model();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::logout
     */
    public function test_logout() {
        $this->router->logout();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::edit_user
     */
    public function test_edit_user() {
        $_REQUEST['id'] = 1;
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user['remember_hash'];

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::edit_user
     * @expectedException WrongRightsException
     */
    public function test_edit_user_wrong_rights() {
        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::edit_user
     * @expectedException WrongRequestMethodException
     */
    public function test_edit_user_wrong_request() {
        $_REQUEST['id'] = 1;
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user['remember_hash'];

        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::add_user
     */
    public function test_add_user() {
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity();
        $_COOKIE['user'] = $user['remember_hash'];

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->add_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::add_user
     * @expectedException WrongRequestMethodException
     */
    public function test_add_user_wrong_request() {
        /**
         * @var $user UserIdentity
         */
        $user = Application::get_class('User')->get_identity();
        $_COOKIE['user'] = $user['remember_hash'];

        $this->router->add_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers SiteRouter::add_user
     * @expectedException WrongRightsException
     */
    public function test_add_user_wrong_rights() {
        $this->router->add_user();
        $this->assertNull(error_get_last());
    }
}
