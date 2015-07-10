<?php

class AdminPanelRouterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $router AdminPanelRouter
     */
    private $router;

    public function setUp() {
        if(empty($this->router)) {
            $this->router = Application::get_class('AdminPanelRouter');
        }
    }

    /**
     * @covers AdminPanelRouter::index
     */
    public function test_index() {
        ob_start();
        $this->router->index();
        ob_end_clean();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers AdminPanelRouter::edit_user_page
     */
    public function test_edit_user_page() {
        ob_start();
        $this->router->edit_user_page($id = 1);
        ob_end_clean();
        $this->assertNull(error_get_last());
    }

    /**
     * @covers AdminPanelRouter::add_user_page
     */
    public function test_add_user_page() {
        ob_start();
        $this->router->add_user_page();
        ob_end_clean();
        $this->assertNull(error_get_last());
    }
}
 