<?php

namespace Tools\views;

use classes\Application;
use classes\Request;
use views\ExtensionView;

class LeftMenuView extends ExtensionView {

	private $template_name = 'left_menu.tpl.html';

	public function get_extension() {
        return Application::get_class(\Tools::class);
    }

	public function __construct() {
		parent::__construct();

        $extension = $this->get_extension();
		$this->setTemplateDir($extension->get_path().DS.'templates'.DS.'menu');
	}

	public function render() {
		$this->assign('uri', Request::uri());
		return $this->get_template($this->template_name);
	}
    
    public function get_data() {
        return [];
    }

    public function get_template_name() {
        return $this->template_name;
    }
}