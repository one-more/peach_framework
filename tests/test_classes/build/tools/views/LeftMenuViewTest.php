<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'views'.DS.'leftmenuview.php';

/**
 * Class SessionModelTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 */
class LeftMenuViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view \Tools\views\LeftMenuView
     */
    private $view;

    public function setUp() {
        if(empty($this->view)) {
            $this->view = Application::get_class('\Tools\views\LeftMenuView');
        }
    }

    /**
     * @covers \Tools\views\LeftMenuView::get_extension
     */
    public function test_get_extension() {
        $this->assertTrue($this->view->get_extension() instanceof Tools);
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
        ob_start();
        $this->view->render();
        ob_end_clean();
        $this->assertNull(error_get_last());
    }
}
