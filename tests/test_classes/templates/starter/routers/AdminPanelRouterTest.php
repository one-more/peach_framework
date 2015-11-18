<?php

use common\classes\GetResponse;

class SilentAdminPanelRouter extends \Starter\routers\AdminPanelRouter {

    public function __destruct() {}

    protected function show_result(GetResponse $response) {}
}

\Starter::$current_router = \Starter\routers\AdminPanelRouter::class;

/**
 * Class AdminPanelRouterTest
 *
 */
class AdminPanelRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router \Starter\routers\AdminPanelRouter
     */
    private $router;

    public function setUp() {
        $this->router = new SilentAdminPanelRouter();
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::__construct
     */
    public function test_construct() {
        new SilentAdminPanelRouter();
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::index
     */
    public function test_index() {
        $this->router->index();
        self::assertNull(error_get_last());
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::login
     */
    public function test_login() {
        $this->router->login();
        self::assertNull(error_get_last());
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::edit_user_page
     */
    public function test_edit_user_page() {
        $this->router->edit_user_page($id = 1);
        self::assertNull(error_get_last());
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::add_user_page
     */
    public function test_add_user_page() {
        $this->router->add_user_page();
        self::assertNull(error_get_last());
    }
}
 