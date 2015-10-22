<?php

namespace test_classes\templates\starter\views;


use common\classes\Application;
use Starter\views\AdminPanel\LeftMenuView;

class LeftMenuViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view LeftMenuView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(LeftMenuView::class);
    }

    /**
     * @covers Starter\views\AdminPanel\LeftMenuView::__construct
     */
    public function test_construct() {
        new LeftMenuView();
    }

    /**
     * @covers Starter\views\AdminPanel\LeftMenuView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     *  @covers Starter\views\AdminPanel\LeftMenuView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\LeftMenuView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
