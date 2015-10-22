<?php

namespace test_classes\common\traits;


use common\classes\Application;
use common\models\TemplateViewModel;
use Starter\views\AdminPanel\LeftMenuView;
use Tools\models\TemplateModel;

class TraitViewTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $view LeftMenuView
     */
    private $view;

    public function setUp() {
        $this->view = Application::get_class(LeftMenuView::class);
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
        self::assertTrue(count($this->view->getTemplateVars()) > 0);
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
