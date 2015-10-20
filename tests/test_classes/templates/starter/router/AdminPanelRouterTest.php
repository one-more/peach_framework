<?php

class SilentAdminPanelRouter extends \Starter\routers\AdminPanelRouter {

    public function __destruct() {}

    protected function show_result(GetResponse $response) {}
}

\Starter::$current_router = \Starter\routers\AdminPanelRouter::class;

/**
 * Class AdminPanelRouterTest
 *
 * @method bool assertNull($var)
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
     * @covers \Starter\routers\AdminPanelRouter::index
     */
    public function test_index() {
        $this->router->index();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::edit_user_page
     */
    public function test_edit_user_page() {
        $this->router->edit_user_page($id = 1);
        $this->assertNull(error_get_last());
    }

    /**
     * @covers \Starter\routers\AdminPanelRouter::add_user_page
     */
    public function test_add_user_page() {
        $this->router->add_user_page();
        $this->assertNull(error_get_last());
    }
}
 