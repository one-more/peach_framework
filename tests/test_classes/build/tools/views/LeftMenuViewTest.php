<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'views'.DS.'leftmenuview.php';

use \Tools\views\LeftMenuView;
/**
 * Class SessionModelTest
 *
 */
class LeftMenuViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view LeftMenuView
     */
    private $view;

    public function setUp() {
        $this->view = \common\classes\Application::get_class(LeftMenuView::class);
    }

    /**
     * @covers \Tools\views\LeftMenuView::get_extension
     */
    public function test_get_extension() {
        self::assertTrue($this->view->get_extension() instanceof Tools);
    }

    /**
     * @covers \Tools\views\LeftMenuView::__construct
     */
    public function test_construct() {
        new \Tools\views\LeftMenuView();
    }

    /**
     * @covers \Tools\views\LeftMenuView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }

    /**
     * @covers \Tools\views\LeftMenuView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers \Tools\views\LeftMenuView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
