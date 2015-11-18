<?php

namespace test_classes\templates\starter\views;


use common\classes\Application;
use Starter\views\AdminPanel\NavbarView;

class NavbarViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view NavbarView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(NavbarView::class);
    }

    /**
     * @covers Starter\views\AdminPanel\NavbarView::__construct
     */
    public function test_construct() {
        new NavbarView();
    }

    /**
     * @covers Starter\views\AdminPanel\NavbarView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     *  @covers Starter\views\AdminPanel\NavbarView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\NavbarView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
