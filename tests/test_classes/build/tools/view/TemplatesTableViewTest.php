<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'view'.DS.'templatestableview.php';

/**
 * Class SessionModelTest
 *
 * @method bool assertInternalType($a,$b)
 * @method bool assertCount($a,$b)
 * @method bool assertEquals($a,$b)
 * @method bool assertNull($var)
 * @method bool assertFalse($var)
 */
class TemplatesTableViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view \Tools\view\TemplatesTableView
     */
    private $view;

    public function setUp() {
        if(empty($this->view)) {
            $this->view = Application::get_class('\Tools\view\TemplatesTableView');
        }
    }

    /**
     * @covers \Tools\view\TemplatesTableView::get_extension
     */
    public function test_get_extension() {
        $this->assertTrue($this->view->get_extension() instanceof Tools);
    }

    /**
     * @covers \Tools\view\TemplatesTableView::__construct
     */
    public function test_construct() {
        new \Tools\view\TemplatesTableView();
    }

    /**
     * @covers \Tools\view\TemplatesTableView::render
     */
    public function test_render() {
        ob_start();
        $this->view->render();
        ob_end_clean();
        $this->assertNull(error_get_last());
    }
}
