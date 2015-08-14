<?php

class SilentSiteRouter extends SiteRouter {

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
     * @covers SiteRouter::edit_user
     */
    public function test_edit_user() {
        $_REQUEST['id'] = 1;
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $fields = $user->get_fields(1);
        $_COOKIE['user'] = $fields['remember_hash'];

        $this->router->edit_user();
        $this->assertNull(error_get_last());
    }
}
