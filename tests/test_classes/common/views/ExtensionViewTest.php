<?php

use common\views\ExtensionView;
use common\classes\Application;

class FakeExtensionView extends ExtensionView {

    public function get_extension() {
        return Application::get_class(Tools::class);
    }

    public function render() {
        return '';
    }
    
    public function get_data() {
        return [];
    }
    
    public function get_template_name() {
        return '';
    }
}

class ExtensionViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $view FakeExtensionView
     */
    private $view;

    /**
     * @covers ExtensionView::__construct
     */
    public function test_construct() {
        new FakeExtensionView();
    }

    public function setUp() {
        $this->view = new FakeExtensionView();
    }

    /**
     * @covers ExtensionView::get_lang_vars_base_dir
     */
    public function test_get_lang_vars_base_dir() {
        $ext = $this->view->get_extension();
        self::assertEquals($ext->get_lang_path(), $this->view->get_lang_vars_base_dir());
    }
}
