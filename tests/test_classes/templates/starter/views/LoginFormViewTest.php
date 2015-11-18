<?php

namespace test_classes\templates\starter\views;


use common\classes\Application;
use Starter\views\AdminPanel\LoginFormView;

class LoginFormViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view LoginFormView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(LoginFormView::class);
    }

    /**
     * @covers Starter\views\AdminPanel\LoginFormView::__construct
     */
    public function test_construct() {
        new LoginFormView();
    }

    /**
     * @covers Starter\views\AdminPanel\LoginFormView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     *  @covers Starter\views\AdminPanel\LoginFormView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\LoginFormView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
