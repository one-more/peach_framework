<?php

use common\views\TemplateView;
use common\classes\Application;

class FakeTemplateView extends TemplateView {

    public function render() {}

    public function get_data() {
        return [];
    }

    public function get_template_name() {
        return '';
    }
}

class TemplateViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view TemplateView
     */
    private $view;

    public function setUp() {
        $this->view = new FakeTemplateView();
    }

    /**
     * @covers common\views\TemplateView::__construct
     */
    public function test_construct() {
        new FakeTemplateView();
    }

    /**
     * @covers common\views\TemplateView::get_lang_vars_base_dir
     */
    public function test_get_lang_vars_base_dir() {
        /**
         * @var $system System
         */
        $system = Application::get_class(System::class);
        /**
         * @var $template \common\interfaces\Template
         */
        $template = $system->template;
        self::assertEquals($this->view->get_lang_vars_base_dir(), $template->get_lang_path());
    }
}
