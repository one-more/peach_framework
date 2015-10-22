<?php

namespace test_classes\templates\starter\views;


use common\classes\Application;
use Starter\views\AdminPanel\AddUserView;

class AddUserViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view AddUserView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(AddUserView::class);
    }

    /**
     * @covers Starter\views\AdminPanel\AddUserView::__construct
     */
    public function test_construct() {
        new AddUserView();
    }

    /**
     * @covers Starter\views\AdminPanel\AddUserView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     *  @covers Starter\views\AdminPanel\AddUserView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\AddUserView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
