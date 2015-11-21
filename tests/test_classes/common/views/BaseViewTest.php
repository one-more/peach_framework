<?php

namespace test_classes\common\views;

use common\classes\Application;
use common\models\TemplateViewModel;
use Starter\views\AdminPanel\LeftMenuView;

class TestView extends LeftMenuView {
    /**
     * @var \Smarty
     */
    public $template_engine;

    public $compile_dir;

    public $template_dirs = [];

    public function get_lang_file() {
        return ROOT_PATH.DS.'tests'.DS.'resource'.DS.'test_view.json';
    }

    public function get_lang_vars_base_dir() {
        return '';
    }
};

class BaseViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var TestView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(TestView::class);
    }

    /**
     * @covers common\traits\TraitView::set_compile_dir
     */
    public function test_set_compile_dir() {
        $this->view->set_compile_dir($this->view->compile_dir);
    }

    /**
     * @covers common\traits\TraitView::set_template_dir
     */
    public function test_set_template_dir() {
        $this->view->set_template_dir($this->view->template_dirs[0]);
    }

    /**
     * @covers common\traits\TraitView::add_template_dir
     */
    public function test_add_template_dir() {
        $this->view->add_template_dir($this->view->template_dirs[0]);
    }

    /**
     * @covers common\traits\TraitView::get_template_dir
     */
    public function test_get_template_dir() {
        self::assertEquals($this->view->template_dirs[0], $this->view->get_template_dir(0));
    }

    /**
     * @covers common\traits\TraitView::assign
     */
    public function test_assign() {
        $this->view->assign([uniqid('test', true) => 'test']);
        self::assertTrue(count($this->view->template_engine->getTemplateVars()) > 0);
    }

    /**
     * @covers common\traits\TraitView::get_lang_file
     */
    public function test_get_lang_file() {
        $file = $this->view->get_lang_vars_base_dir().DS.$this->view->get_lang_file();
        self::assertTrue(file_exists($file));
    }

    /**
     * @covers common\traits\TraitView::get_template
     */
    public function test_get_template() {
        $this->view->render();
    }

    /**
     * @covers common\traits\TraitView::load_lang_vars
     */
    public function test_load_lang_vars() {
        $this->view->load_lang_vars($this->view->get_lang_file());
        self::assertTrue(count($this->view->template_engine->getTemplateVars()) > 0);
    }

    /**
     * @covers common\traits\TraitView::get_lang_vars_array
     */
    public function test_get_lang_vars_array() {
        self::assertTrue(count($this->view->get_lang_vars_array()) > 0);
    }

    /**
     * @covers common\traits\TraitView::get_template_model
     */
    public function test_get_template_model() {
        self::assertTrue($this->view->get_template_model() instanceof TemplateViewModel);
    }
}
