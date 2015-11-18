<?php

namespace test_classes\templates\starter\router;

use common\classes\Application;
use Starter\routers\RestRouter;

class RestRouterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $router RestRouter
     */
    private $router;

    public function setUp() {
        $this->router = Application::get_class(RestRouter::class);
    }

    /**
     * @covers Starter\routers\RestRouter::__construct
     */
    public function test_construct() {
        new RestRouter();
    }

    /**
     * @covers Starter\routers\RestRouter::admin_panel_index_templates
     */
    public function test_admin_panel_index_templates() {
        ob_start();
        $this->router->admin_panel_index_templates();
        ob_end_clean();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\routers\RestRouter::admin_panel_users_templates
     */
    public function test_admin_panel_users_templates() {
        ob_start();
        $this->router->admin_panel_users_templates();
        ob_end_clean();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\routers\RestRouter::admin_panel_login
     */
    public function test_admin_panel_login() {
        ob_start();
        $this->router->admin_panel_login();
        ob_end_clean();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\routers\RestRouter::admin_panel_add_user
     */
    public function test_admin_panel_add_user() {
        ob_start();
        $this->router->admin_panel_add_user();
        ob_end_clean();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\routers\RestRouter::admin_panel_edit_user
     */
    public function test_admin_panel_edit_user() {
        ob_start();
        $this->router->admin_panel_edit_user(1);
        ob_end_clean();
        self::assertNull(error_get_last());
    }
}
