<?php

namespace test_classes\templates\starter\views;


use common\classes\Application;
use Starter\views\AdminPanel\EditUserView;

class EditUserViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view EditUserView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(EditUserView::class);
    }

    /**
     * @covers Starter\views\AdminPanel\EditUserView::__construct
     */
    public function test_construct() {
        new EditUserView(1);
    }

    /**
     * @covers Starter\views\AdminPanel\EditUserView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     *  @covers Starter\views\AdminPanel\EditUserView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers Starter\views\AdminPanel\EditUserView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
