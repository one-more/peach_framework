<?php

class SilentSiteRouter extends \Starter\router\SiteRouter {

    public function __destruct() {}

    protected function show_result(AjaxResponse $response) {}
}

/**
 * Class SiteRouterTest
 *
 * @method assertNull($var)
 */
class SiteRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router \Starter\router\SiteRouter
     */
    private $router;

    public function setUp() {
        if(empty($this->router)) {
            $this->router = new SilentSiteRouter();
        }
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        }
    }

    /**
     * @covers \Starter\router\SiteRouter::login
     */
    public function test_login() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->login();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::login
     * @expectedException WrongRequestMethodException
     */
    public function test_login_wrong_request() {
        $this->router->login();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::logout
     */
    public function test_logout() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $this->router->logout();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::edit_user
     */
    public function test_edit_user() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        $_REQUEST['id'] = 1;
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::edit_user
     * @expectedException WrongRightsException
     */
    public function test_edit_user_wrong_rights() {
        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::edit_user
     * @expectedException WrongRequestMethodException
     */
    public function test_edit_user_wrong_request() {
        $_REQUEST['id'] = 1;
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::add_user
     */
    public function test_add_user() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHTTPRequest';

        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity(1);
        $_COOKIE['user'] = $user->remember_hash;

        $this->router->add_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::add_user
     * @expectedException WrongRequestMethodException
     */
    public function test_add_user_wrong_request() {
        /**
         * @var $user \User\identity\UserIdentity
         */
        $user = Application::get_class('User')->get_identity_by_field('credentials', User::credentials_super_admin);
        $_COOKIE['user'] = $user->remember_hash;

        $this->router->add_user();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\router\SiteRouter::add_user
     * @expectedException WrongRightsException
     */
    public function test_add_user_wrong_rights() {
        $this->router->add_user();
        $this->assertNull(error_get_last());
    }
}
