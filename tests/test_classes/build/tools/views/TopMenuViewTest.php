<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'views'.DS.'topmenuview.php';

use \Tools\views\TopMenuView;
/**
 * Class SessionModelTest
 *
 */
class TopMenuViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view \Tools\views\TopMenuView
     */
    private $view;

    public function setUp() {
        $this->view = \common\classes\Application::get_class(TopMenuView::class);
    }

    /**
     * @covers \Tools\views\TopMenuView::get_extension
     */
    public function test_get_extension() {
        self::assertTrue($this->view->get_extension() instanceof Tools);
    }

    /**
     * @covers \Tools\views\TopMenuView::__construct
     */
    public function test_construct() {
        new TopMenuView();
    }

    /**
     * @covers \Tools\views\TopMenuView::render
     */
    public function test_render() {
        $this->view->render();
        self::assertNull(error_get_last());
    }
}
