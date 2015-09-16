<?php

class FakeTemplateView extends TemplateView {

    public function render() {}
}

class TemplateViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view TemplateView
     */
    private $view;

    public function setUp() {
        if(empty($this->view)) {
            $this->view = new FakeTemplateView();
        }
    }

    /**
     * @covers TemplateView::__construct
     */
    public function test_construct() {
        new FakeTemplateView();
    }

    /**
     * @covers TemplateView::get_lang_vars_base_dir
     */
    public function test_get_lang_vars_base_dir() {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        $template_class = $system->get_template();

    }
}
