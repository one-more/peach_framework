<?php

class SilentAdminPanelRouter extends AdminPanelRouter {

    protected function show_result(AjaxResponse $response) {}
}

/**
 * Class AdminPanelRouterTest
 *
 * @method bool assertNull($var)
 */
class AdminPanelRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router AdminPanelRouter
     */
    private $router;

    public function setUp() {
        if(empty($this->router)) {
            $this->router = new SilentAdminPanelRouter();
        }
    }

    /**
     * @covers AdminPanelRouter::index
     */
    public function test_index() {
        $this->router->index();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers AdminPanelRouter::edit_user_page
     */
    public function test_edit_user_page() {
        $this->router->edit_user_page($id = 1);
        $this->assertNull(error_get_last());
    }

    /**
     * @covers AdminPanelRouter::add_user_page
     */
    public function test_add_user_page() {
        $this->router->add_user_page();
        $this->assertNull(error_get_last());
    }
}
 