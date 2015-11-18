<?php

namespace test_classes\templates\starter\views;


use common\classes\Application;
use Starter\views\AdminPanel\UsersTableView;

class UsersTableViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view UsersTableView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(UsersTableView::class);
    }

    /**
     * @covers Starter\views\AdminPanel\UsersTableView::__construct
     */
    public function test_construct() {
        new UsersTableView();
    }

    /**
     * @covers Starter\views\AdminPanel\UsersTableView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     *  @covers Starter\views\AdminPanel\UsersTableView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\UsersTableView::get_template_model
     */
    public function test_get_template_model() {
        $this->view->get_template_model();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\UsersTableView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
