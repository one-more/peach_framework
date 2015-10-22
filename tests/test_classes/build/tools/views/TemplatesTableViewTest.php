<?php
require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'views'.DS.'templatestableview.php';

use \Tools\views\TemplatesTableView;
/**
 * Class SessionModelTest
 *
 */
class TemplatesTableViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view \Tools\views\TemplatesTableView
     */
    private $view;

    public function setUp() {
        $this->view = \common\classes\Application::get_class(TemplatesTableView::class);
    }

    /**
     * @covers \Tools\views\TemplatesTableView::get_extension
     */
    public function test_get_extension() {
       self::assertTrue($this->view->get_extension() instanceof Tools);
    }

    /**
     * @covers \Tools\views\TemplatesTableView::__construct
     */
    public function test_construct() {
        new TemplatesTableView();
    }

    /**
     * @covers \Tools\views\TemplatesTableView::render
     */
    public function test_render() {
        $this->view->render();
       self::assertNull(error_get_last());
    }
    
    /**
     * @covers \Tools\views\TemplatesTableView::get_data
     */
    public function test_get_data() {
        $this->view->get_data();
        self::assertNull(error_get_last());
    }

    /**
     * @covers \Tools\views\TemplatesTableView::get_template_name
     */
    public function test_get_template_name() {
        $this->view->get_template_name();
        self::assertNull(error_get_last());
    }
}
