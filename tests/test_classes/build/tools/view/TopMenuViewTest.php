<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'view'.DS.'topmenuview.php';

/**
 * Class SessionModelTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 */
class TopMenuViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view \Tools\view\TopMenuView
     */
    private $view;

    public function setUp() {
        if(empty($this->view)) {
            $this->view = Application::get_class('\Tools\view\TopMenuView');
        }
    }

    /**
     * @covers \Tools\view\TopMenuView::get_extension
     */
    public function test_get_extension() {
        $this->assertTrue($this->view->get_extension() instanceof Tools);
    }

    /**
     * @covers \Tools\view\TopMenuView::__construct
     */
    public function test_construct() {
        new \Tools\view\TopMenuView();
    }

    /**
     * @covers \Tools\view\TopMenuView::render
     */
    public function test_render() {
        ob_start();
        $this->view->render();
        ob_end_clean();
        $this->assertNull(error_get_last());
    }
}