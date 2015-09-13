<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'view'.DS.'leftmenuview.php';

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
     * @var $view \Tools\view\LeftMenuView
     */
    private $view;

    public function setUp() {
        if(empty($this->view)) {
            $this->view = Application::get_class('\Tools\view\LeftMenuView');
        }
    }

    /**
     * @covers \Tools\view\LeftMenuView::get_extension
     */
    public function test_get_extension() {
        $this->assertTrue($this->view->get_extension() instanceof Tools);
    }

    /**
     * @covers \Tools\view\LeftMenuView::__construct
     */
    public function test_construct() {
        new \Tools\view\LeftMenuView();
    }

    /**
     * @covers \Tools\view\LeftMenuView::render
     */
    public function test_render() {
        ob_start();
        $this->view->render();
        ob_end_clean();
        $this->assertNull(error_get_last());
    }
}
